<?php

namespace App\Repositories;

use App\Repositories\Interfaces\AssociationRepositoryInterface;

class AssociationRepository extends AbstractRepository implements AssociationRepositoryInterface
{
    public function getAll(int $sid): array
    {
        return $this->fetchAsArray(
            <<<SQL
            SELECT
                shp.stock,
                shp.price,
                p.*
            FROM supplier_has_product shp JOIN products p ON shp.product_id = p.id
            WHERE shp.supplier_id = :sid
SQL,
            [
                [':sid', $sid, SQLITE3_INTEGER],
            ]
        );
    }

    public function getById(int $sid, int $pid): array
    {
        $supplierProduct = $this->fetchAsArray(
            <<<SQL
            SELECT
                shp.stock,
                shp.price,
                p.*
            FROM supplier_has_product shp JOIN products p ON shp.product_id = p.id
            WHERE shp.supplier_id = :sid AND shp.product_id = :pid
SQL,
            [
                [':sid', $sid, SQLITE3_INTEGER],
                [':pid', $pid, SQLITE3_INTEGER],
            ]
        );

        return $supplierProduct[0] ?? [];
    }

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

        return $this->getById($sid, $pid);
    }
}

