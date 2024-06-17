<?php

namespace App\Test\Repositories;

use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Repositories\RepositoryInterface;

class SupplierMemoryRepository implements RepositoryInterface, SupplierRepositoryInterface
{
    protected array $data = [
        ['id' => 1, 'name' => 'Memory Supplier'],
        ['id' => 2, 'name' => 'Memory Supplier II'],
    ];

    public function getAll(): array
    {
        return $this->data;
    }

    public function getById(int $id): array
    {
        if (in_array((int) $id, [1,2])) {
            return $this->data[$id - 1];
        }

        return [];
    }
}

