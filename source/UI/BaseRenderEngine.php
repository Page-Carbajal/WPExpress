<?php


namespace WPExpress\UI;


abstract class BaseRenderEngine
{

    protected $type;
    protected $context;
    protected $typeExtension;
    protected $templatePath;
    protected $includeFileExtension;
    protected $maybePaths;

    public function __construct( $context = false )
    {
        $this->templatePath         = 'templates';
        $this->typeExtension        = 'php';
        $this->includeFileExtension = true;

        $this->setContext($context);
    }


    protected function setContext( $context )
    {
        if( false !== $context && !empty( $context ) ) {
            $this->context = $context;
        }

        return $this;
    }


    protected function getMaybePaths( $filename )
    {
        $filename = $this->getFileName($filename);

        $maybePaths = array(
            untrailingslashit(get_stylesheet_directory()) . "/" . untrailingslashit($this->getTemplateDirectory()) . "/{$filename}", // Stylesheet directory with custom template path
            untrailingslashit(get_template_directory()) . "/" . untrailingslashit($this->getTemplateDirectory()) . "/{$filename}", // Template directory with custom template path
            untrailingslashit(get_stylesheet_directory()) . "/{$this->templatePath}/{$filename}", // Stylesheet directory with custom templates path
            untrailingslashit(get_template_directory()) . "/{$this->templatePath}/{$filename}", // Template directory with custom templates path
            untrailingslashit(get_stylesheet_directory()) . "/templates/{$filename}", // Stylesheet directory with default templates path
            untrailingslashit(get_template_directory()) . "/templates/{$filename}", // Template directory with default templates path
            untrailingslashit($this->getTemplateDirectory()) . "/{$filename}", // Custom template path
            untrailingslashit(dirname(__FILE__)) . "/../../resources/templates/settings-page.php" // Default WPExpress templates
        );

        // Play nice with others
        $this->maybePaths = apply_filters("wpx_{$filename}_render_paths", $maybePaths);

        return $maybePaths;
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


    protected function getTemplateNotFoundMessage()
    {
        ob_start();
        ?>
        <h1>No template file found!</h1>
        <h3>Page Context</h3>
        <pre> <?php print_r($this->context); ?> </pre>
        <?php
        $message = ob_get_contents();
        ob_end_clean();

        return $message;
    }


    abstract public function render( $filename );
}