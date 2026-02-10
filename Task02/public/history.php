<?php

declare(strict_types=1);

session_start();

require dirname(__DIR__) . '/src/bootstrap.php';
require dirname(__DIR__) . '/src/game_repository.php';
require dirname(__DIR__) . '/src/view.php';

use function SHeyanov_AV\Task02\App\clearGameHistory;
use function SHeyanov_AV\Task02\App\fetchGameHistory;
use function SHeyanov_AV\Task02\App\getConnection;
use function SHeyanov_AV\Task02\App\render;

$connection = getConnection();

if (!isset($_SESSION['history_csrf_token'])) {
    $_SESSION['history_csrf_token'] = bin2hex(random_bytes(16));
}

$sessionToken = (string) $_SESSION['history_csrf_token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? '');
    $postedToken = (string) ($_POST['csrf_token'] ?? '');

    if ($action === 'clear_history' && hash_equals($sessionToken, $postedToken)) {
        clearGameHistory($connection);
        $_SESSION['history_flash'] = [
            'type' => 'success',
            'text' => 'История успешно очищена.',
        ];
    } else {
        $_SESSION['history_flash'] = [
            'type' => 'error',
            'text' => 'Не удалось очистить историю. Обновите страницу и попробуйте снова.',
        ];
    }

    header('Location: /history.php');
    exit;
}

$historyFlash = $_SESSION['history_flash'] ?? null;
unset($_SESSION['history_flash']);

$history = fetchGameHistory($connection);

render('history', [
    'title' => 'История игр',
    'history' => $history,
    'csrfToken' => $sessionToken,
    'historyFlash' => $historyFlash,
]);
