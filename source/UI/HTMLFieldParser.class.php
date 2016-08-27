<?php
/**
 * Developer: Page Carbajal (https://github.com/Page-Carbajal)
 * Date: October 21 2015, 6:30 PM
 */

namespace WPExpress\UI;


class HTMLFieldParser
{

    // This class is inspired by Groovy's FormTagLib.groovy

    private $fieldCollection;

    public function __construct( $fields )
    {
        if( !empty( $fields ) ) {
            $this->fields = $fields;
            $this->parseFields();
        }
    }

    public static function textField( $name, $attributes, $fieldProperties = null, $ID = null )
    {
        return self::renderInputField($name, 'text', $attributes, $fieldProperties, $ID);
    }

    public static function hiddenField( $name, $attributes, $fieldProperties = null, $ID = null )
    {
        return self::renderInputField($name, 'hidden', $attributes, $fieldProperties, $ID);
    }

    public static function checkboxField( $name, $attributes, $fieldProperties = null, $ID = null )
    {
        return self::renderInputField($name, 'checkbox', $attributes, $fieldProperties, $ID);
    }

    public static function radioButtonField( $name, $attributes, $fieldProperties = null, $ID = null )
    {
        return self::renderInputField($name, 'radio', $attributes, $fieldProperties, $ID);
    }

    private static function renderInputField( $name, $type, $attributes, $fieldProperties, $ID )
    {
        $controlID       = empty( $ID ) ? $name : $ID;
        $baseAttributes  = array( 'type' => $type, 'ID' => $controlID, 'name' => $name );
        $fieldProperties = empty( $fieldProperties ) ? array() : ( is_array($fieldProperties) ? $fieldProperties : array( $fieldProperties ) );
        if( isset( $fieldProperties['value'] ) ) {
            $baseAttributes['value'] = $fieldProperties['value'];
        }
        $attString = self::arrayToHTMLAttributes(array_merge($baseAttributes, $attributes));

        $field                         = new \stdClass();
        $field->html                   = "<input {$attString} />";
        $field->properties             = array_merge($baseAttributes, $attributes, $fieldProperties);

        return $field;
    }

    protected static function arrayToHTMLAttributes( $list, $includeValue = true )
    {
        $attributes = array_map(function ( $key ) use ( $list, $includeValue ) {
            if( is_bool($list[$key]) ) {
                return ( $list[$key] ? $key : '' );
            }
            if( $key == 'value' && ( !$includeValue ) ) {
                return ""; //if value should not be rendered as an attribute
            }
            $propertyValue = esc_html($list[$key]);
            return "{$key}=\"{$propertyValue}\"";
        }, array_keys($list));
        return implode(' ', $attributes);
    }

    /**
     * Generate a Select Field.
     * @param $name
     * @param $options : An array with items. Accepts simple array or nested array with the properties [text, value, selected]
     * @param $attributes
     * @return bool|string
     */
    public static function selectField( $name, $options, $attributes, $value = null, $fieldProperties = null, $ID = null )
    {
        if( empty( $name ) ) {
            return false;
        }

        if( empty( $options ) ) {
            trigger_error("Object <strong>{$name}</strong> has no valid options!", E_USER_WARNING);
            return false;
        }

        $fieldProperties   = empty( $fieldProperties ) ? array() : ( is_array($fieldProperties) ? $fieldProperties : array( $fieldProperties ) );
        $baseAttributes    = array( 'ID' => $name, 'name' => $name );
        $source            = '';
        $hasSelectedOption = false;

        foreach( $options as $item ) {
            $option         = '';
            $selectedOption = '';

            if( !$hasSelectedOption && ( isset( $item['selected'] ) || ( $item == $value ) || ( isset( $item['value'] ) && $value == $item['value'] ) ) ) {
                $selectedOption    = 'selected="selected"';
                $hasSelectedOption = true;
            }

            if( !is_array($item) ) {
                $option .= "<option {$selectedOption} value=\"{$item}\">{$item}</option>";
            } else {
                $option = "<option ";
                if( isset( $item['value'] ) ) {
                    $option .= " value=\"{$item['value']}\" {$selectedOption}";
                } else {
                    $option .= " value=\"{$item['text']}\" {$selectedOption}";
                }

                $option = trim($option) . ">{$item['text']}</option>";
            }
            $source .= $option;
        }

        $attsString = self::arrayToHTMLAttributes(array_merge($baseAttributes, $attributes));
        $output     = "<select {$attsString}>{$source}</select>";

        $field             = new \stdClass();
        $field->html       = $output;
        $field->properties = array_merge($baseAttributes, $attributes, array( 'options' => $options ), $fieldProperties);

        return $field;
    }

    public static function textArea( $name, $value, $attributes, $fieldProperties = null, $ID = null )
    {
        $controlID       = empty( $ID ) ? $name : $ID;
        $fieldProperties = empty( $fieldProperties ) ? array() : ( is_array($fieldProperties) ? $fieldProperties : array( $fieldProperties ) );
        $baseAttributes  = array( 'name' => $name, 'ID' => $controlID );
        $attsString      = self::arrayToHTMLAttributes(array_merge($baseAttributes, $attributes));

        $field             = new \stdClass();
        $field->html       = "<textarea {$attsString}>{$value}</textarea>";
        $field->properties = array_merge($baseAttributes, $attributes, $fieldProperties);

        return $field;
    }


    public function parseFields()
    {
        $list = array();

        foreach( $this->fields as $field ) {

            switch( $field->type ) {
                case "select":
                    // Why not simply pass the field object? Well, we could, but we'll be forcing you to use our FieldCollection. So we keep it cool.
                    $list[] = $this->selectField($field->name, $field->properties['options'], $field->attributes, $field->properties['value'], $field->properties, $field->ID);
                    break;
                case "radio":
                    $list[] = $this->radioButtonField($field->name, $field->attributes, $field->properties, $field->ID);
                    break;
                case "radiobutton":
                    $list[] = $this->radioButtonField($field->name, $field->attributes, $field->properties, $field->ID);
                    break;
                case "check":
                    $list[] = $this->checkboxField($field->name, $field->attributes, $field->properties, $field->ID);
                    break;
                case "checkbox":
                    $list[] = $this->checkboxField($field->name, $field->attributes, $field->properties, $field->ID);
                    break;
                case "textarea":
                    $list[] = $this->textArea($field->name, $field->properties['value'], $field->attributes, $field->properties, $field->ID);
                    break;
                default:
                    $list[] = $this->textField($field->name, $field->attributes, $field->properties, $field->ID);
                    break;
            }

        }

        return $list;
    }

}