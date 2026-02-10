<?php

declare(strict_types=1);

namespace SHeyanov_AV\Task02\App;

function render(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require appRootPath() . '/views/' . $template . '.php';
}

