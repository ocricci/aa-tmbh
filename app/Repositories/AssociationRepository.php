<?php

namespace App\Repositories;

class AssociationRepository extends AbstractRepository
{
    public function associate(int $sid, int $pid, array $assoc): array
    {
        $query = "INSERT INTO supplier_has_product(supplier_id, product_id, stock, price) ";
        $query .= "VALUES({$sid}, {$pid}, {$assoc['stock']}, {$assoc['price']}) ";
        $query .= "ON CONFLICT (supplier_id, product_id) DO ";
        $query .= "UPDATE SET stock = {$assoc['stock']}, price = {$assoc['price']};";
        $SQLite3 = $this->getSQLite3();
        if (!$SQLite3->exec($query)) {
            throw new \RuntimeException('Database insert failed');
        }

        return [];
    }
}

