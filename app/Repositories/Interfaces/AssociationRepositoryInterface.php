<?php

namespace App\Repositories\Interfaces;

interface AssociationRepositoryInterface
{
    public function getAll(int $id): array;
    public function getById(int $sid, int $pid): array;
    public function associate(int $sid, int $pid, array $assoc): array;
}

