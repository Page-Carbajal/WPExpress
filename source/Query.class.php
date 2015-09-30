<?php
/**
 * Developer: Page Carbajal (https://github.com/Page-Carbajal)
 * Date: 9/29/15, 6:37 PM
 * Generator: PhpStorm
 */

namespace WPExpress;


class Query
{

    protected $parameters;

    public function __construct()
    {
        $parameters = array( 'showposts' => 10 );
    }

    private function addParameter( $parameter, $value, $condition = null ){
        $this->parameters[$parameter] = $value;
    }

    public function all(){
        $this->limit(-1);
        return $this;
    }

    public function limit($total){
        if( is_int( $total ) ){
            $this->parameters['showposts']['value'] = intval( $total );
        }
        return $this;
    }
    // Post Methods
//    public function ID($ID)
//    {
//        $this->addParameter( 'p', $ID );
//        return $this;
//    }

    public function postType($type)
    {
        $this->addParameter( 'post_type', $type );
        return $this;
    }

    // Process Values
    private function buildArguments(){
        return $this->parameters;
    }

    public function get()
    {
        $query = new \WP_Query( $this->buildArguments() );
        $posts = $query->get_posts();
        wp_reset_postdata();
        return $posts;
    }

    // Static methods

    public static function Posts()
    {
        $query = new self();
        return $query->postType('post');
    }

}
