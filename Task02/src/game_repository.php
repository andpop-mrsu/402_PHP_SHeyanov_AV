<?php

declare(strict_types=1);

namespace SHeyanov_AV\Task02\App;

use PDO;

function saveGameResult(PDO $connection, array $game): void
{
    $statement = $connection->prepare(
        'INSERT INTO games (player_name, expression, correct_answer, user_answer, is_correct, played_at)
         VALUES (:player_name, :expression, :correct_answer, :user_answer, :is_correct, :played_at)'
    );

    $statement->execute([
        ':player_name' => $game['player_name'],
        ':expression' => $game['expression'],
        ':correct_answer' => $game['correct_answer'],
        ':user_answer' => $game['user_answer'],
        ':is_correct' => $game['is_correct'] ? 1 : 0,
        ':played_at' => $game['played_at'],
    ]);
}

function fetchGameHistory(PDO $connection, int $limit = 100): array
{
    $statement = $connection->prepare(
        'SELECT player_name, expression, correct_answer, user_answer, is_correct, played_at
         FROM games
         ORDER BY id DESC
         LIMIT :limit'
    );
    $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetchAll();
}

function clearGameHistory(PDO $connection): void
{
    $connection->exec('DELETE FROM games');
}
