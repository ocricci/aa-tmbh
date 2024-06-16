<?php

namespace App\Actions;

use App\Repositories\BootRepository;

class BootAction
{
    public function boot(): void
    {
        $bootRepository = new BootRepository();
        $bootRepository->migrate();
        $bootRepository->seed();
    }
}

