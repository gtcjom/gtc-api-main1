<?php

namespace App\Enums;

enum PatientQueuePriorityEnum: int
{
	case Priority = 1;
    case Regular = 0;
}