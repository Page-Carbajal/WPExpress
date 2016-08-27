<?php


namespace WPExpress\UI;


class FieldCollection implements \ArrayAccess, \Countable
{

    private $container;
    private $selectedFieldName;
    private $fieldTypes = array( 'hidden', 'text', 'textarea', 'checkbox', 'radiobutton', 'select' );


    public function __construct()
    {
        $this->container         = array();
        $this->selectedFieldName = false;
    }

    private function newField( $name, $type )
    {
        $field = new \stdClass();

        $field->ID         = sanitize_title($name); // TODO: Implement a port of sanitize title
        $field->name       = sanitize_title($name);
        $field->type       = $type;
        $field->value      = '';
        $field->attributes = array();
        $field->properties = array();

        return $field;
    }

    private function addField( $name, $type = null )
    {
        if( isset( $this->container[$name] ) ) {
            // Trigger a Warning
            trigger_error("A field named {$name} is already part of the list!", E_USER_WARNING);
        }
        // Validates the given type against our list
        $fieldType               = in_array($type, $this->fieldTypes) ? $type : 'text';
        $this->container[$name]  = $this->newField($name, $fieldType);
        $this->selectedFieldName = $name;
        return $this;
    }

    public function field( $name )
    {
        $this->selectedFieldName = isset( $this->container[$name] ) ? $name : false;
        return $this;
    }

    public function addHiddenInput( $name )
    {
        $this->addField($name, 'hidden');
        return $this;
    }

    public function addTextInput( $name )
    {
        $this->addField($name, 'text');
        return $this;
    }

    public function addTextArea( $name )
    {
        $this->addField($name, 'textarea');
        return $this;
    }

    public function addRadioButton( $name )
    {
        $this->addField($name, 'radiobutton');
        return $this;
    }

    public function addCheckBox( $name )
    {
        $this->addField($name, 'checkbox');
        return $this;
    }

    public function addSelect( $name, $options )
    {
        $this->addField($name, 'select');
        $this->setProperty('options', $options);
        return $this;
    }

    public function getID()
    {
        if( false !== $this->selectedFieldName ) {
            $this->container[$this->selectedFieldName]->ID;
        }
        return false;
    }

    public function setID( $ID )
    {
        if( $this->selectedFieldName !== false ) {
            return $this->container[$this->selectedFieldName]->ID = $ID;
        }
        return $this;
    }

    public function getAttribute( $att )
    {
        if( false !== $this->selectedFieldName && isset( $this->container[$this->selectedFieldName]->attributes[$att] ) ) {
            return $this->container[$this->selectedFieldName]->attributes[$att];
        }
        return false;
    }

    public function setAttribute( $att, $value )
    {
        if( $this->selectedFieldName !== false ) {
            $this->container[$this->selectedFieldName]->attributes[$att] = $value;
        }
        return $this;
    }

    public function getAttributes()
    {
        if( $this->selectedFieldName !== false ) {
            return $this->container[$this->selectedFieldName]->attributes;
        }
        return $this;
    }

    public function setAttributes( $atts )
    {
        if( $this->selectedFieldName !== false ) {
            $this->container[$this->selectedFieldName]->attributes = $atts;
        }
        return $this;
    }

    public function getProperty( $property )
    {
        if( false !== $this->selectedFieldName && isset( $this->container[$this->selectedFieldName]->properties[$property] ) ) {
            return $this->container[$this->selectedFieldName]->properties[$property];
        }
        return false;
    }

    private function setProperty( $property, $value )
    {
        if( $this->selectedFieldName !== false ) {
            $this->container[$this->selectedFieldName]->properties[$property] = $value;
        }
        return $this;
    }

    public function getLabel()
    {
        return $this->getProperty('label');
    }

    //Labels and related data
    public function addLabel( $text )
    {
        $this->setProperty('label', $text);
        return $this;
    }

    public function getValue()
    {
        return isset( $this->container[$this->selectedFieldName]->properties['value'] ) ? $this->container[$this->selectedFieldName]->properties['value'] : null;
    }

    public function setValue( $value )
    {
        $this->setProperty('value', $value);
        return $this;
    }


    /****ArrayField Methods****/


    /****Parse HTML****/
    public function parseFields( $subset = null )
    {
        if( empty($this->container) ){
            return null;
        }

        $fields = $this->container;
        if( is_array($subset) && count($subset) > 0 ) {
            $keys = array_keys($fields);
            $fields = array();
            foreach( $subset as $index ) {
                if( in_array($index, $keys) && isset($this->container[$index]) ) {
                    $fields[$index] = $this->container[$index];
                }
            }
        }
        $parser = new HTMLFieldParser($fields);
        return $parser->parseFields();
    }

    public function toArray()
    {
        return $this->container;
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

}