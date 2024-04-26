<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TimeSlot::query()->truncate();
        $time_slots = [
                [
                    'start_time' => '08:00AM',
                    'end_time' => '08:30AM',
                    'duration' => 30,
                    'rank' => 1

                ],
                [
                    'start_time' => '08:30AM',
                    'end_time' => '09:00AM',
                    'duration' => 30,
                    'rank' => 2

                ],
                [
                    'start_time' => '09:00AM',
                    'end_time' => '09:30AM',
                    'duration' => 30,
                    'rank' => 3

                ],
                [
                    'start_time' => '09:30AM',
                    'end_time' => '10:00AM',
                    'duration' => 30,
                    'rank' => 4

                ],
                [
                    'start_time' => '10:00AM',
                    'end_time' => '10:30AM',
                    'duration' => 30,
                    'rank' => 5

                ],
                [
                    'start_time' => '10:30AM',
                    'end_time' => '11:00AM',
                    'duration' => 30,
                    'rank' => 6

                ],
                [
                    'start_time' => '11:00AM',
                    'end_time' => '11:30AM',
                    'duration' => 30,
                    'rank' => 7

                ],
                [
                    'start_time' => '11:30AM',
                    'end_time' => '12:00PM',
                    'duration' => 30,
                    'rank' => 8

                ],
                [
                    'start_time' => '12:00PM',
                    'end_time' => '12:30PM',
                    'duration' => 30,
                    'rank' => 9

                ],
                [
                    'start_time' => '12:30PM',
                    'end_time' => '01:00PM',
                    'duration' => 30,
                    'rank' => 10

                ],
                [
                    'start_time' => '01:00PM',
                    'end_time' => '01:30PM',
                    'duration' => 30,
                    'rank' => 11

                ],
                [
                    'start_time' => '01:30PM',
                    'end_time' => '02:00PM',
                    'duration' => 30,
                    'rank' => 12

                ],
                [
                    'start_time' => '02:00PM',
                    'end_time' => '02:30PM',
                    'duration' => 30,
                    'rank' => 13
                ],
                [
                    'start_time' => '02:30PM',
                    'end_time' => '03:00PM',
                    'duration' => 30,
                    'rank' => 14
                ],
                [
                    'start_time' => '03:00PM',
                    'end_time' => '03:30PM',
                    'duration' => 30,
                    'rank' => 15
                ],
                [
                    'start_time' => '03:30PM',
                    'end_time' => '04:00PM',
                    'duration' => 30,
                    'rank' => 16
                ],
                [
                    'start_time' => '04:00PM',
                    'end_time' => '04:30PM',
                    'duration' => 30,
                    'rank' => 17
                ],
                [
                    'start_time' => '04:30PM',
                    'end_time' => '05:00PM',
                    'duration' => 30,
                    'rank' => 18
                ],
                [
                    'start_time' => '05:00PM',
                    'end_time' => '05:30PM',
                    'duration' => 30,
                    'rank' => 19
                ],
                [
                    'start_time' => '05:30PM',
                    'end_time' => '06:00PM',
                    'duration' => 30,
                    'rank' => 20
                ],
                [
                    'start_time' => '06:00PM',
                    'end_time' => '06:30PM',
                    'duration' => 30,
                    'rank' => 21
                ],
                [
                    'start_time' => '06:30PM',
                    'end_time' => '07:00PM',
                    'duration' => 30,
                    'rank' => 22
                ],
                [
                    'start_time' => '07:00PM',
                    'end_time' => '07:30PM',
                    'duration' => 30,
                    'rank' => 23
                ],
                [
                    'start_time' => '07:30PM',
                    'end_time' => '08:00PM',
                    'duration' => 30,
                    'rank' => 24
                ],
                [
                    'start_time' => '08:00PM',
                    'end_time' => '08:30PM',
                    'duration' => 30,
                    'rank' => 25
                ],
                [
                    'start_time' => '08:30PM',
                    'end_time' => '09:00PM',
                    'duration' => 30,
                    'rank' => 26
                ],
                [
                    'start_time' => '09:00PM',
                    'end_time' => '09:30PM',
                    'duration' => 30,
                    'rank' => 27
                ],
                [
                    'start_time' => '09:30PM',
                    'end_time' => '10:00PM',
                    'duration' => 30,
                    'rank' => 28
                ],
                [
                    'start_time' => '10:00PM',
                    'end_time' => '10:30PM',
                    'duration' => 30,
                    'rank' => 29
                ],
                [
                    'start_time' => '10:30PM',
                    'end_time' => '11:00PM',
                    'duration' => 30,
                    'rank' => 30
                ],
                [
                    'start_time' => '11:00PM',
                    'end_time' => '11:30PM',
                    'duration' => 30,
                    'rank' => 31
                ],
                [
                    'start_time' => '11:30PM',
                    'end_time' => '12:00AM',
                    'duration' => 30,
                    'rank' => 32
                ],

            ];

        TimeSlot::query()->insert($time_slots);

    }
}
