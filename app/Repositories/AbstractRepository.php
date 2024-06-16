<?php

namespace App\Repositories;

abstract class AbstractRepository
{
    const DATABASE_PATH = '/storage/';
    const DATABASE_FILE = 'timberhub.db3';

    protected static ?\SQLite3 $SQLite3 = null;

    protected static function getSQLite3(): \SQLite3
    {
        if (false == (self::$SQLite3 instanceof \SQLite3)) {
            $databaseFile = $_SERVER['DOCUMENT_ROOT'] . '/..';
            $databaseFile .= self::DATABASE_PATH;
            $databaseFile .= self::DATABASE_FILE;
            self::$SQLite3 = new \SQLite3($databaseFile);
        }

        return self::$SQLite3;
    }

    protected function fetchAsArray(string $query, array $args = []): array
    {
        $SQLite3 = self::getSQLite3();
        $stmt = $SQLite3->prepare($query);
        $result = $stmt->execute();
        if (0 == $result->numColumns()) {
            return [];
        }

        $resultArray = [];
        while ($resultItem = $result->fetchArray(SQLITE3_ASSOC)) {
            $resultArray[] = $resultItem;
        }

        return $resultArray;
    }
}

