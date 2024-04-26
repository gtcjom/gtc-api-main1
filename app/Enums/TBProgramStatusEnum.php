<?php

namespace App\Enums;

enum TBProgramStatusEnum: string
{
    case Treated = 'treated';
    case Closed = 'closed';
    case Untreated = 'untreated';
}