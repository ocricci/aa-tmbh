<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Entities\Product;

class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    public function getAll(): array
    {
        return $this->fetchAsArray("SELECT * FROM products;");
    }

    public function getById(int $id): array
    {
        $product = $this->fetchAsArray(
            "SELECT * FROM products WHERE id = :id;",
            [
                [':id', $id, SQLITE3_INTEGER],
            ]
        );

        return $product[0] ?? [];
    }

    public function getByUniqueKey(array $product): array
    {
        $query = "SELECT * FROM products WHERE";
        $args = [];
        foreach (Product::$unique as $field => $type) {
            $query .= " {$field} = :{$field} ";
            $args[] = [":{$field}", $product[$field], $type];
            if (count($args) != count(Product::$unique)) {
                $query .= " AND ";
            }
        }

        $registered = $this->fetchAsArray($query, $args);
        return $registered[0] ?? [];
    }

    public function create(array $product): array
    {
        $query = "INSERT INTO products(";
        $query .= implode(',', array_keys($product));
        $query .= ") VALUES (";
        $values = array_map(
            function ($value) {
                if (is_null($value)) {
                    return 'NULL';
                }

                if (is_int($value)) {
                    return $value;
                }

                return "\"{$value}\"";
            },
            array_values($product)
        );

        $query .= implode(',', $values);
        $query .= ");";

        $SQLite3 = $this->getSQLite3();
        if (!$SQLite3->exec($query)) {
            throw new \RuntimeException('Database insert failed');
        }

        return $this->getByUniqueKey($product);
    }
}

