<?php

namespace App\Actions;

use App\Actions\Validators\ProductValidator;

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

    public function validate(array $product = []): ProductValidator
    {
        $validator = new ProductValidator();
        $validator->validate($product);
        return $validator;
    }
}

