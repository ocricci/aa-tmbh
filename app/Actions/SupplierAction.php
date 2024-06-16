<?php

namespace App\Actions;

use App\Repositories\SupplierRepository;

class SupplierAction
{
    public function getAll()
    {
        $supplierRepository = new SupplierRepository();
        return $supplierRepository->getAll();
    }

    public function get($id)
    {

    }
}

