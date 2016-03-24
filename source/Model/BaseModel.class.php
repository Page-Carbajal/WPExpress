<?php


namespace WPExpress\Model;


use WPExpress\Query;
use WPExpress\UI\RenderEngine;
use WPExpress\UI\FieldCollection;
use WPExpress\Collections\MetaBoxCollection;


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
    protected $titleSupport        = true;
    protected $editorSupport       = true;
    protected $thumbnailSupport    = true;
    protected $postTypeDescription = '';

    protected $isPublic;
    protected $name;
    protected $postTypeSlug;
    protected $postType;
    // Labels
    protected $nameLabel;
    protected $singularNameLabel;
    // Custom Post Type Declarations
    protected $menuPosition;
    // MetaBoxes and Fields
    protected $metaBoxes;
    protected $fields;
    private   $fieldsMetaID = "__wex_fields";
    protected $templatePath;

    // Original Post Object
    protected $post;

    public function __construct( $bean = null )
    {
        $me   = new \ReflectionClass($this);
        $post = null;

        // If your class name is Book. Your Post Type would be 'book'
        $this->postType = sanitize_title($me->getShortName());

        if( is_int($bean) ) {
            $post = get_post($bean);
        } elseif( ( $bean instanceof \WP_Post ) && !empty( $bean->ID ) ) {
            $post = $bean;
        }

        // Load Basic post data
        if( !empty( $post ) ) {
            $this->post    = $post;
            $this->ID      = $post->ID;
            $this->title   = $post->post_title;
            $this->content = $post->post_content;
            $this->excerpt = $post->post_excerpt;
        }

        // Setup Basic post data
        if( empty( $this->nameLabel ) ) {
            $this->nameLabel         = $me->getShortName();
            $this->postTypeSlug      = sanitize_title($this->nameLabel);
            $this->singularNameLabel = $this->nameLabel;
        }

        //Metaboxes and Fields
        $this->metaBoxes = new MetaBoxCollection();
        $this->fields    = new FieldCollection();


        $this->registerCustomPostType()->registerFilters();
    }

    public function getPostType()
    {
        return $this->postType;
    }

    protected function setPublic( $public )
    {
        $this->isPublic = $public;
    }

    protected function getPostTypeLabels()
    {
        $labels = array(
            'name'          => ( $this->nameLabel ),
            'singular_name' => ( empty( $this->singularNameLabel ) ? $this->nameLabel : $this->singularNameLabel ),
        );
        return $labels;
    }

    protected function setPostTypeLabels( $customLabels )
    {
        $labels = $this->getPostTypeLabels();

        $labels                  = shortcode_atts($labels, $customLabels);
        $this->nameLabel         = $labels['name'];
        $this->singularNameLabel = $labels['singular_name'];
    }

    protected function setNameLabel( $name )
    {
        $this->nameLabel = $name;
    }

    protected function setSingularNameLabel( $singularName )
    {
        $this->singularNameLabel = $singularName;
    }

    protected function setSupportedFeatures( $supportTitle = true, $supportEditor = true, $supportThumbnail = false )
    {
        $this->titleSupport     = $supportTitle;
        $this->editorSupport    = $supportEditor;
        $this->thumbnailSupport = $supportThumbnail;
        return $this;
    }

    protected function getSupportedFeatures()
    {
        $features = array();
        if( $this->titleSupport ) {
            $features[] = 'title';
        }
        if( $this->editorSupport ) {
            $features[] = 'editor';
        }
        if( $this->thumbnailSupport ) {
            $features[] = 'thumbnail';
        }
        return $features;
    }

    protected function registerCustomPostType()
    {
        if( !post_type_exists($this->getPostType()) ) {

            $options = array(
                'labels'              => $this->getPostTypeLabels(),
                'description'         => $this->postTypeDescription,
                'public'              => $this->isPublic,
                'show_ui'             => true,
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
                'publicly_queryable'  => true,
                'menu_icon'           => '',
                'menu_position'       => ( isset( $this->menuPosition ) ? $this->menuPosition : 20 ),
                'exclude_from_search' => true,
                'hierarchical'        => false,
                'rewrite'             => array( 'slug' => $this->postTypeSlug ),
                'query_var'           => true,
                'supports'            => $this->getSupportedFeatures(),
                'has_archive'         => false,
                'show_in_nav_menus'   => false,
            );

            if( !empty( $this->parentPostType ) ) {
                $options['show_in_menu'] = "edit.php?post_type={$this->parentPostType}";
            }

            register_post_type($this->getPostType(), $options);

        }

        return $this;
    }

    protected function registerFilters()
    {
        // TODO: Create Abstract Class WordPressFilters. Invoke it's init method here
        // Set Fields Values
        // Render Metaboxes and Fields
        add_action('admin_init', array( $this, 'registerMetaBoxes' ));
        //        add_action('admin_head', array( $this, '' )); // Set with a script instead
        // Save Post MetaData
        add_action('save_post', array( &$this, 'saveFieldValues' ), 10, 2);
        // Register Scripts
        add_action('admin_enqueue_scripts', array( __CLASS__, 'registerScriptsAndStyles' ));

        // OTHER AVAILABLE HOOKS
        // Attachment Hooks
        // TODO: Consider implementation
        //        add_action( 'add_attachment', array( $this, '' ) );
        //        add_action( 'edit_attachment', array( $this, '' ) );
        // User Hooks. TODO: Implement in BaseUser
        //        add_action( 'admin_head', array( $this, '' ) );
        //        add_action( 'show_user_profile', array( $this, '' ), $this->metaBoxes->getPriority() );
        //        add_action( 'edit_user_profile', array( $this, '' ), $this->metaBoxes->getPriority() );
        //        add_action( 'personal_options_update', array( $this, '' ) );
        //        add_action( 'edit_user_profile_update', array( $this, '' ) );

        return $this;
    }

    public function registerMetaBoxes()
    {
        if( is_admin() ) {
            $me = new static();

            // If the type has not meta boxes create one by default
            if( count($me->metaBoxes) == 0 && count($me->fields) > 0 ) {
                $me->metaBoxes = new MetaBoxCollection();
                $me->metaBoxes->add("Properties for {$me->title}");
            }

            foreach( $me->metaBoxes->toArray() as $ID => $box ) {
                $arguments = array(
                    'currentMetaBox' => $ID,
                );
                add_meta_box($box->ID, $box->title, array( $this, 'renderFields' ), $me->getPostType(), $box->context, $box->priority, $arguments);
            }
        }
    }

    public function setTemplatePath( $templatePath )
    {
        if( file_exists($templatePath) ) {
            $this->templatePath = $templatePath;
        }

        return $this;
    }

    private function getMetaBoxContext( $instance, $fields )
    {
        $boxID    = isset( $params['args']['currentMetaBox'] ) ? $params['args']['currentMetaBox'] : 'default';
        $postType = $instance->getPostType();

        $context = array(
            'hasFields'        => count($fields) > 0,
            'fields'           => $fields,
            'saveChangesLabel' => 'Save Changes',
        );

        return apply_filters("metabox_context_{$boxID}_at_{$postType}", $context);
    }

    public function renderFields( $post, $params )
    {
        $me = new static($post);

        // Auto Load Field Values
        $me->loadFieldValues();

        $templatePath = empty( $me->templatePath ) ? untrailingslashit(dirname(__FILE__)) . "/../../resources/templates" : $me->templatePath;
        $engine       = new RenderEngine($templatePath);
        $boxID        = $params['args']['currentMetaBox'];
        $fields       = $me->fields->parseFields($me->metaBoxes->box($boxID)->getFields());

        echo $engine->renderTemplate('metabox-content', $this->getMetaBoxContext($me, $fields));
    }

    public static function registerScriptsAndStyles()
    {
        $baseDir = untrailingslashit(dirname(__FILE__));

        $style  = "{$baseDir}/../../resources/css/metabox.css";
        $script = "{$baseDir}/../../resources/js/metabox-validation.js";

        if( file_exists($style) ) {
            // wp_enqueue_style('wpexpress-metabox-style', $style);
            // Can't really enqueue a thing since this is not a plugin not a theme.
            // Therefore we will rely in this case to something rather unappealing
            add_action('admin_head', function () use ( $style ) {
                $rawCSS = file_get_contents($style);
                echo "<style type=\"text/css\">{$rawCSS}</style>";
            });
        }

        if( file_exists($script) ) {
            add_action('admin_footer', function () use ( $script ) {
                $rawJS = file_get_contents($script);
                echo "<script type=\"text/javascript\">{$rawJS}</script>";
            });
        }
    }

    /****Custom Field Methods****/

    public function fields( $name )
    {
        if( $this->fields instanceof FieldCollection ) {
            $this->fields->field($name); //Sets active field
        }
        return $this->fields; // Is a direct access to the property
    }

    // Load on constructor
    protected function loadFieldValues()
    {
        $meta = get_post_meta($this->ID, $this->fieldsMetaID, true);

        if( is_array($meta) ) {
            foreach( $this->fields->toArray() as $name => $field ) {
                $fieldName = sanitize_title($name);
                if( isset( $meta[$fieldName] ) ) {
                    $this->fields($name)->setValue($meta[$fieldName]);
                }
            }
        }

        return $this;
    }

    public function saveFieldValues( $postID, $post = false )
    {
        $screen = get_current_screen();
        if( $screen->post_type == $this->getPostType() ) {
            $meta = array();
            //$instance = new static($post);

            foreach( $this->fields->toArray() as $title => $field ) {
                $fieldID        = sanitize_title($title);
                $meta[$fieldID] = ( isset( $_POST ) && isset( $_POST[$fieldID] ) ) ? $_POST[$fieldID] : $this->fields($fieldID)->getValue();
            }

            update_post_meta($postID, $this->fieldsMetaID, $meta);
        }

        return $this;
    }


    /****Magic Methods****/
    public function __get( $property )
    {
        if( property_exists($this, $property) ) {
            return $this->$property;
        }

        return false;
    }

    public function __set( $property, $value )
    {
        if( property_exists($this, $property) ) {
            $this->$property = $value;
        }
    }


    /**** Helper Methods ****/
    public function getPermalink()
    {
        return get_permalink($this->ID);
    }

    public function getThumbnail( $size = 'full' )
    {
        if( $this->thumbnailSupport && has_post_thumbnail($this->ID) ) {
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($this->ID), $size);
            return $thumbnail;
        }

        return false;
    }

    public function getThumbnailURL()
    {
        if( !$this->thumbnailSupport ) {
            return false;
        }

        if( $thumbnail = $this->getThumbnail() ) {
            return $thumbnail[0];
        }
        return false;
    }

    /****CRUD Methods****/
    public function create( $title = null, $content = null )
    {
        $post = wp_insert_post(array(
            'post_type'    => $this->postType,
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => 'draft',
        ));

        return new static($post);
    }

    public function save()
    {
        $properties = array(
            'ID'           => $this->ID,
            'post_title'   => $this->title,
            'post_content' => $this->content,
            'post_excerpt' => $this->excerpt,
        );

        return wp_update_post($properties);
    }

    public static function delete( $ID = null )
    {
        return wp_delete_post($ID);
    }

    public function publish()
    {
        $this->setStatus('publish');
    }

    public function setStatus( $status )
    {
        $options = array( 'draft',
                          'future',
                          'pending',
                          'private',
                          'publish',
                          'trash',
        );
        if( in_array($status, $options) ) {
            wp_update_post(array( 'id' => $this->ID, 'post_status' => $status ));
        }
        return $this;
    }

    /****** Static Methods **********/
    // Traversing Methods

    public function getByID( $ID )
    {
        return new static($ID);
    }

    public static function getAll()
    {
        $posts = Query::Custom(static::getPostType())->all()->get();
        $list  = array();
        foreach( $posts as $post ) {
            $item   = new static($post);
            $list[] = $item;
        }
        return $list;
    }

    public static function getByField( $field, $value )
    {
        $posts = Query::Custom(static::getPostType())->all()->meta($field, $value)->get();
        $list  = array();
        foreach( $posts as $post ) {
            $item   = new static($post);
            $list[] = $item;
        }
        return $list;
    }

    public static function getByTaxonomy( $taxonomyName, $taxonomyTerm )
    {
        $posts = Query::Custom(static::getPostType())->all()->term($taxonomyName, $taxonomyTerm)->get();
        $list  = array();
        foreach( $posts as $post ) {
            $item   = new static($post);
            $list[] = $item;
        }
        return $list;
    }

}