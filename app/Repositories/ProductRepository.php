<?php

namespace App\Repositories;

class ProductRepository extends AbstractRepository
{
    public function getAll(): array
    {
        return $this->fetchAsArray("SELECT * FROM products;");
    }

    public function getById(int $id): array
    {
        return $this->fetchAsArray(
            "SELECT * FROM products WHERE id = :id;",
            [
                [':id', $id, SQLITE3_INTEGER],
            ]
        );
    }
}

