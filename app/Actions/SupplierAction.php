<?php

namespace App\Actions;

use App\Repositories\SupplierRepository;

class SupplierAction
{
    public function getAll(): array
    {
        $supplierRepository = new SupplierRepository();
        return $supplierRepository->getAll();
    }

    public function getById(int $id): array
    {
        $supplierRepository = new SupplierRepository();
        return $supplierRepository->getById($id);
    }
}

