<?php
/**
 * Developer: Page Carbajal (https://github.com/Page-Carbajal)
 * Date: October 21 2015, 6:30 PM
 */

namespace WPExpress\UI\HTML;



class Tags
{

    // This class is inspired by Groovy's FormTagLib.groovy
    // https://github.com/grails/grails-core/blob/0b1d6a6d02f2217643a69e8314f76078dacbce32/grails-plugin-gsp/src/main/groovy/org/grails/plugins/web/taglib/FormTagLib.groovy

    public function __construct()
    {

    }

    public static function textField($name, $attributes)
    {
        $defaultProperties = array(
            'type' => 'text',
            'id' => $name,
            'name' => $name
        );

        return self::renderInputField(array_merge( $defaultProperties, $attributes ));
    }

    public static function hiddenField($name, $attributes)
    {
        $defaultProperties = array(
            'type' => 'hidden',
            'id' => $name,
            'name' => $name,
        );

        return self::renderInputField(array_merge( $defaultProperties, $attributes ));
    }

    public static function checkboxField($name, $attributes)
    {
        $defaultProperties = array(
            'type' => 'checkbox',
            'id' => $name,
            'name' => $name,
        );

        return self::renderInputField(array_merge( $defaultProperties, $attributes ));
    }

    public static function radioButtonField($name, $attributes)
    {
        $defaultProperties = array(
            'type' => 'checkbox',
            'name' => $name,
        );

        return self::renderInputField(array_merge( $defaultProperties, $attributes ));
    }

    protected static function renderInputField($attributes)
    {
        $atts = self::arrayToHTMLAttributes( $attributes );

        $field = new \stdClass();
        $field->html = "<input {$atts} />";
        $field->properties = $attributes;

        return $field;
    }

    protected static function arrayToHTMLAttributes($list, $includeValue = true)
    {
        $attributes = array_map(function($key) use ($list, $includeValue)
        {
            if( is_bool($list[$key]) )
            {
                return ( $list[$key] ? $key : '');
            }
            if($key == 'value' && (!$includeValue)){
                return ""; //if value should not be rendered as an attribute
            }
            $propertyValue = esc_html( $list[$key] );
            return "{$key}=\"{$propertyValue}\"";
        },  array_keys($list) );
        return implode( ' ', $attributes );
    }

    /**
     * Generate a Select Field.
     * @param $name
     * @param $options: An array with items. Accepts simple array or nested array with the properties [text, value, selected]
     * @param $attributes
     * @return bool|string
     */
    public static function selectField($name, $options, $attributes)
    {
        if( empty($name) ){
            return false;
        }

        $properties = array( 'id' => $name, 'name' => $name );
        $source = '';

        foreach( $options as $item ){
            $option = '';
            if( !is_array($item) ){
                $option .= "<option value=\"{$item}\">{$item}</option>";
            } else {
                $option = "<option ";
                if( isset($item['value']) ){
                    $option .= " value=\"{$item['value']}\"";
                } else {
                    $option .= " value=\"{$item['text']}\"";
                }

                if( isset($item['selected']) && $item['selected'] == true ){
                    $option .= ' selected="selected" ';
                }
                $option = trim( $option ) . ">{$item['text']}</option>";
            }
            $source .= $option;
        }

        $attsString = self::arrayToHTMLAttributes( array_merge($properties, $attributes) );
        $output = "<select {$attsString}>{$source}</select>";

        $field = new \stdClass();
        $field->html = $output;
        $field->properties = array_merge( $properties, $attributes, array('options' => $options) );

        return $field;
    }


    public static function parseFields($collection)
    {
        $list = array();

        foreach( $collection as $item ){

            switch($item['type']){
                case "select":
                    $list[] = self::selectField($item['name'], $item['options'], $item['properties']);
                    break;
                case "radio":
                    break;
                case "radiobutton":
                    break;
                case "check":
                    $list[] = self::checkboxField($item['name'], $item['properties']);
                    break;
                case "checkbox":
                    $list[] = self::checkboxField($item['name'], $item['properties']);
                    break;
                default:
                    $list[] = self::textField($item['name'], $item['properties']);
                    break;
            }

        }

        return $list;
    }

}