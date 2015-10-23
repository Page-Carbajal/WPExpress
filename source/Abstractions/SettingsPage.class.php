<?php
/**
 * Developer: Page Carbajal (https://github.com/Page-Carbajal)
 * Date: October 21 2015, 12:32 PM
 */

namespace WPExpress\Abstractions;


use WPExpress\UI;
use WPExpress\UI\RenderEngine;
use WPExpress\UI\HTML\Tags;


abstract class SettingsPage
{

    protected $fieldPrefix;
    protected $pageTitle;
    protected $pageType;
    protected $menuTitle;
    protected $capabilities;
    protected $menuSlug;
    protected $fields;
    protected $properties;
    protected $description;
    protected $customTemplatesPath;
    protected $templateExtension;
    protected $templateFolder;

    protected $post;

    protected $settingsLegend;

    public function __construct( $title, $capabilities, $menuSlug = false, $settingsLegend = 'Settings' )
    {
        $this->post = null;

        $this->fields = array();
        $this->properties = array();

        $this->templateExtension = 'mustache';
        $this->customTemplatesPath = untrailingslashit(get_stylesheet_directory());
        $this->capabilities = $capabilities;
        $this->settingsLegend = $settingsLegend;
        $this->setMenuTitle($title, $menuSlug)->registerFilters();

        // TODO: Process data persistence on POST
        // TODO: Add filter before saving data
        // TODO: Add filter after saving data

        if( !empty( $_POST ) ){
            $this->post = $_POST;
        }

    }

    /* Validate and Persist Data */
    public function save()
    {
        $abc = 1;
        if( !empty($_POST) ){
            foreach( $this->properties as $name => $value ){
                $fieldName = substr( $name, strlen($this->fieldPrefix), ( strlen($name) - strlen($this->fieldPrefix) ) );
                if( isset($_POST[$fieldName]) && !empty($_POST[$fieldName]) ){
                    update_option( $name, $_POST[$fieldName] );
                    $this->properties[$name] = $_POST[$fieldName];
                }
            }
        }
    }

    private function registerFilters()
    {
        add_filter( 'wp_loaded', array(&$this, 'save') );
        return $this;
    }

    public function registerSettings()
    {

    }

    // TODO: Delete this function
    public function pluginPage( $parent = 'settings' )
    {
        add_filter('after_setup_theme', array(&$this, 'addTopLevelPage' )); // TODO: Add to Menu
        return $this;
    }

    public function registerPage($type = 'options', $level = null)
    {
        $this->pageType = $type;

        add_action( 'admin_menu', array(&$this, 'addMenuItem') );

        return $this;
    }

    public function addMenuItem()
    {
        $abc = 111;

        switch($this->pageType){
            case "top":
                add_menu_page( $this->pageTitle, $this->menuTitle, $this->capabilities, $this->menuSlug, array(&$this, 'render') );
                break;
            case "dashboard":
                add_dashboard_page($this->pageTitle, $this->menuTitle, $this->capabilities, $this->menuSlug, array( &$this, 'render' ) );
                break;
            case "posts":
                add_posts_page($this->pageTitle, $this->menuTitle, $this->capabilities, $this->menuSlug, array( &$this, 'render' ) );
                break;
            case "pages":
                add_pages_page($this->pageTitle, $this->menuTitle, $this->capabilities, $this->menuSlug, array( &$this, 'render' ) );
                break;
            case "management":
                add_management_page($this->pageTitle, $this->menuTitle, $this->capabilities, $this->menuSlug, array( &$this, 'render' ) );
                break;
            case "users":
                add_users_page($this->pageTitle, $this->menuTitle, $this->capabilities, $this->menuSlug, array( &$this, 'render' ) );
                break;
            case "plugins":
                add_plugins_page($this->pageTitle, $this->menuTitle, $this->capabilities, $this->menuSlug, array( &$this, 'render' ) );
                break;
            case "theme":
                add_theme_page($this->pageTitle, $this->menuTitle, $this->capabilities, $this->menuSlug, array( &$this, 'render' ) );
                break;
            default:
                // "options" -> Settings
                add_options_page($this->pageTitle, $this->menuTitle, $this->capabilities, $this->menuSlug, array( &$this, 'render' ) );
                break;
        }

        return $this;
    }

    /**
     * Sets menu title, menu slug, and page title. Page title is concatenated with the translatable string " settings"
     * @param $menuTitle
     * @return $this
     */
    private function setMenuTitle($menuTitle, $menuSlug = false)
    {
        $this->menuTitle = $menuTitle;
        $this->menuSlug = ( $menuSlug !== false ? sanitize_title($menuSlug) : sanitize_title($menuTitle) ) ;
        $this->fieldPrefix = "__wex_{$this->menuSlug}_";
        $this->pageTitle = $menuTitle . ' ' . $this->settingsLegend;

        return $this;
    }

    /**
     * Handler to set a custom page title
     * @param $title
     * @return $this
     */
    public function setPageTitle($title)
    {
        $this->pageTitle = $title;
        return $this;
    }

    public function setPageDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setCustomTemplatePath($path)
    {
        $this->customTemplatesPath = $path;
        return $this;
    }

    public function useMustacheTemplates()
    {
        $this->templateExtension = 'mustache';
        return $this;
    }

    public function useTwigTemplates()
    {
        $this->templateExtension = 'twig';
        return $this;
    }

    // Option Functions

    /**
     * Get the property values for the settings page.
     *
     * @param $property
     * @return mixed|void
     */
    public function getProperty($property)
    {
        $propertyName = "{$this->fieldPrefix}{}";
        if( in_array($propertyName, array_keys($this->properties)) ){
            // If property exists return the value
            return $this->properties[$propertyName];
        }
        // Check the database for the value and return the result
        $this->properties[$propertyName] = $fieldValue = get_option( $propertyName, "" );

        return $fieldValue;
    }

    public function addMetaFieldsArray($name, $collection, $fieldType = 'text', $groupName = '', $customAttributes = array())
    {
        $name = sanitize_title($name);
        $propertyName = "{$this->fieldPrefix}{$name}";
        $this->properties[$propertyName] = $items = get_option( $propertyName, "" );

        foreach( $collection as $key => $value ){
//            $basicFieldProperties = $this->getFieldBasicProperties($fieldType, $name, $value, $groupName, true);
            $properties = array(
                'name' => $name . '[]',
                'id' => $name. "_{$key}",
                'value' => $value,
                'labelText' => $value,
                'group' => ( empty($groupName) ? '' : $groupName ),
            );

            if( in_array( $fieldType, array('checkbox', 'radiobutton') ) && in_array( $value, $items ) ){
                $properties['checked'] = true;
            }

            $this->fields[] = $this->getFieldTag( $fieldType, $name.'[]', array_merge( $properties, $customAttributes ) );
        }
    }

    public function addMetaField($name, $labelText, $fieldType = 'text', $groupName = '', $customAttributes = array())
    {
        $name = sanitize_title($name);
        // Add the field Markup
        // Get the value if any!
        $propertyName = "{$this->fieldPrefix}{$name}";
        $this->properties[$propertyName] = $fieldValue = get_option( $propertyName, "" );

        // Add the field Markup
        $properties = array( 'name' => $name, 'value' => $fieldValue, 'labelText' => $labelText );
        $properties['id'] = $properties['name'];
        if(!empty($group)){
            $properties['group'] = $groupName;
        }

        if( in_array( $fieldType, array('checkbox', 'radiobutton') ) ){
            if( $fieldValue == $name ){
                $properties['checked'] = true;
            }
        }

        $this->fields[] = $this->getFieldTag( $fieldType, $name, array_merge( $properties, $customAttributes ) );
    }


    private function getFieldTag($fieldType, $name, $attributes)
    {
        $field = false;

        switch($fieldType){
            case "select":
                break;
            case "radio":
            case "radiobutton":
                break;
            case "check":
            case "checkbox":
                $field = Tags::checkboxField($name, $attributes);
                break;
            default:
                $field = Tags::textField($name, $attributes);
                break;
        }

        return $field;
    }

    private function getSegments()
    {

        return '';
    }

    private function getContext()
    {
        $context = array( 'pageTitle' => $this->pageTitle, 'description' => $this->description );
        $context['fields'] = $this->fields;
        $context['segments'] = $this->getSegments();

        $context = apply_filters( 'wpExpressSettingsPageContext', $context );

        return $context;
    }

    private function getTemplateFilePath()
    {
        // Search for the custom file
        if( file_exists( $templatePath = "{$this->customTemplatesPath}/{$this->templateExtension}/{$this->menuSlug}.{$this->templateExtension}" )  ){
            $this->templateFolder = "{$this->customTemplatesPath}/{$this->templateExtension}/";
            return $templatePath;
        }

        // Else return default template path
        if( file_exists( $templatePath = UI::getResourceDirectory( "settings-page.{$this->templateExtension}", "templates/{$this->templateExtension}" ) ) ){
            $this->templateFolder = UI::getResourceDirectory( "", "templates/{$this->templateExtension}" );
            return $templatePath;
        }

        return false;
    }


    public function render()
    {
        $renderer = new RenderEngine();
        echo $renderer->renderTemplate( $this->getTemplateFilePath(), $this->getContext() );
    }

}