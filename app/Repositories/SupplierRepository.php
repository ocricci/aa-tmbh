<?php

namespace App\Repositories;

class SupplierRepository extends AbstractRepository
{
    public function getAll(): array
    {
        return $this->fetchAsArray("SELECT * FROM suppliers");
    }

    public function getById(string $id): array
    {
        return [$id];
    }
}

