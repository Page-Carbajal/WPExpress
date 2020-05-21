<?php


namespace WPExpress\Admin;


abstract class BaseMetaBox
{

    private $ID;
    private $title;
    private $screen;
    private $context;
    private $priority;

    private function __construct( $ID, $title )
    {
        $this->ID       = $ID;
        $this->title    = $title;
        $this->screen   = null;
        $this->context  = 'advanced';
        $this->priority = 'default';

    }



    /****Access Methods****/

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle( $title )
    {
        $this->title = empty( $title ) ? $this->title : $title;

        return $this;
    }

    public function getScreen()
    {
        return $this->screen;
    }

    public function setScreen( $screen )
    {
        $this->screen = $screen;

        return $this;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext( $context )
    {
        $options       = array( 'advanced' );
        $this->context = in_array($context, $options) ? $context : $this->context;

        return $this;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority( $priority )
    {
        $options        = array( 'default' );
        $this->priority = in_array($priority, $options) ? $priority : $this->priority;

        return $this;
    }

}