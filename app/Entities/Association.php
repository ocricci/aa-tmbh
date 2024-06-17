<?php

namespace App\Entities;

class Association
{
    public static array $required = [
        "stock" => "int",
        "price" => "num",
    ];

    public static array $unique = [
        "stock" => SQLITE3_INTEGER,
        "price" => SQLITE3_NUM,
    ];
}

