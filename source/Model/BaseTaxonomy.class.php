<?php


namespace WPExpress\Model;


use WPExpress\Database\Taxonomy;


abstract class BaseTaxonomy extends Taxonomy
{

    public function __construct( $bean )
    {
        parent::__construct($bean);
    }

    public function implementThumbnail()
    {
        // TODO: TBD
    }

    public function addField( $field )
    {
        // TODO: TBD
    }

    public function getFields()
    {
        // TODO: TBD
    }

    public function getField()
    {
        // TOD: TBD
    }

    // This methods belong to the Abstract Class WPExpress/Model/BaseTaxonomy

    //    protected static function getLabels( $name, $pluralName )
    //    {
    //        $labels = array(
    //            'name'                       => $name,
    //            'singular_name'              => $name,
    //            'search_items'               => "Search {$pluralName}",
    //            'popular_items'              => "Popular {$pluralName}",
    //            'all_items'                  => "All {$pluralName}",
    //            'parent_item'                => null,
    //            'parent_item_colon'          => null,
    //            'edit_item'                  => "Edit {$name}",
    //            'update_item'                => "Update {$name}",
    //            'add_new_item'               => "Add New {$name}",
    //            'new_item_name'              => "New {$name} Name",
    //            'separate_items_with_commas' => "Separate {$pluralName} with commas",
    //            'add_or_remove_items'        => "Add or remove {$pluralName}",
    //            'choose_from_most_used'      => "Choose from the most used {$pluralName}",
    //            'not_found'                  => "No {$pluralName} found.",
    //            'menu_name'                  => "{$pluralName}",
    //        );
    //
    //        if( function_exists('apply_filters') ) {
    //            $labels = apply_filters("wpex_taxonomy_{$name}_labels", $labels);
    //        }
    //
    //        return $labels;
    //    }
    //
    //    protected function setCapabilities( $capabilities )
    //    {
    //        $this->capabilities = $capabilities;
    //    }
    //
    //    protected function getCapabilities()
    //    {
    //        return $this->capabilities;
    //    }
    //
    //
    //    /** CRUD Methods **/
    //
    //    public static function create( $name, $pluralName, $forObjects = null, $slug = false, $attributes = null )
    //    {
    //        $safeName = sanitize_title($name);
    //
    //        $defaultAttributes = array(
    //            'labels'                => static::getLabels($name, $pluralName),
    //            'rewrite'               => array( 'slug' => ( $slug !== false ? $slug : $safeName ) ),
    //            'description'           => '',
    //            'public'                => true,
    //            'hierarchical'          => false,
    //            'show_ui'               => null,
    //            'show_in_menu'          => null,
    //            'show_in_nav_menus'     => null,
    //            'show_tagcloud'         => null,
    //            'show_in_quick_edit'    => null,
    //            'show_admin_column'     => false,
    //            'meta_box_cb'           => null,
    //            'capabilities'          => array(),
    //            'query_var'             => $safeName,
    //            'update_count_callback' => '',
    //            '_builtin'              => false,
    //        );
    //
    //        $objects = array();
    //        if( $forObjects != null ) {
    //            if( !is_array($forObjects) ) {
    //                $objects = array( $forObjects );
    //            } else {
    //                $objects = array_merge($objects, $forObjects);
    //            }
    //        }
    //
    //        if( is_array($attributes) ) {
    //            $defaultAttributes = array_merge($defaultAttributes, $attributes);
    //        }
    //
    //        register_taxonomy($safeName, $objects, $defaultAttributes);
    //
    //        return new self($safeName);
    //    }    
    
}