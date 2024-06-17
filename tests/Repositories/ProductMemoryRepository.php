<?php

namespace App\Test\Repositories;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\RepositoryInterface;

class ProductMemoryRepository implements RepositoryInterface, ProductRepositoryInterface
{
    protected array $data = [
        [
            "id" => 1,
            "species" => "pine",
            "grading_system" => "nordic_blue",
            "grade" => "a1",
            "drying" => "fresh",
            "treatment" => "null",
            "thickness" => 50,
            "width" => 120,
            "length" => 1200
        ],
        [
            "id" => 2,
            "species" => "pine",
            "grading_system" => "nordic_blue",
            "grade" => "a12",
            "drying" => "fresh",
            "treatment" => "null",
            "thickness" => 50,
            "width" => 120,
            "length" => 1200
        ]

    ];

    public function getAll(): array
    {
        return $this->data;
    }

    public function getById(int $id): array
    {
        if (in_array($id, [1,2])) {
            return $this->data[$id - 1];
        }

        return [];
    }

    public function getByUniqueKey(array $product): array
    {
        foreach ($this->data as $storedProduct) {
            foreach (array_keys($this->data[0]) as $field) {
                if ($field == "id") {
                    continue;
                }

                if (($product[$field] ?? '') != ($storedProduct[$field] ?? '')) {
                    continue 2;
                } 
            }

            return $storedProduct;
        }

        return [];
    }

    public function create(array $product): array
    {
        $id = count($this->data) + 1;
        $this->data[] = $product + ['id' => $id];
        return $this->data[count($this->data) - 1];
    }
}

