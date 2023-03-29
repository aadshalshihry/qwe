<?php
namespace App\Enum;


enum ProductStatusEnum:string
{
    case SALE = "sale";
    case HIDDEN = "hidden";
    case DELETED = "deleted";
    case OUT = "out";
}