<?php
/**
 * Developer: Page Carbajal (https://github.com/Page-Carbajal)
 * Date: 9/29/15, 8:20 PM
 * Generator: PhpStorm
 */

namespace WPExpress\Abstractions\Model;

use WPExpress\Query;


abstract class BaseModel
{

    // Basic WordPress
    protected $ID;
    protected $name;
    protected $title;
    protected $content;
    protected $excerpt;
    protected $author;

    protected $properties;

    protected static $postType;

    //public function __construct(){};

    public function __get($property){
        // TODO: Search property with the class and within the meta_fields
        // TODO: transform someProperty_name to some_variable_name with and without leading underscore
        // TODO: Consider not pre-loading the fields, but to load them on demand. Reduces the DB stress??
    }

    public function __set($property)
    {

    }

    // TODO: add static method getAll
    // TODO: add static method get($id)
    // TODO: add static method getByTaxonomy( $term, $value )
    // Static method getByMetadata( $key, $value )

    public static function getByMetadata($key, $value = null )
    {
//        $items = Query::Post()->postType(self::getPostType())->meta('metakey')->also()->meta( 'second_value', '100' )->get();
        $items = apply_filters( 'wpexpress-model-get-by-meta', $items );
        return $items;
    }
}