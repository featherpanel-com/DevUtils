<?php

/*
 * This file is part of FeatherPanel.
 *
 * MIT License
 *
 * Copyright (c) 2025 MythicalSystems
 * Copyright (c) 2025 Cassian Gherman (NaysKutzu)
 * Copyright (c) 2018 - 2021 Dane Everitt <dane@daneeveritt.com> and Contributors
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
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
