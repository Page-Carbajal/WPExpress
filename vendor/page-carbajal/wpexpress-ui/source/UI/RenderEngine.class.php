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
    protected $useTypeAsExtension;

    public function __construct($templateFolderPath, $type = 'mustache')
    {
        $this->type = trim(strtolower($type));
        $this->useTypeAsExtension = true;
        $this->typeExtension = sanitize_title($this->type);

        if($templateFolderPath !== false && file_exists( $templateFolderPath ) ){
            $this->templateFolder = $templateFolderPath;
        }else{
            throw new \Exception( __('No such folder! - WPExpress @ Render Engine', 'wpexpress') );
        }

        $this->createDirectoryStructure();

    }

    public function setTypeAsExtension($flag)
    {
        $this->useTypeAsExtension = $flag;
    }

    private function createDirectoryStructure()
    {
        switch( $this->type ){
            case "twig":
                // Create the directory <path-to-template>/twig/cache
                $partialsPath = $this->getBaseDirectory() . '/cache/';
                if( !file_exists( $partialsPath ) ){
                    wp_mkdir_p( $partialsPath );
                }
                break;
            default:
                // Create the directory <path-to-template>/mustache/partials
                $partialsPath = $this->getBaseDirectory() . '/partials/';
                if( !file_exists( $partialsPath ) ){
                    wp_mkdir_p( $partialsPath );
                }
                $cachePath = $this->getBaseDirectory() . '/cache/';
                if( !file_exists( $cachePath ) ){
                    wp_mkdir_p( $cachePath );
                }
                break;
        }
    }

    public function getBaseDirectory()
    {
        return untrailingslashit($this->templateFolder);
    }

    private function parseFileName($fileName)
    {
        return  ( $this->useTypeAsExtension ? "{$fileName}.{$this->typeExtension}" : $fileName );
    }

    public function getTemplatePath( $fileName )
    {
        $pathToFile = trailingslashit( $this->getBaseDirectory() ) . $this->parseFileName($fileName) ;
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
                    $raw = $this->renderTwigTemplate( $fileName, $context );
                    break;
                default:
                    $raw = $this->renderMustacheTemplate( $fileName, $context );
                    break;
            }
            return $raw;
        }

        // TODO: Improve this message
        return "<strong>Not it!</strong>";
    }

    private function renderMustacheTemplate($fileName, $context)
    {
        $options = array();
//        $options['cache'] = $this->getBaseDirectory() . '/cache';
        $options['loader'] = new Mustache_Loader_FilesystemLoader( $this->getBaseDirectory() );
        $options['partials_loader'] = new Mustache_Loader_FilesystemLoader( $this->getBaseDirectory() . '/partials' );
        $options['charset'] = 'UTF-8';

        $options = apply_filters( 'wpex_set_mustache_engine_options', $options );

        $mustache = new Mustache_Engine($options);


        return $mustache->render( $fileName, $context );
    }

    private function renderTwigTemplate($fileName, $context)
    {
        $loader = new Twig_Loader_Filesystem( $this->getBaseDirectory() );
        $twig = new Twig_Environment( $loader, array( 'cache' => $this->getBaseDirectory() . '/cache' ) );
        $fileName = $this->parseFileName( $fileName );
        $template = $twig->loadTemplate( $fileName );
        return $template->render( $context );
    }

}