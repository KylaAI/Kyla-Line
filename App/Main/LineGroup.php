<?php
namespace App\Main;
/**
* 
*/
use App\Main\Line;
class LineGroup
{
	public static function Join($bot){
		Line::Messages("Hallo semuanya...\nPerkenalkan nama saya Kyla, Saya merupakan bot yang akan membantu perkerjaan kalian.\nSalam Kenal :)",$bot);
	}
}