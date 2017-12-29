<?php
namespace App\Helpers;
/**
 *
 */
use App\Helpers\Hub\Singleton;
use App\Helpers\Crayner_Machine;
use Config\Config;
class Telegram
{
    use Singleton;
    public static function __callStatic($method, $parameters){
        return self::getInstance()->Turn($method,...$parameters);
    }
    public static function Turn($method,...$parameters){
        $tel = Config::get('telegram');
        $data = self::Convert($parameters,$method);
        return Crayner_Machine::qurl($tel['urltel'].$tel['apikey'].'/'.$method.'?'.$data);
    }
    public static function Conf(){
        dd(Config::get('telegram'));
    }
    public static function Convert(array $data){
        $str = '';
        $num = count($data[0])-1;
        $a = 0;
        if(isset($data[0])){
            foreach($data[0] as $key=>$val){
                $str .= urlencode($key)."=";
	            if($key != 'url')$str .=urlencode($val);
	            else $str .=$val;
	            if($a<$num) $str .= "&&";
	            $a++;
	        }
	}
        return $str;
    }
}
