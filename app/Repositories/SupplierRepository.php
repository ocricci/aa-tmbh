<?php

namespace App\Repositories;

class SupplierRepository extends AbstractRepository
{
    public function getAll(): array
    {
        return $this->fetchAsArray("SELECT * FROM suppliers;");
    }

    public function getById(int $id): array
    {
        return $this->fetchAsArray(
            "SELECT * FROM suppliers WHERE id = :id;",
            [
                [':id', $id, SQLITE3_INTEGER],
            ]
        );
    }
}

