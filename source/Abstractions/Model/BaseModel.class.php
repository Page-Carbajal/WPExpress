<?php
/**
 * Developer: Page Carbajal (https://github.com/Page-Carbajal)
 */

namespace WPExpress\Abstractions\Model;

use WPExpress\Query;


abstract class BaseModel
{

    // Basic WordPress
    protected $ID;
    protected $title;
    protected $content;
    protected $excerpt;
    protected $createdAt;
    protected $lastUpdate;
    protected $author;

    protected $properties;
    protected $parentPostType;
    protected $titleSupport = true;
    protected $editorSupport = true;
    protected $thumbnailSupport = true;
    protected $postTypeDescription = '';

    protected $fields;

    protected $fieldPrefix;

    protected $isPublic;
    protected $name;
    protected static $postType;
    protected $postTypeSlug;
    // Labels
    protected $nameLabel;
    protected $singularNameLabel;



    protected $post;

    //public function __construct(){};

    protected function setPublic(bool $public)
    {
        $this->isPublic = $public;
    }

    protected function getPostTypeLabels()
    {
        $labels = array(
            'name' => $this->nameLabel,
            'singular_name' => ( empty($this->singularNameLabel) ? $this->nameLabel : $this->singularNameLabel ),
        );
        return $labels;
    }

    protected function setPostTypeLabels()
    {
        // TODO: Add all the labels here
    }

    protected function setSupportedFeatures($supportTitle = true, $supportEditor = true, $supportThumbnail = false)
    {
        $this->titleSupport = $supportTitle;
        $this->editorSupport = $supportEditor;
        $this->thumbnailSupport = $supportThumbnail;
        return $this;
    }

    protected function getSupportedFeatures()
    {
        $features = array(
            'title' => $this->titleSupport,
            'editor' => $this->editorSupport,
            'thumbnail' => $this->thumbnailSupport,
            );
        return $features;
    }

    protected function registerCustomPostType()
    {
        $options =             array(
            'labels'              => $this->getPostTypeLabels(),
            'description'           => $this->postTypeDescription,
            'public'                => $this->isPublic,
            'show_ui'               => true,
            'capability_type'       => 'post',
            'map_meta_cap'          => true,
            'publicly_queryable'    => true,
            'menu_icon'			    => '',
            'menu_position'         => 1,
            'exclude_from_search'   => true,
            'hierarchical'          => false,
            'rewrite' 	            => array( 'slug' => $this->postTypeSlug ),
            'query_var'             => true,
            'supports' 	            => $this->getSupportedFeatures(),
            'has_archive'           => false,
            'show_in_nav_menus'     => false,
        );

        if(!empty($this->parentPostType)){
            $options['show_in_menu'] = "edit.php?post_type={$this->parentPostType}";
        }

        register_post_type( $this->getPostType(), $options);

        return $this;
    }

    private function loadCustomFields()
    {
        if( empty($this->fields) ){
            $this->fields = get_post_meta( $this->ID );
        }
        return $this;
    }

    // Field Methods
    public function getField($fieldName, $returnAsArray = false)
    {
        if( empty($this->fields) ){
            $this->fields = array();
        }
        if( !array_key_exists( $fieldName, $this->fields ) ){
            $value = get_post_meta( $this->ID, $fieldName );
            if( !empty( $value ) ){
                $this->fields[$fieldName] = $value;
            }
        }

        if( !empty($this->fields[$fieldName]) ){
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

        // Searches property within the class and within the meta_fields
        if( !empty($this->fields) && array_key_exists( $property, $this->fields ) ){
            return reset( $this->fields[$property] );
        }
        if( property_exists( $this, $property ) ){
            return $this->$property;
        }
        // TODO: Implement overridable data as follows. Instead of property_exists method
//        if( array_key_exists( $property, $this->data ) ){
//            return $this->data[$property];
//        }
        return false;
    }

    public function __set($property, $value)
    {
        if( property_exists( $this, $property ) ){
            return $this->$property = $value;
        }

        // TODO: Same as with the get Method
//        $this->data[$property] = $value;
    }

    protected function addCustomField(){

    }


    /****** Helper Methods ***********/
    public function getPermalink()
    {
        return get_permalink( $this->ID );
    }

    public function getThumbnail($size = 'full')
    {
        if ( $this->thumbnailSupport && has_post_thumbnail( $this->ID ) ){
            $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $this->ID ), $size );
            return $thumbnail;
        }

        return false;
    }

    public function getThumbnailURL()
    {
        if( !$this->thumbnailSupport ){
            return false;
        }

        if( $thumbnail = $this->getThumbnail() ){
            return $thumbnail[0];
        }
        return false;
    }


    /****** Static Methods **********/
    // Traversing Methods

    public static function getAll()
    {
        $posts = Query::Custom(static::getPostType())->all()->get();
        $list = array();
        foreach( $posts as $post ){
            $item = new static($post);
            $list[] = $item;
        }
        return $list;
    }

    public static function getByField($field, $value)
    {
        $posts = Query::Custom(static::getPostType())->all()->meta( $field, $value )->get();
        $list = array();
        foreach( $posts as $post ){
            $item = new static($post);
            $list[] = $item;
        }
        return $list;
    }

    public static function getByTaxonomy($taxonomyName, $taxonomyTerm)
    {
        $posts = Query::Custom(static::getPostType())->all()->term( $taxonomyName, $taxonomyTerm )->get();
        $list = array();
        foreach( $posts as $post ){
            $item = new static($post);
            $list[] = $item;
        }
        return $list;
    }

}
