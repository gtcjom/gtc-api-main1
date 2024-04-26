<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class _DTRLogs extends Model
{
    public static function getInsertInLogs($data){
        return DB::table('hospital_dtr_logs')->insert([
            'dtr_id' => 'dtr-'.rand(0, 99).time(),
            'user_id' => $data['user_id'],
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'timein' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function getInsertOutLogs($data){
        return DB::table('hospital_dtr_logs')
        ->where('user_id', $data['user_id'])
        ->where('management_id', $data['management_id'])
        ->whereNotNull('timein')
        ->update([
            'timeout' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

}
