<?php


namespace WPExpress;


use WPExpress\Model\BaseModel;
use WPExpress\Model\BaseModelInterface;


final class Page extends BaseModel implements BaseModelInterface
{

    public function __construct( $bean )
    {

        $this->nameLabel           = 'Pages';
        $this->singularNameLabel   = 'Page';
        $this->postTypeDescription = 'Write your description here';
        $this->postTypeSlug        = 'page';

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
            static::$postType = 'page';
        }
        return static::$postType;
    }

}