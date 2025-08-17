<?php

namespace App\Enums;

enum UserRoleType: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}