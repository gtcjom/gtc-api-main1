<?php

namespace App\Enums;

enum ClinicReferralStatusEnum: string
{
    case Pending = 'pending';
    case Served = 'served';
    case Done = 'done';
	case Cancelled = 'cancelled';
}