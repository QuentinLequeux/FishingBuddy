<?php

namespace App\Enums;

enum Privacy: string
{
    case Public = 'public';

    case Follow = 'followers';

    case Private = 'private';
}
