<?php
namespace App\Controllers;
/**
* 
*/
use App\Helpers\Crayner_Machine;
use Config\Config;

use App\Main\LineMessage;
use App\Main\LineSticker;
use App\Main\LineGroup;
class Home
{
	public function index(){
		$input = self::input();
		if(!isset($_GET['curl'])){
			self::Curl();
			die();
		}
		foreach($input as $obj){
			if(isset($obj->type)){
				if($obj->type == "join"){
					LineGroup::Join($obj);
				}
				else if($obj->type == "message"){
					switch ($obj->message->type) {
						case 'text':
							LineMessage::inputData($obj);
						break;
						case 'sticker':
							LineSticker::inputData($obj);
						break;
					}
				}
			}
		}	
	}
	public static function input(){
		$input = file_get_contents("php://input");
		logs("input",$input);
		return json_decode($input)->events;
	}
	public static function Curl(){
		$hooks = Config::line('hooks')."?curl";
		$cm = Crayner_Machine::qurl($hooks,'',file_get_contents('php://input'));
		logs("output",$cm);
	}
}