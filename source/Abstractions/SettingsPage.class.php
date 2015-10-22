<?php
/**
 * Developer: Page Carbajal (https://github.com/Page-Carbajal)
 * Date: October 21 2015, 12:32 PM
 */

namespace WPExpress\Abstractions;


use WPExpress\UI\RenderEngine;


abstract class SettingsPage
{

    protected $settingsPrefix;
    protected $pageTitle;
    protected $pageType;
    protected $menuTitle;
    protected $capabilities;
    protected $menuSlug;
    protected $fields;


    protected $settingsLegend;

    public function __construct( $title, $capabilities, $menuSlug = false, $settingsLegend = 'Settings' )
    {
        $this->capabilities = $capabilities;
        $this->settingsLegend = $settingsLegend;
        $this->setMenuTitle($title, $menuSlug)->registerFilters();
    }

    private function registerFilters()
    {
        add_filter( 'admin_init', array(&$this,) );
        return $this;
    }

    public function registerSettings()
    {

    }

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

    // Option Functions

    public function addOptionField($field, $groupName = 'default')
    {
        if(empty($this->fields)){
            $this->fields = array();
        }
        $this->fields[] = array( 'field' => $field, 'group' => $groupName );
        return $this;
    }

    public function render()
    {

        $renderer = new RenderEngine();

        echo $renderer->renderTemplate('settings-page', array());

    }

}