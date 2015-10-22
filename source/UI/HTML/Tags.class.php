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

    protected static function renderField($attributes)
    {
        return '<!-- -->';
    }

    public static function textField($name, $attributes)
    {

        return self::renderField($attributes);
    }

    public static function hiddenField($name, $attributes)
    {

        return self::renderField($attributes);
    }

    public static function selectField($name, $attributes)
    {

        return self::renderField($attributes);
    }
}