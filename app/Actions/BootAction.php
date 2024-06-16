<?php

namespace App\Actions;

use App\Repositories\BootRepository;

class BootAction
{
    public function boot(): void
    {
        $this->handlesDatabaseFile();
        $bootRepository = new BootRepository();
        $bootRepository->migrate();
        $bootRepository->seed();
    }

    protected function handlesDatabaseFile(): void
    {
        $databasePath = $_SERVER['DOCUMENT_ROOT'] . '/..' . BootRepository::DATABASE_PATH;
        $file = $databasePath . BootRepository::DATABASE_FILE;
        if (file_exists($file)) {
            return;
        }

        if (!is_dir($databasePath)) {
            mkdir($databasePath, 0777);
        }

        if (!is_file($file)) {
            file_put_contents($file, '');
        }
    }
}

