<?php

namespace App\Enums;

enum CitizenshipEnum: string
{
    case Filipino = 'fil';
    case DualCitizenship = 'dual';
    case Foreign = 'foreign';
}