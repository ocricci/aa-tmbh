<?php

namespace App\Actions;

use App\Actions\Validators\ProductValidator;
use App\Entities\Product;

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

    public function create(array $product): array
    {
        $registered = $this->repository->getByUniqueKey($product);
        if (!empty($registered['id'] ?? '')) {
            return $registered;
        }

        return $this->repository->create(
            $this->sanitize($product)
        );
    }

    public function sanitize(array $product): array
    {
        $diff = array_diff_key($product, Product::$unique);
        if (0 == count($diff)) {
            return $product;
        }

        return array_diff_key($product, $diff);
    }
}

