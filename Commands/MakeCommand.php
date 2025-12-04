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

class MakeCommand implements CommandBuilder
{
    public static function execute(array $args): void
    {
        $app = App::getInstance();
        $app->send('&e&l📝 Creating new CLI command...');
        $app->send('&7Enter command name (e.g. MyCommand): ');

        $commandName = trim(fgets(STDIN));

        if (empty($commandName)) {
            $app->send('&c&l❌ Command name is required.');
            exit(1);
        }

        // Validate command name
        if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $commandName)) {
            $app->send('&c&l❌ Command name must be PascalCase (e.g. MyCommand).');
            exit(1);
        }

        // Generate filename
        $filename = $commandName . '.php';
        $filepath = 'backend/app/Cli/Commands/' . $filename;

        // Check if file already exists
        if (file_exists($filepath)) {
            $app->send('&c&l❌ Command already exists: ' . $filename);
            exit(1);
        }

        // Generate class content
        $classContent = self::generateCommandClass($commandName);

        // Create the file
        if (file_put_contents($filepath, $classContent) === false) {
            $app->send('&c&l❌ Failed to create command file.');
            exit(1);
        }

        $app->send('&a&l✅ Created command file: &f' . $filename);
        $app->send('&7&l📁 Location: &f' . $filepath);
        $app->send('&7&l💡 Usage: &fphp app ' . lcfirst($commandName));
    }

    public static function getDescription(): string
    {
        return 'Create a new CLI command class';
    }

    public static function getSubCommands(): array
    {
        return [];
    }

    private static function generateCommandClass(string $commandName): string
    {
        $lowerCommandName = lcfirst($commandName);

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

namespace App\Cli\Commands;

use App\Cli\App;
use App\Cli\CommandBuilder;

class {$commandName} extends App implements CommandBuilder
{
    public static function execute(array \$args): void
    {
        \$app = App::getInstance();
        \$app->send('&e&l🚀 Executing {$lowerCommandName} command...');
        
        // TODO: Implement your command logic here
        
        \$app->send('&a&l✅ {$commandName} command completed successfully!');
    }

    public static function getDescription(): string
    {
        return 'Description for {$lowerCommandName} command';
    }

    public static function getSubCommands(): array
    {
        return [];
    }
}";
    }
}
