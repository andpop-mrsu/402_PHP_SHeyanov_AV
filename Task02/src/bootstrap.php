<?php

declare(strict_types=1);

namespace SHeyanov_AV\Task02\App;

use PDO;

function appRootPath(): string
{
    return dirname(__DIR__);
}

function databasePath(): string
{
    return appRootPath() . '/db/calculator.sqlite';
}

function getConnection(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $databaseDir = dirname(databasePath());
    if (!is_dir($databaseDir)) {
        mkdir($databaseDir, 0777, true);
    }

    $connection = new PDO('sqlite:' . databasePath());
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    initializeDatabase($connection);

    return $connection;
}

function initializeDatabase(PDO $connection): void
{
    $connection->exec(
        'CREATE TABLE IF NOT EXISTS games (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            player_name TEXT NOT NULL,
            expression TEXT NOT NULL,
            correct_answer INTEGER NOT NULL,
            user_answer INTEGER NOT NULL,
            is_correct INTEGER NOT NULL,
            played_at TEXT NOT NULL
        )'
    );
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

