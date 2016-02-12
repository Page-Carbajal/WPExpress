<?php


namespace WPExpress;


use WPExpress\Model\BaseModel;
use WPExpress\Model\BaseModelInterface;


final class Page extends BaseModel implements BaseModelInterface
{

    public function __construct( $bean )
    {

        static::$postType   = 'post';
        $this->postTypeSlug = ''; // Set Yours for to register your CPT

        $post = null;
        if( is_int($bean) ) {
            $post = get_post($bean);
        } elseif( ( $bean instanceof \WP_Post ) && !empty( $bean->ID ) ) {
            $post = $bean;
        }

        if( !empty( $post ) ) {
            $this->post    = $post;
            $this->ID      = $post->ID;
            $this->title   = $post->post_title;
            $this->content = $post->post_content;
            $this->excerpt = $post->post_excerpt;
            // TODO: Load the rest of the significant properties
        }

    }

    /**
     * Override this function on your theme to set your Custom Post Type
     *
     * @return string. The custom post type.
     */
    public static function getPostType()
    {
        if( !isset( self::$postType ) ) {
            static::$postType = 'page';
        }
        return static::$postType;
    }

}