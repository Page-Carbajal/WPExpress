<?php


namespace WPExpress;


use WPExpress\Model\BaseModel;


final class Page extends BaseModel
{

    public function __construct( $bean )
    {
        $this->setSupportedFeatures(true, true, true);

        parent::__construct($bean);
    }

}