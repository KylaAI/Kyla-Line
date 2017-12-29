<?php
namespace App\Main;
/**
* 
*/
use App\Plugins\Register\Register;
use App\Plugins\Check\CheckMember;
use App\Plugins\Jadwal\Jadwal;
use App\Plugins\Note\Note;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;


use ChrisKonnertz\StringCalc\StringCalc;
class CommandList
{
	private static $method = [];
	private static $bot;

	public function __construct($bot){
		self::$bot = $bot;
        $class = new \ReflectionClass('App\Main\CommandList');
        $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $key) {
            $method[] = strtolower($key->name);
        }
        $method = array_slice($method, 2);
        self::$method = $method;
	}
	public static function getCommand(){
		return self::$method;
	}

	public static function test($bot){
		return "test test 123";
	}

	public static function thanks(){
        return "Terima kasih";
    }
    public static function register($bot){
    	$messages = self::getMessages($bot);
    	if(empty($messages)){
    		return "Gunakan command sebagai berikut\nregister @akun=namaakun\nGunakan tanda ; untuk pemisah jika lebih dari 1 akun.";
    	}
    	$user_data = explode(';',$messages);
    	$Akun = [];
        for ($i=0; $i < count($user_data); $i++) {
            $data = explode('=',$user_data[$i]);
            $key = str_replace('@','',$data[0]);
            $Akun = array_merge([$key=>$data[1]],$Akun);
        }
        $register = Register::set($Akun,$bot);
        if($register){
        	return $register;
        }
        return "Data Kamu sudah kami proses";
    }
    public static function memberlist($bot){
    	return Register::list($bot);
    }
    public static function whois($bot){
    	$messages = strtolower(self::getMessages($bot));
    	if(empty($messages)){
    		return "Gunakan command berikut untuk melihat info seseorang.\nwhois {nama orang}";
    	}
    	return CheckMember::set($messages,$bot);
    }
    public static function jadwal($bot){
    	$messages = self::getMessages($bot);
    	if(empty($messages)){
    		$check = Jadwal::list($bot);
    		if(!$check)
    			return "Jadwal kamu belum tersedia. Gunakan command berikut untuk menambahkan jadwal.\n\njadwal @hari=pelajaran1,pelajaran2;@hari2=pelajaran1,pelajaran2\n\nGunakan ; untuk pemisah hari dan ganti @hari sesuai dengan nama hari serta pelajaran sesuai dengan nama pelajaran";
    		return $check;
    	}
    	if($messages == "revoke"){
    		return Jadwal::revoke($bot);
    	}
    	$user_data = explode(';',$messages);
        $s = [];
        for ($i=0; $i < count($user_data); $i++) {
            $data = explode('=',$user_data[$i]);
            $key = str_replace('@','',$data[0]);
            $s = array_merge([strtolower($key)=>strtolower($data[1])],$s);
        }
        $jadwal = Jadwal::set($s,$bot);
        if($jadwal){
            return  $jadwal;
        }
        return 'Jadwal kamu sudah di proses';
    }

    public static function note($bot){
    	$messages = self::getMessages($bot);
    	if(empty($messages)){
    		return "Gunakan comand dibawah ini:\n\nnote add *judul* [p]isi[/p] => Untuk membuat note baru\n\nnote list => Untuk melihat list note\n\nnote read [id_note] => untuk membaca note\n\nnote delete [id_note] => Untuk menghapus note";
    	}
    	$cmd = explode(' ',$messages)[0];
    	if(preg_match('/add/',$cmd)){
            return Note::set($messages,$bot);
        }
        else if(preg_match('/list/',$cmd)){
            return Note::list($bot);
        }
        else if(preg_match('/read/',$cmd)){
            return Note::get($messages,$bot);
        }
        else if(preg_match('/delete/',$cmd)){
            return Note::revoke($messages,$bot);
        }
        else {
            var_dump(preg_match('/read/', $query));
            return "Command tidak ditemukan.";
        }
    }

    public static function hitung($bot){
    	$messages = self::getMessages($bot);
    	if(empty($messages)){
    		return "Gunakan comamnd dibawah ini: \n\nhitung [perhitungan]";
    	}
    	$str = new stringCalc();
    	return "Hasil dari ".$messages." adalah ".$str->calculate($messages);
    }
    public static function commandlist(){
        unset(self::$method[0]);
        unset(self::$method[1]);
        $img = [];
        $i = 0;
        $a = 0;
        foreach(self::$method as $key => $value){
            if(($value != "__construct")||($value != "getCommand")){
                $img[$a][$i]  = new MessageTemplateActionBuilder(ucfirst($value), $value);
                if($i == 3){
                    $a++;
                    $i=0;
                }
                else {
                    $i++;
                }
            }
        }
        return [
            'desc'=>'Berikut daftar command yang tersedia',
            'title'=>'Command List',
            'data'=>$img
        ];
    }



    private static function getMessages($bot){
    	$msg = explode(' ',$bot->message->text);
    	$msg = implode(' ',array_slice($msg, 1));
    	return $msg;
    }
}