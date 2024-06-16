<?php

namespace App\Repositories;

class BootRepository extends AbstractRepository
{
    public function migrate(): bool
    {
        $SQLite3 = $this->getSQLite3();
        $SQLite3->exec("CREATE TABLE IF NOT EXISTS suppliers(id INTEGER PRIMARY KEY ASC, name VARCHAR(255))");
        $SQLite3->exec(
            <<<SQL
                CREATE TABLE IF NOT EXISTS products(
                    id INTEGER PRIMARY KEY ASC,
                    species TEXT CHECK(species IN ('pine', 'spruce', 'fir', 'birch', 'apple_tree')) NOT NULL,
                    grading_system TEXT CHECK(grading_system IN ('nordic_blue', 'tegernseer')) NOT NULL,
                    grade VARCHAR(10) NOT NULL,
                    drying TEXT CHECK(drying IN ('fresh', 'kiln_dried', 'air_dried')) NOT NULL,
                    treatment TEXT CHECK(treatment IN ('heat', 'anti_strain', 'null')) NOT NULL,
                    thickness INT NOT NULL,
                    width INT NOT NULL,
                    length INT NOT NULL
                );
SQL
        );

        $SQLite3->exec(
            <<<SQL
            CREATE UNIQUE INDEX IF NOT EXISTS unique_product ON products(
                species, grading_system, grade, drying, treatment,
                thickness, width, length
            );
SQL
        );

        return true;
    }

    public function seed(): bool
    {
        $this->seedSuppliers();
        $this->seedProducts();
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

    protected function seedProducts()
    {
        $SQLite3 = $this->getSQLite3();
        $SQLite3->exec("DELETE FROM products");
        $products = [
            1 => [
                'species' => 'pine',
                'grading_system' => 'nordic_blue',
                'grade' => 'a1',
                'drying' => 'fresh',
                'treatment' => 'null', // the unique key is failing with null ( particular to SQLite )
                'thickness' => 50,
                'width' => 120,
                'length' => 1200,
            ],

            2 => [
                'species' => 'spruce',
                'grading_system' => 'nordic_blue',
                'grade' => 'a1',
                'drying' => 'fresh',
                'treatment' => 'null',
                'thickness' => 50,
                'width' => 120,
                'length' => 1200,
            ],

            3 => [
                'species' => 'spruce',
                'grading_system' => 'nordic_blue',
                'grade' => 'a1',
                'drying' => 'fresh',
                'treatment' => 'heat',
                'thickness' => 50,
                'width' => 120,
                'length' => 1200,
            ],
        ];

        foreach ($products as $id => $data) {
            $query = "INSERT INTO products(id, ";
            $query .= implode(',', array_keys($data));
            $query .= ") VALUES ({$id},";
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
                array_values($data)
            );

            $query .= implode(',', $values);
            $query .= ");";
            $SQLite3->exec($query);
        }
    }
}

