<?php


namespace WPExpress\Contracts;


abstract class BaseWordPressFilters
{
    protected function registerWordPressFilters(){
        add_action('init', array($this, 'onWordPressInit'));
    }
    
    abstract function onWordPressInit();
}