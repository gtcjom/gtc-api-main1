<?php

namespace App\Enums;

enum ImagingStatusEnum: string
{
    case Success = 'success';
    case Pending = 'pending';
    case Failed = 'failed';
}