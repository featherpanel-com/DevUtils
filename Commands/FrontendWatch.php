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

class FrontendWatch implements CommandBuilder
{
    public static function execute(array $args): void
    {
        $app = App::getInstance();
        $app->send('&e&l👀 Starting frontend watch mode...');

        $process = popen('cd frontend && pnpm dev 2>&1', 'r');
        if (is_resource($process)) {
            while (!feof($process)) {
                $output = fgets($process);
                $app->processOutput($output);
            }
            $returnVar = pclose($process);
            if ($returnVar !== 0) {
                $app->send('&c&l❌ Failed to start frontend watch mode.');
                exit(1);
            }
            $app->send('&a&l✅ Frontend watch mode stopped.');

        } else {
            $app->send('&c&l❌ Failed to start watch process.');
            exit(1);
        }
    }

    public static function getDescription(): string
    {
        return 'Start frontend development watch mode';
    }

    public static function getSubCommands(): array
    {
        return [];
    }
}
