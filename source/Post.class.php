<?php


namespace WPExpress;


use WPExpress\Model\BaseModel;
use WPExpress\Model\BaseModelInterface;


final class Post extends BaseModel implements BaseModelInterface
{

    public function __construct( $bean )
    {

        $this->nameLabel           = 'Posts';
        $this->singularNameLabel   = 'Post';
        $this->postTypeDescription = 'Write your description here';
        $this->postTypeSlug        = 'post';

        $this->setSupportedFeatures(true, true, true);

        parent::__construct($bean);
    }

    /**
     * Override this function on your theme to set your Custom Post Type
     *
     * @return string. The custom post type.
     */
    public static function getPostType()
    {
        if( !isset( self::$postType ) ) {
            static::$postType = 'post';
        }
        return static::$postType;
    }

}