<?php


namespace WPExpress\UI;


final class RenderEngine extends BaseRenderEngine
{
    public function render( $filename, $context = false )
    {
        $this->setContext($context);
        
        $maybePaths = $this->getMaybePaths($filename);
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
            echo $this->getTemplateNotFoundMessage();
        }

    }
}