<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface
{
    public function getAll(): array;
    public function getById(int $id): array;
    public function getByUniqueKey(array $product): array;
    public function create(array $product): array;
}

