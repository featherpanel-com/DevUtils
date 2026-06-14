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

class MakeMigration implements CommandBuilder
{
    public static function execute(array $args): void
    {
        $app = App::getInstance();
        $app->send('&e&l📝 Creating new migration...');
        $app->send('&7Enter migration name (e.g. add-user-table): ');

        $migrationName = trim(fgets(STDIN));

        if (empty($migrationName)) {
            $app->send('&c&l❌ Migration name is required.');
            exit(1);
        }

        $date = date('Y-m-d.H.i');
        $filename = $date . '-' . $migrationName . '.sql';
        $filepath = 'backend/storage/migrations/' . $filename;

        if (file_put_contents($filepath, '') === false) {
            $app->send('&c&l❌ Failed to create migration file.');
            exit(1);
        }

        $app->send('&a&l✅ Created migration file: &f' . $filename);
    }

    public static function getDescription(): string
    {
        return 'Create a new database migration file';
    }

    public static function getSubCommands(): array
    {
        return [];
    }
}
