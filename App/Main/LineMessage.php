<?php
namespace App\Main;
/**
* 
*/

use App\Main\CommandList;
use App\Main\Line;
use App\Main\Interactive;

use Config\Config;

class LineMessage
{
	public static function inputData($bot){
		$CommandList = new CommandList($bot);
		$getCommand = $CommandList::getCommand();

		$message  = explode(' ',$bot->message->text);
		if(in_array(strtolower($message[0]),$getCommand)){
			$endpoint = $CommandList::{$message[0]}($bot);
		}
		else {
			$endpoint = Interactive::send($bot);
		}
		if(!empty($endpoint))
			Line::Messages($endpoint,$bot);
	}
}