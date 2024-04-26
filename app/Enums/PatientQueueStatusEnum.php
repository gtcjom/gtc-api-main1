<?php

namespace App\Enums;

enum PatientQueueStatusEnum: string
{
	case Pending = 'pending';
    case Served = 'served';
    case Done = 'done';
    case Cancelled = 'cancelled';
}