<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\Purok;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlabelUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $municipality = Municipality::query()
            ->with(['barangays' => [
            'puroks'
        ]])->findOrFail(1);

        $users = [];

        $users[] = [
            'user_id' => 'u-' . rand(0, 8888) . time(),
            'username' =>  $municipality->name,
            'type' => "MUNICIPAL-HO",
            'municipality' => $municipality->id,
            'email' => null,
            'manage_by' => 'm-81632116452',
            'is_confirm' => 1,
            'is_verify' => 1,
            'main_mgmt_id' => 'general-magement-09202021',
            'password' => bcrypt('password'),
            'status' => 1,
            'name' => "{$municipality->name}",
            'barangay' => null,
            'purok' => null
        ];

        foreach ($municipality->barangays as $barangay){
            $ba = str_replace(' ','-',$barangay->name);
            $users[] = [
                'user_id' => 'u-' . rand(0, 8888) . time(),
                'username' =>  "{$municipality->name}-{$ba}",
                'type' => "BARANGAY-HO",
                'municipality' => $municipality->id,
                'email' => null,
                'manage_by' => 'm-81632116452',
                'is_confirm' => 1,
                'is_verify' => 1,
                'main_mgmt_id' => 'general-magement-09202021',
                'password' => bcrypt('password'),
                'status' => 1,
                'name' => "{$municipality->name} {$barangay->name}",
                'barangay' => $barangay->id,
                'purok' => null
            ];



            foreach ($barangay->puroks as $purok){
                $number = ltrim($purok->number,0);
                $users[] = [
                    'user_id' => 'u-' . rand(0, 8888) . time(),
                    'username' =>  "{$municipality->name}-{$ba}-P{$number}",
                    'type' => "PUROK-HO",
                    'municipality' => $municipality->id,
                    'email' => null,
                    'manage_by' => 'm-81632116452',
                    'is_confirm' => 1,
                    'is_verify' => 1,
                    'main_mgmt_id' => 'general-magement-09202021',
                    'password' => bcrypt('password'),
                    'status' => 1,
                    'name' => "{$municipality->name} {$barangay->name} Purok {$number}",
                    'barangay' => $barangay->id,
                    'purok' => $purok->number,
                ];
            }
        }

        collect($users)->chunk(50)->map( function ($chunk){
            foreach ($chunk as $users){
                User::query()->insert($users);
            }
        });




    }
}
