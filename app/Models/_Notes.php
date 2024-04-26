<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class _Notes extends Model
{
    public static function getNotes($data){
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_notes')->where('patients_id', $data['patient_id'])->where('doctors_id', $data['user_id'])->where('status', 1)->orderBy('id', 'desc')->get();
    }

    public static function newNotes($data){
        date_default_timezone_set('Asia/Manila');

        $diagnosis = implode(', ', $data['diagnosis']);
        
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_notes')->insert([
            'notes_id' => 'notes-'.rand(0, 9999),
            'patients_id' => $data['patient_id'],
            'doctors_id' => $data['user_id'],
            'initial_diagnosis' => $diagnosis,
            'notes' => $data['diagnosis_notes'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function editNotes($data){
        date_default_timezone_set('Asia/Manila');

        $diagnosis = implode(', ', $data['diagnosis']);
        
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_notes')->where('notes_id', $data['notes_id'])->update([
            'initial_diagnosis' => $diagnosis,
            'notes' => $data['diagnosis_notes'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function deleteNotes($data){
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_notes')->where('notes_id', $data['notes_id'])->update([
            'status' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function newNotesCanvas($data, $filename){ 

        date_default_timezone_set('Asia/Manila'); 

        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_notes_canvas')->insert([
            'dnc_id' => 'dnc-'.rand(0, 9999),
            'patient_id' => $data['patient_id'],
            'doctors_id' => $data['user_id'],
            'canvas' => $filename,
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getCanvasNotesList($data){
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_notes_canvas')
            ->where('doctors_id', $data['user_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('status' ,1)
            ->orderBy('id', 'desc')
            ->get();
    }

}
