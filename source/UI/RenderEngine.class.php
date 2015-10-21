<?php
/**
 * Developer: Page Carbajal (https://github.com/Page-Carbajal)
 * Date: October 21 2015, 3:04 PM
 */


namespace WPExpress\UI;


use WPExpress\UI;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;



class RenderEngine
{
    protected $type;
    protected $typeExtension;
    protected $templatePath;
    protected $templateFolder;

    public function __construct($type = 'mustache')
    {
        $this->type = trim(strtolower($type));
        $this->typeExtension = sanitize_title($this->type);
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
        if( file_exists( $customTemplate = "$customPath/custom-templates/{$this->type}/{$templateName}.{$this->typeExtension}" )  ){
            $this->templateFolder = "$customPath/custom-templates/{$this->type}/";
            return file_get_contents($customTemplate);
        }

        if( file_exists( $filePath = UI::getResourceDirectory( "{$templateName}.{$this->typeExtension}", 'templates/mustache' ) ) ){
            $this->templateFolder = UI::getResourceDirectory( "", 'templates/mustache' );
            return file_get_contents($filePath);
        }
        return $template;
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