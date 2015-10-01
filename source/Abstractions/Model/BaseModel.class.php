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
    protected $createdAt;
    protected $lastUpdate;
    protected $author;

    protected $properties;

    protected $fields;

    protected $fieldPrefix;

    protected static $postType;
    protected $postTypeSlug;

    protected $post;

    //public function __construct(){};


    // Field Methods
    public function getField($fieldName, $returnAsArray = false)
    {
        if( !empty($this->postTypeSlug) ){
            // For CPTs only
            $fieldName = "_wpx_{$this->postTypeSlug}_$fieldName";
        }
        if( empty($this->fields) ){
            $this->fields = get_post_meta( $this->ID );
        }

        if( in_array( $fieldName, $this->fields ) ){
            if( $returnAsArray ){
                return $this->fields[$fieldName];
            } else {
                return reset( $this->fields[$fieldName] );
            }
        }

        return false;
    }

    // Magic Methods
    public function __get($property){
        // TODO: Search property with the class and within the meta_fields
        // TODO: transform someProperty_name to some_variable_name with and without leading underscore
        // TODO: Consider not pre-loading the fields, but to load them on demand. Reduces the DB stress??
    }

    public function __set($property)
    {

    }

    // Custom Post Types

    protected function registerCustomPostType()
    {

    }

    protected function addCustomField(){

    }

    // Static Methods
    // Traversing Methods

    public static function getAll()
    {
        $posts = Query::Custom(self::$postType)->all()->get();
//        $list = array();
//        foreach( $posts as $post ){
//            $list = new
//        }
    }

    public static function getByField($field, $value)
    {
        $operator = ( is_array( $value ) ? 'in' : '=' );
        $post = Query::Custom(self::$postType)->all()->meta( $field, $value, $operator )->get();
    }

    public static function getByTaxonomy($taxonomyName, $taxonomyTerm)
    {
        // TODO: Develop the necesary methods on Query
        $post = Query::Custom(static::$postType)->all()->term( $taxonomyName, $taxonomyTerm )->get();
    }

}