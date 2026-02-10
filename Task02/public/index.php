<?php

declare(strict_types=1);

session_start();

require dirname(__DIR__) . '/src/bootstrap.php';
require dirname(__DIR__) . '/src/game.php';
require dirname(__DIR__) . '/src/game_repository.php';
require dirname(__DIR__) . '/src/view.php';

use function SHeyanov_AV\Task02\App\escape;
use function SHeyanov_AV\Task02\App\generateExpression;
use function SHeyanov_AV\Task02\App\getConnection;
use function SHeyanov_AV\Task02\App\render;
use function SHeyanov_AV\Task02\App\saveGameResult;

$connection = getConnection();

$errorMessage = '';
$result = $_SESSION['flash_result'] ?? null;
$playerName = '';

unset($_SESSION['flash_result']);

if (!isset($_SESSION['expression'], $_SESSION['correct_answer'])) {
    [$expression, $correctAnswer] = generateExpression();
    $_SESSION['expression'] = $expression;
    $_SESSION['correct_answer'] = $correctAnswer;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerName = trim((string) ($_POST['player_name'] ?? ''));
    $rawAnswer = trim((string) ($_POST['user_answer'] ?? ''));

    if ($playerName === '') {
        $errorMessage = 'Введите имя игрока.';
    } else {
        $validatedAnswer = filter_var($rawAnswer, FILTER_VALIDATE_INT);
        if ($validatedAnswer === false) {
            $errorMessage = 'Введите целое число в поле ответа.';
        } else {
            $userAnswer = (int) $validatedAnswer;
            $correctAnswer = (int) $_SESSION['correct_answer'];
            $expression = (string) $_SESSION['expression'];
            $isCorrect = $userAnswer === $correctAnswer;

            saveGameResult($connection, [
                'player_name' => $playerName,
                'expression' => $expression,
                'correct_answer' => $correctAnswer,
                'user_answer' => $userAnswer,
                'is_correct' => $isCorrect,
                'played_at' => date('Y-m-d H:i:s'),
            ]);

            $result = [
                'player_name' => $playerName,
                'expression' => $expression,
                'correct_answer' => $correctAnswer,
                'user_answer' => $userAnswer,
                'is_correct' => $isCorrect,
            ];
            $_SESSION['flash_result'] = $result;

            [$newExpression, $newCorrectAnswer] = generateExpression();
            $_SESSION['expression'] = $newExpression;
            $_SESSION['correct_answer'] = $newCorrectAnswer;

            header('Location: /index.php');
            exit;
        }
    }
}

render('index', [
    'title' => 'Калькулятор',
    'expression' => (string) $_SESSION['expression'],
    'errorMessage' => $errorMessage,
    'result' => $result,
    'playerName' => escape($playerName),
]);
