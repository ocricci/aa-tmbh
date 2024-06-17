<?php

namespace App\Actions;

use App\Actions\Validators\AssociationValidator;
use App\Repositories\RepositoryInterface;

class AssociationAction extends AbstractAction
{
    public function __construct(
        protected RepositoryInterface $repository,
        protected RepositoryInterface $supplierRepository,
        protected RepositoryInterface $productRepository,
    ) {
    }

    public function getAll(int $sid): array
    {
        return $this->repository->getAll($sid);
    }

    public function getById(int $sid, int $pid): array
    {
        return $this->repository->getById($sid, $pid);
    }

    public function validate(int $sid, int $pid, array $assoc = []): AssociationValidator 
    {
        $validator = new AssociationValidator(
            $this->supplierRepository,
            $this->productRepository
        );

        $validator->validate($sid, $pid, $assoc);
        if (false == $validator->isValid()) {
            return $validator;
        }

        return $validator;
    }

    public function associate(int $sid, int $pid, array $assoc = []): array
    {
        return $this->repository->associate($sid, $pid, $assoc);
    }
}

