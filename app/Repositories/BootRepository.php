<?php

namespace App\Repositories;

class BootRepository extends AbstractRepository
{
    public function migrate(): bool
    {
        $SQLite3 = $this->getSQLite3();
        $SQLite3->exec("CREATE TABLE IF NOT EXISTS suppliers(id INTEGER PRIMARY KEY ASC, name VARCHAR(255))");
        return true;
    }

    public function seed(): bool
    {
        $this->seedSuppliers();
        return true;
    }

    protected function seedSuppliers()
    {
        $SQLite3 = $this->getSQLite3();

        $suppliers = [
            1 => 'Supplier I',
            2 => 'Supplier II',
            3 => 'Supplier III',
            4 => 'Supplier IV',
            5 => 'Supplier V',
        ];

        foreach ($suppliers as $id => $name) {
            $SQLite3->exec(
                "INSERT INTO suppliers(id, name) VALUES ({$id}, '{$name}') ON CONFLICT (id) DO NOTHING;"
            );
        }
    }
}

