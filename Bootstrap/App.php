<?php
namespace Bootstrap;
/**
 *
 */
use Config\Config;
class App
{
    public static function run(){
        require_once(BASEPATH.'/Bootstrap/helper.php');
        self::getClass(
            self::getSeg(
                self::check()
            )
        );
    }
    public static function check(){
        $s = str_replace('index.php','',$_SERVER['SCRIPT_NAME']);
        return $s;
    }
    public static function getSeg(String $str){
        $r = explode($str,$_SERVER['REQUEST_URI'])[1];
        $r = explode('?',$r)[0];
        return $r;
    }
    public static function getClass(String $r){
        $seg = explode('/',$r);
        $seg = array_filter($seg);

        $c = (isset($seg[0]))?$seg[0]:'home';
        $m = (isset($seg[1]))?$seg[1]:'index';

        $c = "\\App\Controllers\\".ucfirst($c);
        $c = new $c;

        $slice = array_slice($seg,2);
        call_user_func_array([$c,$m],$slice);
    }
}