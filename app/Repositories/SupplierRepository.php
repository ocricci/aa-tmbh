<?php

namespace App\Repositories;

use App\Repositories\Interfaces\SupplierRepositoryInterface;

class SupplierRepository extends AbstractRepository implements SupplierRepositoryInterface
{
    public function getAll(): array
    {
        return $this->fetchAsArray("SELECT * FROM suppliers;");
    }

    public function getById(int $id): array
    {
        $supplier = $this->fetchAsArray(
            "SELECT * FROM suppliers WHERE id = :id;",
            [
                [':id', $id, SQLITE3_INTEGER],
            ]
        );

        return $supplier[0] ?? [];
    }
}

