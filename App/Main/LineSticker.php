<?php
namespace App\Main;
/**
* 
*/
use App\Main\Line;
class LineSticker
{
	public static function inputData($bot){

		$line = Line::get($bot);
		$reply = $line->replyText($bot->replyToken,"Mohon maaf aku tidak bisa melihat stiker yang kamu berikan.");
		if ($reply->isSucceeded()) {
    		echo 'Succeeded!';
    		return;
		}
		
		echo $reply->getHTTPStatus() . ' ' . $reply->getRawBody();
	}
}