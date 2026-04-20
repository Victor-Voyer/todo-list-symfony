<?php

namespace App\Enum;

enum ValueType: string
{
    case TEXT = 'text';
    case NUMBER = 'number';
    case DATE = 'date';
    case TIME = 'time';
}