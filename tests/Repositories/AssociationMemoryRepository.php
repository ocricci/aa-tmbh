<?php

namespace App\Test\Repositories;

use App\Repositories\Interfaces\AssociationRepositoryInterface;
use App\Repositories\RepositoryInterface;

class AssociationMemoryRepository implements RepositoryInterface, AssociationRepositoryInterface
{
    protected array $data = [
        1 => ['stock' => 10, 'price' => 29],
        2 => ['stock' => 12, 'price' => 39],
    ];

    public function getAll(int $id): array
    {
        $products = (new ProductMemoryRepository())->getAll();
        $response = [];
        foreach ($products as $product) {
            $response[] = $this->data[$product['id']] + $product;
        }

        return $response;
    }

    public function getById(int $sid, int $pid): array
    {
        $product = (new ProductMemoryRepository())->getById($pid);
        if (empty($product['id'])) {
            return [];
        }

        return $this->data[$product['id']] + $product;
    }

    public function associate(int $sid, int $pid, array $assoc): array
    {
        $this->data[$pid] = $assoc;
        return $this->getById($sid, $pid);
    }
}

