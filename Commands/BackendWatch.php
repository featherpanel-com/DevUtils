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

class BackendWatch implements CommandBuilder
{
    public static function execute(array $args): void
    {
        $app = App::getInstance();
        $app->send('&e&l👀 Starting backend watch mode...');
        $app->send('&7Please wait while we attach to the process...');

        sleep(2);

        $process = popen('tail -f backend/storage/logs/featherpanel-web.fplog backend/storage/logs/App.fplog', 'r');
        if (is_resource($process)) {
            $app->send('&a&l✅ Attached to the process.');
            while (!feof($process)) {
                $output = fgets($process);
                $app->processOutput($output);
            }
            $returnVar = pclose($process);
            if ($returnVar !== 0) {
                $app->send('&c&l❌ Failed to watch backend.');
                exit(1);
            }
            $app->send('&a&l✅ Backend watch mode stopped.');

        } else {
            $app->send('&c&l❌ Failed to start watch process.');
            exit(1);
        }
    }

    public static function getDescription(): string
    {
        return 'Watch backend logs in real-time';
    }

    public static function getSubCommands(): array
    {
        return [];
    }
}
