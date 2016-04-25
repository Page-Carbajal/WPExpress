<?php


namespace WPExpress\Admin;


use WPExpress\UI;
use WPExpress\UI\RenderEngine;
use WPExpress\UI\FieldCollection;


abstract class BaseSettingsPage
{

    protected $fields;
    protected $fieldPrefix = '__wpx_';
    protected $pageTitle;
    protected $pageType;
    protected $menuTitle;
    protected $userCapabilities;
    protected $menuSlug;
    protected $description;
    protected $customTemplatesPath;
    protected $templateExtension;
    protected $templateFolder;

    public function __construct( $title, $userCapabilities = 'manage_options', $menuSlug = false )
    {
        $this->fields              = new FieldCollection();
        $this->pageTitle           = $title;
        $this->userCapabilities    = $userCapabilities;
        $this->templateExtension   = empty( $this->templateExtension ) ? 'mustache' : $this->templateExtension;

        $this->setMenuTitle($title, $menuSlug)->registerFilters();
        // If pageType was not specified defaults to Tools menu
        $this->registerPage();
    }

    public function setTopParentMenu( $option )
    {
        $this->pageType = ( in_array($option, array( 'top', 'dashboard', 'posts', 'pages', 'settings', 'users', 'plugins', 'theme', 'tools' )) ? $option : null );

        return $this;
    }

    private function registerFilters()
    {
        add_filter('wp_loaded', array( $this, 'save' ), 10);
        return $this;
    }

    public function registerPage()
    {
        add_action('admin_menu', array( &$this, 'addMenuItem' ));
        return $this;
    }

    private function actionHookIsValid( $menuSlug, $parentSlug )
    {
        $menuSlug   = plugin_basename($menuSlug);
        $parentSlug = plugin_basename($parentSlug);
        $hookname   = get_plugin_page_hookname($menuSlug, $parentSlug);

        return !has_action($hookname);
    }

    public function addMenuItem()
    {
        switch( $this->pageType ) {
            case "top":
                if( $this->actionHookIsValid($this->menuSlug, '') ) {
                    add_menu_page($this->pageTitle, $this->menuTitle, $this->userCapabilities, $this->menuSlug, array( &$this, 'render' ));
                }
                break;
            case "dashboard":
                if( $this->actionHookIsValid($this->menuSlug, 'index.php') ) {
                    add_dashboard_page($this->pageTitle, $this->menuTitle, $this->userCapabilities, $this->menuSlug, array( &$this, 'render' ));
                }
                break;
            case "posts":
                if( $this->actionHookIsValid($this->menuSlug, 'edit.php') ) {
                    add_posts_page($this->pageTitle, $this->menuTitle, $this->userCapabilities, $this->menuSlug, array( &$this, 'render' ));
                }
                break;
            case "pages":
                if( $this->actionHookIsValid($this->menuSlug, 'edit.php?post_type=page') ) {
                    add_pages_page($this->pageTitle, $this->menuTitle, $this->userCapabilities, $this->menuSlug, array( &$this, 'render' ));
                }
                break;
            case "settings":
                if( $this->actionHookIsValid($this->menuSlug, 'options-general.php') ) {
                    add_options_page($this->pageTitle, $this->menuTitle, $this->userCapabilities, $this->menuSlug, array( &$this, 'render' ));
                }
                break;
            case "users":
                if( $this->actionHookIsValid($this->menuSlug, 'user.php') || $this->actionHookIsValid($this->menuSlug, 'profile.php') ) {
                    add_users_page($this->pageTitle, $this->menuTitle, $this->userCapabilities, $this->menuSlug, array( &$this, 'render' ));
                }
                break;
            case "plugins":
                if( $this->actionHookIsValid($this->menuSlug, 'plugins.php') ) {
                    add_plugins_page($this->pageTitle, $this->menuTitle, $this->userCapabilities, $this->menuSlug, array( &$this, 'render' ));
                }
                break;
            case "theme":
                if( $this->actionHookIsValid($this->menuSlug, 'themes.php') ) {
                    add_theme_page($this->pageTitle, $this->menuTitle, $this->userCapabilities, $this->menuSlug, array( &$this, 'render' ));
                }
                break;
            default:
                // Defaults to Tools Menu
                if( $this->actionHookIsValid($this->menuSlug, 'tools.php') ) {
                    add_management_page($this->pageTitle, $this->menuTitle, $this->userCapabilities, $this->menuSlug, array( &$this, 'render' ));
                }
                break;
        }

        return $this;
    }

    /**
     * Sets menu title, menu slug, and page title. Page title is concatenated with the translatable string " settings"
     * @param $menuTitle
     * @return $this
     */
    private function setMenuTitle( $menuTitle, $menuSlug = false )
    {
        $this->menuTitle   = $menuTitle;
        $this->menuSlug    = ( $menuSlug !== false ? sanitize_title($menuSlug) : sanitize_title($menuTitle) );
        $this->fieldPrefix = "__wex_{$this->menuSlug}_";
        $this->pageTitle   = $menuTitle;

        return $this;
    }

    /**
     * Handler to set a custom page title
     * @param $title
     * @return $this
     */
    public function setPageTitle( $title )
    {
        $this->pageTitle = $title;
        return $this;
    }

    public function setPageDescription( $description )
    {
        $this->description = $description;
        return $this;
    }

    public function setCustomTemplatePath( $path )
    {
        $this->customTemplatesPath = false;
        if( file_exists($path) ) {
            $this->customTemplatesPath = untrailingslashit($path);
        }
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
    public function getProperty( $property )
    {
        // TODO: Deprecate this function
        $this->getOptionValue($property);
    }

    /* Validate and Persist Data */
    public function save()
    {
        if( is_admin() && !empty( $_POST ) ) {
            do_action('wpExpressSettingsPageBeforeSave', $this, $_POST);

            foreach( $this->fields->toArray() as $fieldName => $field ) {

                $optionName = "{$this->fieldPrefix}{$fieldName}";

                if( isset( $_POST[$fieldName] ) ) {

                    update_option($optionName, $_POST[$fieldName]);
                    // Update the field value :D
                    $this->fields($fieldName)->setValue($_POST[$fieldName]);
                }

            }

            do_action('wpExpressSettingsPageAfterSave', $this, $_POST);
        }
    }

    public function delete()
    {
        // TODO: Delete all data based on the existent fields
    }


    public function fields( $name )
    {
        return $this->fields->field($name);
    }

    public function getOptionValue( $option )
    {
        $optionName = "{$this->fieldPrefix}{$option}";
        return get_option($optionName);
    }

    //DEPRECATED. Do not use!!!
    public function getValue( $fieldName )
    {
        // TODO: Deprecate this function
        return $this->getOptionValue($fieldName);
    }

    //DEPRECATED. Do not use!!!
    private function getSegments()
    {
        return '';
    }

    private function loadFieldValues()
    {
        foreach($this->fields->toArray() as $index => $field){
            $this->fields($index)->setValue( $this->getOptionValue($index) );
        }
    }

    private function getContext()
    {
        $this->loadFieldValues();
        $context           = array( 'pageTitle' => $this->pageTitle, 'description' => $this->description );
        $context['fields'] = $this->fields->parseFields(); // Returns HTML fields
        //        $context['segments'] = $this->getSegments();

        $context = apply_filters('wpExpressSettingsPageContext', $context);

        return $context;
    }

    private function getTemplatesPath()
    {
        $customTemplatesPath = untrailingslashit($this->customTemplatesPath);
        // Verify path and filename exists
        $filePath = "{$customTemplatesPath}/{$this->templateExtension}/{$this->menuSlug}.{$this->templateExtension}";
        if( ( $this->customTemplatesPath !== false ) && file_exists($filePath) ) {
            return $customTemplatesPath;
        }

        return untrailingslashit(dirname(__FILE__)) . "/../../resources/templates";
    }

    public function render()
    {
        $engine = new RenderEngine($this->getTemplatesPath(), $this->templateExtension);

        if( file_exists($this->getTemplatesPath() . "/{$this->templateExtension}/{$this->menuSlug}.{$this->templateExtension}") ) {
            echo $engine->renderTemplate($this->menuSlug, $this->getContext());
        } else {
            if( file_exists($this->getTemplatesPath() . "/{$this->templateExtension}/settings-page.{$this->templateExtension}") ) {
                echo $engine->renderTemplate('settings-page', $this->getContext());
            } else {
                throw new \Exception("Template file not found at <{$this->getTemplatesPath()}> - WPExpress @ SettingsPage.", 404);
            }
        }
    }

}