<?php


namespace WPExpress\UI;


abstract class BaseResources
{

    abstract function getBaseDirectory();

    abstract function getBaseURL();

    public static function getResourceDirectory($fileName, $subDirectory = 'css')
    {
        $me = new static();
        $filePath = untrailingslashit($me->getBaseDirectory()) . "/{$subDirectory}/{$fileName}";
        if( file_exists( $filePath ) ){
            return $filePath;
        }
        return false;
    }

    public static function getResourceURL($fileName, $subDirectory = 'css')
    {
        if( self::getResourceDirectory( $fileName, $subDirectory ) !== false ){
            $me = new static();
            return  untrailingslashit($me->getBaseURL()) . "/{$subDirectory}/{$fileName}";
        }

        return false;
    }

}