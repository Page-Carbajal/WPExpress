<?php
/**
 * Developer: Page Carbajal (https://github.com/Page-Carbajal)
 * Date: October 21 15, 3:20 PM
 */

namespace WPExpress;


class UI
{

    public function __construct()
    {

    }

    public static function getResourceDirectory($fileName, $subDirectory = 'css')
    {
        $filePath = plugin_dir_path(__FILE__) . "../resources/{$subDirectory}/{$fileName}";
        if( file_exists( $filePath ) ){
            return $filePath;
        }
        return false;
    }

    public static function getResourceURL($fileName, $subDirectory = 'css')
    {
        if( self::getResourceDirectory( $fileName, $subDirectory ) !== false ){
            return plugin_dir_url(__FILE__) . "../resources/{$subDirectory}/{$fileName}";
        }

        return false;
    }

}