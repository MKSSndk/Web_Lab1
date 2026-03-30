<?php

define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);
define('DB_NAME', 'dobrostroy_db');
define('DB_USER', 'root');
define('DB_PASS', '11727');

function db(): mysqli {
    static $connection = null;

    if ($connection instanceof mysqli) {
        return $connection;
    }

    mysqli_report(MYSQLI_REPORT_OFF);
    $connection = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    if ($connection->connect_errno) {
        throw new RuntimeException('Не удалось подключиться к MySQL: ' . $connection->connect_error);
    }

    if (!$connection->set_charset('utf8mb4')) {
        throw new RuntimeException('Не удалось установить кодировку utf8mb4.');
    }

    return $connection;
}
