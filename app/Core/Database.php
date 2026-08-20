<?php

declare(strict_types=1);

namespace App\Core;

use App\Helpers\EnvironmentHelper;
use PDO;
use PDOException;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $host = EnvironmentHelper::string('DB_HOST', '127.0.0.1');
        $port = EnvironmentHelper::string('DB_PORT', '3306');
        $database = EnvironmentHelper::string('DB_DATABASE', 'radio_rumbo');
        $username = EnvironmentHelper::string('DB_USERNAME', 'root');
        $password = EnvironmentHelper::string('DB_PASSWORD', '');

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $host,
            $port,
            $database
        );

        try {
            self::$connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            throw new PDOException(
                'No se pudo conectar con la base de datos.',
                (int) $exception->getCode(),
                $exception
            );
        }

        return self::$connection;
    }
}
