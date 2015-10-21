<?php
/**
 * Developer: Page Carbajal (https://github.com/Page-Carbajal)
 * Date: October 21 2015, 3:04 PM
 */


namespace WPExpress\UI;


use Mustache_Engine;



class RenderEngine
{
    protected $type;
    protected $typeExtension;
    protected $templatePath;

    public function __construct($type = 'mustache')
    {
        $this->type = trim(strtolower($type));
        $this->type = sanitize_title($this->type);
    }

    public function renderTemplate($templateName, $context)
    {
        $template = $this->getTemplate($templateName);
        switch($this->type){
            default:
                $raw = $this->renderMustacheTemplate($template, $context);
                break;
        }
        return $raw;
    }

    private function getTemplate($templateName)
    {
        $template = false;
        $customPath = untrailingslashit(get_stylesheet_directory());
        if( file_exists( $customTemplate = "$customPath/custom-templates/{$this->type}/{templateName}.{$this->typeExtension}" )  ){
            $template = file_get_contents($customTemplate);
        }
        return $template;
    }

    private function renderMustacheTemplate($template, $context)
    {
        $mustache = new Mustache_Engine();
        return $mustache->render($template, $context);
    }

    private function renderTwigTemplate($template, $context)
    {
        // TODO: implement twig
    }

}