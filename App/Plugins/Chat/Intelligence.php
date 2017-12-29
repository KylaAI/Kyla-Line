<?php
namespace App\Plugins\Chat;

/**
* 
*/
use App\Main\Line;
class Intelligence
{
	
	public static function set($chat,$bot)
	{
		$profile = Line::getProfile($bot);
		if(preg_match('/hai/', strtolower($chat))){
			return "Hai Juga ".$profile->displayName;
		}
	}
}