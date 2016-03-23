<?php


namespace WPExpress\Collections;


class MetaBoxCollection implements \ArrayAccess, \Countable
{

    private $container;
    private $selectedMetaBox;
    private $allowAjaxUpdate;


    public function __construct()
    {
        $this->container       = array();
        $this->selectedMetaBox = false;
        $this->allowAjaxUpdate = true;
    }

    private function newMetaBox( $ID, $title )
    {
        $box = new \stdClass();

        $box->ID         = $ID;
        $box->title      = $title;
        $box->screen     = null;
        $box->context    = 'advanced';
        $box->priority   = 'default';
        $box->fields     = array();
        $box->properties = array();

        return $box;
    }

    public function box( $title )
    {
        return $this->metaBox($title);
    }

    public function metaBox( $title )
    {
        $title                 = sanitize_title($title);
        $this->selectedMetaBox = isset( $this->container[$title] ) ? $title : false;
        return $this;
    }

    public function add( $title )
    {
        $ID = sanitize_title($title);

        if( isset( $this->container[$ID] ) ) {
            // Trigger a Warning
            trigger_error("A MetaBox with the {$title} is already part of the list!", E_USER_WARNING);
        }
        // Validates the given type against our list
        $this->container[$ID]  = $this->newMetaBox($ID, $title);
        $this->selectedMetaBox = $ID;

        return $this;
    }

    //    public function getID()
    //    {
    //        if( false !== $this->selectedMetaBox ) {
    //            $this->container[$this->selectedMetaBox]->ID;
    //        }
    //        return false;
    //    }
    //
    //    public function setID( $ID )
    //    {
    //        if( $this->selectedMetaBox !== false ) {
    //            return $this->container[$this->selectedMetaBox]->ID = $ID;
    //        }
    //        return $this;
    //    }

    public function getTitle()
    {
        if( false !== $this->selectedMetaBox ) {
            $this->container[$this->selectedMetaBox]->title;
        }
        return false;
    }

    public function setTitle( $title )
    {
        if( $this->selectedMetaBox !== false ) {
            return $this->container[$this->selectedMetaBox]->title = $title;
        }
        return $this;
    }

    public function getPriority()
    {
        if( false !== $this->selectedMetaBox ) {
            $this->container[$this->selectedMetaBox]->priority;
        }
        return false;
    }

    public function setPriority( $priority )
    {
        if( $this->selectedMetaBox !== false ) {
            return $this->container[$this->selectedMetaBox]->priority = $priority;
        }
        return $this;
    }

    public function getScreen()
    {
        if( false !== $this->selectedMetaBox ) {
            $this->container[$this->selectedMetaBox]->screen;
        }
        return false;
    }

    public function setScreen( $screen )
    {
        if( $this->selectedMetaBox !== false ) {
            return $this->container[$this->selectedMetaBox]->screen = $screen;
        }
        return $this;
    }

    public function getContext()
    {
        if( false !== $this->selectedMetaBox ) {
            $this->container[$this->selectedMetaBox]->context;
        }
        return false;
    }

    public function setContext( $context )
    {
        if( $this->selectedMetaBox !== false ) {
            return $this->container[$this->selectedMetaBox]->context = $context;
        }
        return $this;
    }

    public function getFields()
    {
        if( false !== $this->selectedMetaBox ) {
            return $this->container[$this->selectedMetaBox]->fields;
        }
        return null;
    }

    public function setFields( $fields )
    {
        if( ( $this->selectedMetaBox !== false ) && is_array($fields) ) {
            $this->container[$this->selectedMetaBox]->fields = $fields;
        }
        return $this;
    }

    public function allFields()
    {
        if( ( $this->selectedMetaBox !== false ) ) {
            $this->container[$this->selectedMetaBox]->fields = null;
        }
        return $this;
    }

    public function getProperties()
    {
        if( false !== $this->selectedMetaBox ) {
            $this->container[$this->selectedMetaBox]->properties;
        }
        return false;
    }

    public function setProperties( $properties )
    {
        if( ( $this->selectedMetaBox !== false ) && is_array($properties) ) {
            return $this->container[$this->selectedMetaBox]->properties = $properties;
        }
        return $this;
    }

    public function disableAjaxUpdate()
    {
        $this->allowAjaxUpdate = false;

        return $this;
    }

    /*****Contracts*****/
    /*****Implement Interface Methods*******/
    // ArrayAccess Methods
    public function offsetSet( $offset, $value )
    {
        if( !empty( $offset ) ) {
            //$this->fieldList[] = $value;
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists( $offset )
    {
        return isset( $this->container[$offset] );
    }

    public function offsetUnset( $offset )
    {
        unset( $this->container[$offset] );
    }

    public function offsetGet( $offset )
    {
        return isset( $this->container[$offset] ) ? $this->container[$offset] : null;
    }

    // Countable Methods
    public function count()
    {
        return count($this->container);
    }

    public function toArray()
    {
        return $this->container;
    }
}