<?php
/**
 * Developer: Page Carbajal (https://github.com/Page-Carbajal)
 * Date: October 21 2015, 3:04 PM
 */


namespace WPExpress\UI;


use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;



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
    }

    public function renderTemplate($templatePath, $context)
    {
        if( file_exists( $templatePath ) ){
            $template = file_get_contents( $templatePath );
            switch($this->type){
                default:
                    $raw = $this->renderMustacheTemplate($template, $context);
                    break;
            }
            return $raw;
        }

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
        // TODO: implement twig
    }

}