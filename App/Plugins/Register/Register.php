<?php
namespace App\Plugins\Register;
/**
 *
 */
use App\Helpers\DB;
use App\Main\Line;
class Register{
    public static function list($bot){
        $query = DB::prepare('SELECT * FROM users_data WHERE group_id=:group');
        $query->execute([':group'=>$bot->source->groupId]);
        if($query->rowCount() == 0) {
            return "Tidak ada data yang tersedia";
        }
        $data = $query->fetchAll(5);
        $str = '';
        foreach($data as $key){
            $str .= 'Data '.$key->username."\n";
            foreach(json_decode($key->user_data) as $keys=>$vals){
                $key = str_replace('name','Nama',$keys);
                $key = str_replace('from','Asal',$keys);
                $str .= ucfirst($keys)." : ".$vals."\n";
            }
            $str .= "\n";
        }
        return $str;
    }
    public static function set(array $data,$bot){
        $data = json_encode($data);

        $profile = Line::getProfile($bot);
        $group = (($bot->source->type == "user"))?'':$bot->source->groupId;
        $query = DB::prepare('SELECT * FROM users_data WHERE user_id=:id AND group_id=:group');
        $query->execute([':id'=>$profile->userId,':group'=>$bot->source->groupId]);
        if($query->rowCount() != 0){
            return 'Mohon maaf data kamu sudah tersedia didalam database kami.';
        }

        $sql = "INSERT INTO users_data(user_id, username, group_id, user_data, created_at, updated_at)
                VALUES (:id,:user,:group,:data,:created,:updated)";
        $query = DB::prepare($sql);
        $query->execute(
            [
                ':id'=>$profile->userId,
                ':user'=>strtolower($profile->displayName),
                ':group'=>$bot->source->groupId,
                ':data'=>$data,
                ':created'=>date('Y-m-d H:i:s'),
                ':updated'=>date('Y-m-d H:i:s')
            ]
        );
        return false;
    }
}
