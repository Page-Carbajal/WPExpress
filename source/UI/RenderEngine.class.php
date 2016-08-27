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
            echo '<p>No template file found!</p>';
            echo '<h3>Context</h3>';
            echo '<pre>';
            print_r($this->context);
            echo '</pre>';
        }
    }
}