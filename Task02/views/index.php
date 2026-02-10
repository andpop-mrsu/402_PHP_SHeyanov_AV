<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \SHeyanov_AV\Task02\App\escape($title) ?></title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<div class="page">
    <header class="panel header-panel">
        <h1>Игра «Калькулятор»</h1>
        <p class="subtle">Task02</p>
    </header>

    <div class="panel">
        <a class="retro-link" href="/history.php">Открыть историю игр</a>
    </div>

    <div class="panel">
        <p class="subtle">Вычислите выражение</p>
        <p class="expression inline-code"><?= \SHeyanov_AV\Task02\App\escape($expression) ?></p>

        <?php if ($errorMessage !== ''): ?>
            <p class="notice notice-warning"><?= \SHeyanov_AV\Task02\App\escape($errorMessage) ?></p>
        <?php endif; ?>

        <form method="post" action="/index.php">
            <div class="field">
                <label for="player_name">Имя игрока</label>
                <input id="player_name" name="player_name" required value="<?= $playerName ?>">
            </div>

            <div class="field">
                <label for="user_answer">Ответ</label>
                <input id="user_answer" name="user_answer" required>
            </div>

            <button class="retro-btn" type="submit">Проверить ответ</button>
        </form>
    </div>

    <?php if (is_array($result)): ?>
        <div class="panel result-list">
            <p>Игрок: <strong><?= \SHeyanov_AV\Task02\App\escape($result['player_name']) ?></strong></p>
            <p>Выражение: <span class="inline-code"><?= \SHeyanov_AV\Task02\App\escape($result['expression']) ?></span></p>
            <p>Ваш ответ: <strong><?= \SHeyanov_AV\Task02\App\escape((string) $result['user_answer']) ?></strong></p>
            <p>Правильный ответ: <strong><?= \SHeyanov_AV\Task02\App\escape((string) $result['correct_answer']) ?></strong></p>

            <?php if ($result['is_correct']): ?>
                <p class="status-ok"><strong>Верно! :-)</strong></p>
            <?php else: ?>
                <p class="status-bad"><strong>Неверно. :-(</strong></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
