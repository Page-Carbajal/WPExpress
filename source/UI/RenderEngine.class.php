<?php


namespace WPExpress\UI;


use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Twig_Loader_Filesystem;
use Twig_Environment;



class RenderEngine
{
    protected $type;
    protected $typeExtension;
    protected $templatePath;
    protected $templateFolder;

    public function __construct($type = 'mustache', $templateFolder = false)
    {
        $this->type = trim(strtolower($type));
        $this->typeExtension = sanitize_title($this->type);
        if($templateFolder !== false && file_exists( $templateFolder ) ){
            $this->templateFolder = $templateFolder;
        }

        $this->createDirectoryStructure();

    }

    private function createDirectoryStructure()
    {
        switch( $this->type ){
            case "twig":
                // Create the directory <path-to-template>/twig/cache
                $partialsPath = untrailingslashit( $this->getTemplatePath() ) . '/cache/';
                if( !file_exists( $partialsPath ) ){
                    wp_mkdir_p( $partialsPath );
                }
                break;
            default:
                // Create the directory <path-to-template>/mustache/partials
                $partialsPath = untrailingslashit( $this->getTemplatePath() ) . '/partials/';
                if( !file_exists( $partialsPath ) ){
                    wp_mkdir_p( $partialsPath );
                }
                break;
        }
    }

    public function getTemplatePath( $fileName = '' )
    {
        if( file_exists($fileName) ){
            return $fileName;
        }


        $pathToFile = "{$this->templateFolder}/{$this->type}/{$fileName}";
        if( file_exists( $pathToFile ) ){
            return $pathToFile;
        }

        return false;
    }

    public function renderTemplate($fileName, $context)
    {
        if( $template = $this->getTemplatePath( $fileName ) ){
            switch($this->type){
                case "twig":
                    $raw = $this->renderTwigTemplate( $template, $context );
                    break;
                default:
                    $raw = $this->renderMustacheTemplate($template, $context);
                    break;
            }
            return $raw;
        }

        // TODO: Improve this message
        return "<strong>Not it!</strong>";
    }

    private function renderMustacheTemplate($template, $context)
    {
        $options = array();
        $options['partials_loader'] = new Mustache_Loader_FilesystemLoader(untrailingslashit($this->templateFolder));

        $mustache = new Mustache_Engine($options);

        return $mustache->render($template, $context);
    }

    private function renderTwigTemplate($template, $context)
    {

        $loader = new Twig_Loader_Filesystem( $this->getTemplatePath() );
        $twig = new Twig_Environment( $loader, array( 'cache' => $this->getTemplatePath('cache') ) );
        $template = $twig->loadTemplate( $this->getTemplatePath($template) );
        return $template->render( $context );

    }

}