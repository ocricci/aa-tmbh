<?php

namespace App\Repositories\Interfaces;

interface SupplierRepositoryInterface
{
    public function getAll(): array;
    public function getById(int $id): array;
}

