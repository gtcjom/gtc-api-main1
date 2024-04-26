<?php

namespace App\Enums;

enum OperatingRoomChartStatusEnum: string
{
    case OR = 'operating_room';
    case Resu = 'resu';
    case Done = 'done';
}