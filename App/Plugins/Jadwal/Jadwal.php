<?php
namespace App\Plugins\Jadwal;
/**
* 
*/
use App\Helpers\DB;
use App\Main\Line;
class Jadwal
{
	// Penghapusan Data
	public static function revoke($bot){
		$query = DB::prepare("SELECT * FROM jadwal WHERE user_id=:id");
		$query->execute([":id"=>$bot->source->userId]);
		var_dump($bot->source->userId);
		if($query->rowCount() == 0){
			return "Anda tidak memiliki data sebelumnya";
		}
		$query  = DB::prepare("DELETE FROM jadwal WHERE user_id=:id");
		$query->execute([':id'=>$bot->source->userId]);
		return "Data anda berhasil di hapus";
	}
	// Get Data
	public static function list($bot){
		$profile = Line::getProfile($bot);

		$query = DB::prepare("SELECT * FROM jadwal WHERE user_id=:id");
		$query->execute([":id"=>$bot->source->userId]);
		if($query->rowCount() == 0){
			return false;
		}
		$data = $query->fetch(5);
		$user_data = json_decode($data->user_data);
		$jadwal['senin'] = (isset($user_data->senin))?$user_data->senin:null;
		$jadwal['selasa'] = (isset($user_data->selasa))?$user_data->selasa:null;
		$jadwal['rabu'] = (isset($user_data->rabu))?$user_data->rabu:null;
		$jadwal['kamis'] = (isset($user_data->kamis))?$user_data->kamis:null;
		$jadwal['jumat'] = (isset($user_data->jumat))?$user_data->jumat:null;
		$jadwal['sabtu'] = (isset($user_data->sabtu))?$user_data->sabtu:null;
		$jadwal['minggu'] = (isset($user_data->minggu))?$user_data->minggu:null;
		$str = "Jadwal ".$profile->displayName."\n\n";
		foreach($jadwal as $key =>$value){
			if(isset($jadwal[$key])):
				$str .= "".ucfirst($key)."\n";
				$jad = explode(',',$jadwal[$key]);
				foreach($jad as $keys){
					$str .= '=>'.ucfirst($keys)."\n";
				}
				$str .= "\n";
			endif;
		}
		$str .= "\nUntuk mengahapus jadwal yang sudah ada, gunakan command seperti berikut.\nJadwal revoke";
		return $str;
	}
	//Set Data
	public static function set($data,$bot)
	{
		$data = json_encode($data);
		$sql = "SELECT * FROM jadwal WHERE user_id=:id";
		$query = DB::prepare($sql);
		$query->execute([":id"=>$bot->source->userId]);
		if($query->rowCount() != 0){
			return "Mohon maaf data jadwal anda sudah tersedia di dalam database kami";
		}
		$sql = "INSERT INTO jadwal(user_id, user_data, created_at, updated_at) VALUES (:id,:data,:created,:updated)";
		$query = DB::prepare($sql);
		$query->execute(
			[
				":id" => $bot->source->userId,
				":data" => $data,
				":created" => date("Y-m-d H:i:s"),
				":updated" => date("Y-m-d H:i:s")
			]
		);
		return false;
	}
}