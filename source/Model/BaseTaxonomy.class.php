<?php


namespace WPExpress\Model;


use WPExpress\Database\Taxonomy;


abstract class BaseTaxonomy extends Taxonomy
{

    public function __construct( $bean )
    {
        parent::__construct($bean);
    }

    public function implementThumnbanil()
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
}