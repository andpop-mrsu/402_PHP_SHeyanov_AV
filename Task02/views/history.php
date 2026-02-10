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
        <h1>История игр</h1>
        <p class="subtle">Все завершенные партии хранятся в SQLite</p>
    </header>

    <div class="panel">
        <a class="retro-link" href="/index.php">Вернуться к новой партии</a>
    </div>

    <?php if (is_array($historyFlash)): ?>
        <div class="panel">
            <p class="notice <?= $historyFlash['type'] === 'success' ? 'notice-success' : 'notice-warning' ?>">
                <?= \SHeyanov_AV\Task02\App\escape($historyFlash['text']) ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="panel">
        <form id="clear-history-form" method="post" action="/history.php">
            <input type="hidden" name="action" value="clear_history">
            <input type="hidden" name="csrf_token" value="<?= \SHeyanov_AV\Task02\App\escape($csrfToken) ?>">
            <button id="open-clear-dialog" class="retro-btn retro-btn-danger" type="button">Очистить историю</button>
        </form>
    </div>

    <div class="panel">
        <?php if ($history === []): ?>
            <p>Пока нет сыгранных партий.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Игрок</th>
                    <th>Выражение</th>
                    <th>Ответ игрока</th>
                    <th>Правильный ответ</th>
                    <th>Результат</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($history as $row): ?>
                    <tr>
                        <td><?= \SHeyanov_AV\Task02\App\escape($row['played_at']) ?></td>
                        <td><?= \SHeyanov_AV\Task02\App\escape($row['player_name']) ?></td>
                        <td><span class="inline-code"><?= \SHeyanov_AV\Task02\App\escape($row['expression']) ?></span></td>
                        <td><?= \SHeyanov_AV\Task02\App\escape((string) $row['user_answer']) ?></td>
                        <td><?= \SHeyanov_AV\Task02\App\escape((string) $row['correct_answer']) ?></td>
                        <td>
                            <?php if ((int) $row['is_correct'] === 1): ?>
                                <span class="status-ok">Верно</span>
                            <?php else: ?>
                                <span class="status-bad">Неверно</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<dialog id="clear-history-dialog" class="retro-modal">
    <h2>Очистить историю?</h2>
    <p>Это действие удалит все партии без возможности восстановления.</p>
    <div class="modal-actions">
        <form method="dialog">
            <button class="retro-btn" type="submit">Отмена</button>
        </form>
        <button class="retro-btn retro-btn-danger" type="submit" form="clear-history-form">Очистить</button>
    </div>
</dialog>

<script>
    (function () {
        const openButton = document.getElementById('open-clear-dialog');
        const dialog = document.getElementById('clear-history-dialog');
        const form = document.getElementById('clear-history-form');

        if (!openButton || !form) {
            return;
        }

        openButton.addEventListener('click', function () {
            if (dialog && typeof dialog.showModal === 'function') {
                dialog.showModal();
                return;
            }

            if (window.confirm('Очистить всю историю игр?')) {
                form.submit();
            }
        });
    }());
</script>

</body>
</html>
