<?php
namespace App\Enum;


enum StatusEnum:int
{
    case PENDING = 0;
    case DRAFT = 1;
    case PUBLISHED = 2;
}