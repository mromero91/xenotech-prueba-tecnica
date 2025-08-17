<?php

namespace App\Enums;

enum CustomerType: string
{
    case REGULAR = 'regular';
    case PREMIUM = 'premium';
    case VIP = 'vip';
}
