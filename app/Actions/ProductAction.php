<?php

namespace App\Actions;

use App\Repositories\ProductRepository;

class ProductAction extends AbstractAction
{
    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): array
    {
        return $this->repository->getById($id);
    }
}

