<?php
namespace App\Main;
/**
* 
*/
use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;





use Config\Config;
class Line
{
	
	public static function get($bot)
	{
		$httpClient = new CurlHTTPClient(Config::line('access-token'));
		$line = new LINEBot($httpClient,['channelSecret' =>Config::line('secret')]);
		return $line;
	}
	public static function Messages($end,$bot){
		$line = Line::get($bot);
		if(!is_array($end)){
			$reply = $line->replyText($bot->replyToken,$end);
		}
		else {
			$to = ($bot->source->type == "user")?$bot->source->userId:$bot->source->groupId;
			$num = count($end['data']);
			for ($i=0; $i < $num; $i++) { 
				$imageUrl = 'https://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_Placeholder.svg/800px-Flag_of_Placeholder.svg.png';
            		$buttonTemplateBuilder = new ButtonTemplateBuilder(
                		$end['title'],
                		$end['desc'],
                		$imageUrl,
                		$end['data'][$i]
            		);
            	$templateMessage = new TemplateMessageBuilder('List Command', $buttonTemplateBuilder);
            	$reply = $line->pushMessage($to,$templateMessage);
			}
		}
		var_dump($reply->getRawBody());
		return json_decode($reply->getRawBody());
	}
	public static function getProfile($bot){
		$line = Line::get($bot);
		$reply = $line->getProfile($bot->source->userId);
		return json_decode($reply->getRawBody());
	}
	public static function getRoomMemberId($bot){
		$line = Line::get($bot);
		if($bot->source->type != "group"){
			return false;
		}
		$reply =  $line->getAllRoomMemberIds($bot->source->groupId);
		return json_decode($reply->getRawBody());
	}
}