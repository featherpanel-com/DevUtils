<?php

/*
 * This file is part of FeatherPanel.
 *
 * Copyright (C) 2025 MythicalSystems Studios
 * Copyright (C) 2025 FeatherPanel Contributors
 * Copyright (C) 2025 Cassian Gherman (aka NaysKutzu)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See the LICENSE file or <https://www.gnu.org/licenses/>.
 */

namespace App\Addons\devutils\Commands;

use App\Cli\App;
use App\Cli\CommandBuilder;

class BackendLint implements CommandBuilder
{
    public static function execute(array $args): void
    {
        $app = App::getInstance();
        $app->send('&e&l🔍 Linting backend...');

        $process = popen('cd backend && export COMPOSER_ALLOW_SUPERUSER=1 && composer run lint 2>&1', 'r');
        if (is_resource($process)) {
            while (!feof($process)) {
                $output = fgets($process);
                $app->processOutput($output);
            }
            $returnVar = pclose($process);
            if ($returnVar !== 0) {
                $app->send('&c&l❌ Failed to lint backend.');
                exit(1);
            }
            $app->send('&a&l✅ Backend linted successfully.');

        } else {
            $app->send('&c&l❌ Failed to start lint process.');
            exit(1);
        }
    }

    public static function getDescription(): string
    {
        return 'Lint the backend code';
    }

    public static function getSubCommands(): array
    {
        return [];
    }
}
