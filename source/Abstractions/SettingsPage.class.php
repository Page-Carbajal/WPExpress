<?php
/**
 * Developer: Page Carbajal (https://github.com/Page-Carbajal)
 * Date: October 21 2015, 12:32 PM
 */

namespace WPExpress\Abstractions;


use Mustache_Engine;


abstract class SettingsPage
{

    protected $pageTitle;
    protected $menuTitle;
    protected $capabilities;
    protected $menuSlug;

    protected $textDomain = 'default_text_domain';

    abstract public function __construct();

    public function registerFilters()
    {

    }

    public function pluginPage()
    {
        // TODO: Set as plugin page
        return $this;
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
        // TODO: Render

    }

}