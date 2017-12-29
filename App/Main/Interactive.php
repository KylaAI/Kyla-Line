<?php
namespace App\Main;
/**
 *
 */
use App\Plugins\Script\PHPInput;
use App\Plugins\Chat\Intelligence;
class Interactive
{
    public static function send($bot=null){
        $chat = $bot->message->text;
        $msg = '';
    	if(preg_match('/<?php/',$chat)){
    		$msg = PHPInput::set($chat);
    	}
        else {
            $msg = Intelligence::set($chat,$bot);
        }
        return $msg;
    }
}
