<?php

namespace App\Actions;

use App\Repositories\RepositoryInterface;

abstract class AbstractAction
{
    public function __construct(
        protected RepositoryInterface $repository
    ) {
    }
}

