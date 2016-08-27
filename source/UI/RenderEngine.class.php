<?php


namespace WPExpress\UI;


final class RenderEngine extends BaseRenderEngine
{

    public function __construct( $context )
    {
        $this->context = $context;
        parent::__construct($context);
    }

    public function render( $filename )
    {
        extract($this->context );

        $filePath = $filename . '.' . ( $this->includeFileExtension ? $this->typeExtension : '' );

        if( !empty( $this->getTemplateDirectory() ) ) {
            $filePath = untrailingslashit($this->getTemplateDirectory()) . "/{$filePath}";
        }

        $templatePath = locate_template($filePath, false);
        if( !empty( $templatePath ) ) {
            include( $templatePath );
        } else {
            $templatePath = untrailingslashit(dirname(__FILE__)) . "/../../resources/templates/settings-page.php";
            if( file_exists($templatePath) ){
                include( $templatePath );
            } else {
                echo '<h1>No template file found!</h1>';
                echo '<h3>Page Context</h3>';
                echo '<pre>';
                print_r($this->context);
                echo '</pre>';
            }
        }
    }
}