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

    protected $registeredMetaFields;
    protected $registeredMetaFieldArrays;


    protected $post;

    protected $settingsLegend;

    public function __construct( $title, $capabilities, $menuSlug = false, $settingsLegend = 'Settings' )
    {
        $this->post = null;

        $this->fields = array();
        $this->properties = array();
        $this->registeredMetaFieldArrays = array();
        $this->registeredMetaFields = array();

        $this->templateExtension = 'mustache';
        $this->customTemplatesPath = untrailingslashit(get_stylesheet_directory());
        $this->capabilities = $capabilities;
        $this->settingsLegend = $settingsLegend;
        $this->setMenuTitle($title, $menuSlug)->registerFilters();

        if( !empty( $_POST ) ){
            $this->post = $_POST;
        }

    }

    private function registerFilters()
    {
        add_filter( 'wp_loaded', array($this, 'save'), 10 );
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

    /* Validate and Persist Data */
    public function save()
    {
        if( !empty($_POST) ){
            do_action('wpExpressSettingsPageBeforeSave', $this, $_POST);
            foreach( $this->properties as $name => $value ){
                $fieldName = substr( $name, strlen($this->fieldPrefix), ( strlen($name) - strlen($this->fieldPrefix) ) );
                if( isset($_POST[$fieldName]) && !empty($_POST[$fieldName]) ){
                    update_option( $name, $_POST[$fieldName] );
                    $this->properties[$name] = $_POST[$fieldName];
                }
            }
            do_action('wpExpressSettingsPageAfterSave', $this, $_POST);
        }
    }

    public function registerMetaFieldsArray($name, $collection, $fieldType, $groupName, $customAttributes = array())
    {
        $name = sanitize_title($name);
        $this->registeredMetaFieldArrays[] = array( 'name' => $name, 'collection' => $collection, 'fieldType' => $fieldType, 'groupName' => $groupName, 'customAttributes' => $customAttributes );
        $this->properties["{$this->fieldPrefix}{$name}"] = '';
    }

    private function addMetaFieldsArray($name, $collection, $fieldType = 'text', $groupName = '', $customAttributes = array())
    {
        $name = sanitize_title($name);
        $propertyName = "{$this->fieldPrefix}{$name}";
        $this->properties[$propertyName] = $items = get_option( $propertyName, "" );
        $itemKeys = array_keys($items);

        foreach( $collection as $key => $value ){
//            $basicFieldProperties = $this->getFieldBasicProperties($fieldType, $name, $value, $groupName, true);
            $properties = array(
                'name' => $name . '[]',
                'id' => $name. "_{$key}",
                'value' => $key,
                'labelText' => $key,
                'group' => ( empty($groupName) ? '' : $groupName ),
            );

            if( in_array( $fieldType, array('checkbox', 'radiobutton') ) && in_array( $key, $items ) ){
                $properties['checked'] = true;
            }

            $this->fields[] = array( 'type' => $fieldType, 'name' => $name.'[]', 'properties' => array_merge( $properties, $customAttributes ) );
        }
    }

    public function registerMetaField($name, $labelText, $fieldType = 'text', $groupName = '', $customAttributes = array())
    {
        $name = sanitize_title($name);
        $this->registeredMetaFields[] = array( 'name' => $name, 'labelText' => $labelText, 'fieldType' => $fieldType, 'groupName' => $groupName, 'customAttributes' => $customAttributes );
        $this->properties["{$this->fieldPrefix}{$name}"] = '';
    }

    private function addMetaField($name, $labelText, $fieldType = 'text', $groupName = '', $customAttributes = array())
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

        $this->fields[] = array( 'type' => $fieldType, 'name' => $name, 'properties' => array_merge( $properties, $customAttributes ) );
    }

    public function processRegisteredFields()
    {
        foreach($this->registeredMetaFields as $field ){
            $this->addMetaField( $field['name'], $field['labelText'], $field['fieldType'], $field['groupName'], $field['customAttributes'] );
        }

        foreach($this->registeredMetaFieldArrays as $arrayField){
            $this->addMetaFieldsArray( $arrayField['name'], $arrayField['collection'], $arrayField['fieldType'], $arrayField['groupName'], $arrayField['customAttributes'] );
        }
    }

    public function getValue($fieldName)
    {
        $propertyName = "{$this->fieldPrefix}{$fieldName}";
        if( isset($this->properties[$propertyName]) ){
            if( empty($this->properties[$propertyName]) ){
                return $this->properties["{$this->fieldPrefix}{$fieldName}"] = get_option($propertyName, '');
            }
            return $this->properties[$propertyName];
        }
        return false;
    }

    private function getSegments()
    {

        return '';
    }

    private function getContext()
    {
        $context = array( 'pageTitle' => $this->pageTitle, 'description' => $this->description );
        $context['fields'] = Tags::parseFields($this->fields);
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
        $this->processRegisteredFields();
        echo $renderer->renderTemplate( $this->getTemplateFilePath(), $this->getContext() );
    }

}