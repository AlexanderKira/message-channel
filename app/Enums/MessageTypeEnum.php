<?php

namespace App\Enums;

enum MessageTypeEnum: string
{
    case PRIVATE = 'private';
    case AUTHORIZED = 'authorized';
    case ALL = 'all';

}
