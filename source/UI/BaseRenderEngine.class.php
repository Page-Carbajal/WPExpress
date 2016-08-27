<?php


namespace WPExpress\UI;


abstract class BaseRenderEngine
{

    protected $type;
    protected $context;
    protected $typeExtension;
    protected $templatePath;
    protected $includeFileExtension;

    public function __construct( $context )
    {
        $this->context              = $context;
        $this->templatePath         = 'templates';
        $this->typeExtension        = 'php';
        $this->includeFileExtension = true;
    }


    public function setTemplatePath( $templatePath )
    {
        $this->templatePath = $templatePath;
        return $this;
    }


    public function excludeFileExtension()
    {
        $this->includeFileExtension = false;
    }


    public function getTemplateDirectory()
    {
        return untrailingslashit($this->templatePath);
    }


    public function getFileName( $fileName )
    {
        return ( $this->includeFileExtension ? "{$fileName}.{$this->typeExtension}" : $fileName );
    }


    abstract public function render( $filename );
}