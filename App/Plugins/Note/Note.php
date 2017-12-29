<?php
namespace App\Plugins\Note;
/**
* 
*/
use App\Helpers\DB;
class Note
{
	public static function set($data,$bot){
		$data = explode("add ",$data)[1];
		preg_match('~\*(.*)\*~', $data,$judul);
		$judul = $judul[1];
		$isi = explode('[p]',$data);
		$isi = explode('[/p]',$isi[1])[0];
		$sql = "INSERT INTO note(user_id, judul, paragraph, created_at, updated_at) VALUES (:user,:judul,:texts,:created,:updated)";
		$query = DB::prepare($sql);
		$query->execute(
			[
				":user"=>$bot->source->userId,
				":judul"=>$judul,
				":texts"=>$isi,
				"created"=>date("Y-m-d H:i:s"),
				"updated"=>date("Y-m-d H:i:s")
			]
		);
		return "silahkan ketik note read ".DB::lastInsertId()." untuk melihat note yang barusan kamu inputkan";
	}
	public static function get($data,$bot){
		$data = explode("read ",$data)[1];
		$sql = "SELECT * FROM note WHERE id_note=:id AND user_id=:user_id";
		$query = DB::prepare($sql);
		$query->execute([':id'=>$data,":user_id"=>$bot->source->userId]);
		if($query->rowCount() == 0){
			return "Tidak ada catatan dengan id ini";
		}
		$data = $query->fetch(5);
		$str = ucfirst($data->judul)."\n\n";
		$str .= $data->paragraph."\n";
		return $str;
	}
	public static function list($bot){
		$sql = "SELECT * FROM note WHERE user_id=:id";
		$query = DB::prepare($sql);
		$query->execute([':id'=>$bot->source->userId]);
		if($query->rowCount() == 0){
			return "Kamu tidak memiliki catatan apapun";
		}
		$data = $query->fetchAll(5);
		$str = "Berikut ini adalah note yang tersedia.\n\n";
		foreach($data as $key){
			$str .= "[".$key->id_note."] ".ucfirst($key->judul)."\n";
		}
		$str .= "\n\nGunakan note read [id note] untuk melihat isi note.";
		return $str;
	}
	public static function revoke($data,$bot){
		$data = explode("delete ",$data)[1];
		$sql = "SELECT * FROM note WHERE id_note=:id AND user_id=:user_id";
		$query = DB::prepare($sql);
		$query->execute([':id'=>$data,":user_id"=>$bot->source->userId]);
		if($query->rowCount() == 0){
			return "Anda tidak memiliki catatan dengan id ini";
		}
		$sql = "DELETE FROM note WHERE id_note=:id AND user_id=:user_id";
		$query = DB::prepare($sql);
		$query->execute([':id'=>$data,":user_id"=>$bot->source->userId]);
		return "Note berhasil di hapus";
	}
}