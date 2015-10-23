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

//  def fieldImpl(GrailsPrintWriter out, Map attrs) {
//      resolveAttributes(attrs)
//
//      attrs.value = processFormFieldValueIfNecessary(attrs.name, attrs.value, attrs.type)
//
//      out << "<input type=\"${attrs.remove('type')}\" "
//          outputAttributes(attrs, out, true)
//      out << "/>"
//  }


    public static function selectField($name, $attributes)
    {

        return self::renderInputField($attributes);
    }
}