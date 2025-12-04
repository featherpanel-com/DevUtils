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

class MakeCron implements CommandBuilder
{
    public static function execute(array $args): void
    {
        $app = App::getInstance();
        $app->send('&e&l📝 Creating new app cron...');
        $app->send('&7Enter cron name (e.g. MyCronTask): ');

        $cronName = trim(fgets(STDIN));

        if (empty($cronName)) {
            $app->send('&c&l❌ Cron name is required.');
            exit(1);
        }

        // Validate cron name
        if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $cronName)) {
            $app->send('&c&l❌ Cron name must be PascalCase (e.g. MyCronTask).');
            exit(1);
        }

        // Generate filename
        $filename = $cronName . '.php';
        $filepath = 'backend/storage/cron/php/' . $filename;

        // Check if file already exists
        if (file_exists($filepath)) {
            $app->send('&c&l❌ Cron already exists: ' . $filename);
            exit(1);
        }

        // Generate cron content
        $content = self::generateCronContent($cronName);

        // Write file
        if (file_put_contents($filepath, $content) === false) {
            $app->send('&c&l❌ Failed to create cron file.');
            exit(1);
        }

        $app->send('&a&l✅ Cron created successfully: &f' . $filename);
        $app->send('&7Location: &f' . $filepath);
    }

    public static function getDescription(): string
    {
        return 'Create a new app cron task';
    }

    public static function getSubCommands(): array
    {
        return [];
    }

    private static function generateCronContent(string $cronName): string
    {
        return "<?php

/*
 * This file is part of FeatherPanel.
 * Please view the LICENSE file that was distributed with this source code.
 *
 * # MythicalSystems License v2.0
 *
 * ## Copyright (c) 2021–2025 MythicalSystems and Cassian Gherman
 *
 * Breaking any of the following rules will result in a permanent ban from the MythicalSystems community and all of its services.
 */

namespace App\\Cron;

/**
 * {$cronName} - Cron task description
 *
 * This cron job runs every minute and handles [describe what this cron does].
 */

use App\\App;
use App\\Cli\\Utils\\MinecraftColorCodeSupport;
use App\\Cron\\Cron;
use App\\Cron\\TimeTask;

class {$cronName} implements TimeTask
{
    /**
     * Entry point for the cron {$cronName}.
     */
    public function run()
    {
        \$cron = new Cron('" . strtolower(preg_replace('/([A-Z])/', '-$1', $cronName)) . "', '1M');
        try {
            \$cron->runIfDue(function () {
                \$this->processTask();
            });
        } catch (\\Exception \$e) {
            \$app = App::getInstance(false, true);
            \$app->getLogger()->error('Failed to process {$cronName}: ' . \$e->getMessage());
        }
    }

    /**
     * Process the main task logic.
     */
    private function processTask()
    {
        \$app = App::getInstance(false, true);
        MinecraftColorCodeSupport::sendOutputWithNewLine('&aProcessing {$cronName}...');

        // Add your cron logic here
        // Example:
        // - Database operations
        // - File processing
        // - API calls
        // - Cleanup tasks
        // - etc.

        MinecraftColorCodeSupport::sendOutputWithNewLine('&a{$cronName} completed successfully');
    }
}
";
    }
}
