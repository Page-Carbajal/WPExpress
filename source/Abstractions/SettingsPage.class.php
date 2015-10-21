<?php
/**
 * Developer: Page Carbajal (https://github.com/Page-Carbajal)
 * Date: October 21 2015, 12:32 PM
 */

namespace WPExpress\Abstractions;


use WPExpress\UI\RenderEngine;


abstract class SettingsPage
{

    protected $pageTitle;
    protected $menuTitle;
    protected $capabilities;
    protected $menuSlug;

    protected $textDomain = 'default_text_domain';

    abstract public function __construct();

    private function registerFilters()
    {
        return $this;
    }

    public function pluginPage( $parent = 'settings' )
    {
        add_filter('after_setup_theme', array(&$this, 'addTopLevelPage' )); // TODO: Add to Menu
        return $this;
    }

    public function topLevelPage()
    {
        add_filter('after_setup_theme', array(&$this, 'addTopLevelPage'));
        return $this;
    }

    public function addTopLevelPage()
    {
        add_menu_page( $this->pageTitle, $this->menuTitle, 'administrator', $this->menuSlug, array(&$this, 'render') );
    }


    /**
     * Sets menu title, menu slug, and page title. Page title is concatenated with the translatable string " settings"
     * @param $menuTitle
     * @return $this
     */
    public function setMenuTitle($menuTitle)
    {
        $this->menuTitle = $menuTitle;
        $this->menuSlug = sanitize_title($menuTitle);
        $this->pageTitle = $menuTitle . ' ' . __('Settings', $this->textDomain);

        return $this;
    }

    public function render()
    {

        $renderer = new RenderEngine();

        echo $renderer->renderTemplate('settings-page', array());

    }

}