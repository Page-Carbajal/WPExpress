<?php


namespace WPExpress\UI;


final class RenderEngine extends BaseRenderEngine
{

    public function __construct( $context = false )
    {
        $this->setContext($context);

        parent::__construct($context);
    }

    private function setContext( $context )
    {
        if( false !== $context && !empty( $context ) ) {
            $this->context = $context;
        }

        return $this;
    }

    public function render( $filename, $context = false )
    {
        $this->setContext($context);

        $filename = $filename . ( $this->includeFileExtension ? '.' . $this->typeExtension : '' );

        $maybePaths = array(
            untrailingslashit(get_stylesheet_directory()) . "/" . untrailingslashit($this->getTemplateDirectory()) . "/{$filename}", // Stylesheet directory with custom template path
            untrailingslashit(get_template_directory()) . "/" . untrailingslashit($this->getTemplateDirectory()) . "/{$filename}", // Template directory with custom template path
            untrailingslashit(get_stylesheet_directory()) . "/templates/{$filename}", // Stylesheet directory with default template path
            untrailingslashit(get_template_directory()) . "/templates/{$filename}", // Template directory with default template path
            untrailingslashit($this->getTemplateDirectory()) . "/{$filename}", // Custom template path
            untrailingslashit(dirname(__FILE__)) . "/../../resources/templates/{$filename}" // Default WPExpress templates
        );

        // Play nice with others
        $maybePaths = apply_filters('wpx_render_template_paths', $maybePaths);
        $located    = false;

        foreach( $maybePaths as $path ) {
            if( file_exists($path) ) {
                $located = $path;
                break;
            }
        }

        // Include file or notify error
        if( false !== $located ) {
            // Extracting the context allows the included file to access  the variables directly
            if( !empty( $this->context ) ) {
                extract($this->context);
            }

            include( $located );
        } else {
            // If not template was found then display the message
            echo '<h1>No template file found!</h1>';
            echo '<h3>Page Context</h3>';
            echo '<pre>';
            print_r($this->context);
            echo '</pre>';
        }

    }
}