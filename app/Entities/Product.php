<?php

namespace App\Entities;

class Product
{
    public static array $required = [
        "species" => ['pine', 'spruce', 'fir', 'birch', 'apple_tree'],
        "grading_system" => ['nordic_blue', 'tegernseer'],
        "grade" => "string",
        "drying" => ['fresh', 'kiln_dried', 'air_dried'],
        "treatment" => ['heat', 'anti_strain', 'null'], // optional
        "thickness" => "int",
        "width" => "int",
        "length" => "int",
    ];

    public static array $unique = [
        "species" => SQLITE3_TEXT,
        "grading_system" => SQLITE3_TEXT,
        "grade" => SQLITE3_TEXT,
        "drying" => SQLITE3_TEXT,
        "treatment" => SQLITE3_TEXT,
        "thickness" => SQLITE3_INTEGER,
        "width" => SQLITE3_INTEGER,
        "length" => SQLITE3_INTEGER,
    ];
}

