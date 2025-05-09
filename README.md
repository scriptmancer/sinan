# Sinan CLI

A lightweight and powerful CLI tool for the Nazım framework, built on top of Symfony Console.

## Installation

```bash
composer require scriptmancer/sinan
```

## Usage

### Basic Usage

```bash
# Run the CLI tool
./sinan

# List available commands
./sinan list

# Get help for a specific command
./sinan help [command]
```

### Creating Commands

Sinan allows you to create new commands using the `create:command` command:

```bash
# Create a command
./sinan create:command Hello

# Create a nested command
./sinan create:command Hello/World

# Create a deeply nested command
./sinan create:command Hello/World/Goodmorning
```

The commands follow these naming conventions:

| Command | Path | Class Name | Signature |
|---------|------|------------|-----------|
| `./sinan create:command Hello` | `src/Command/Hello/DefaultCommand.php` | `DefaultCommand` | `app:hello` |
| `./sinan create:command Hello/World` | `src/Command/Hello/WorldCommand.php` | `WorldCommand` | `app:hello-world` |
| `./sinan create:command Hello/World/Goodmorning` | `src/Command/Hello/World/GoodmorningCommand.php` | `GoodmorningCommand` | `app:hello-world-goodmorning` |

### Command Auto-Discovery

Sinan automatically discovers and registers commands from:

1. `Sinan\Command` namespace (core commands)
2. `App\Commands` namespace (application commands)
3. Custom namespaces registered via configuration

### Termwind Integration

Sinan includes [Termwind](https://github.com/nunomaduro/termwind) for beautiful console output:

```bash
# Try the Termwind showcase
./sinan app:termwind-showcase
```

## Configuration

### Project-Level Configuration

Create a `sinan.php` or `config/sinan.php` file in your project root:

```php
<?php
return [
    // Directory where new commands will be generated
    'command_directory' => __DIR__ . '/../app/Commands',

    // Namespace for generated commands
    'command_namespace' => 'App\\Commands',

    // Additional namespaces to register for command discovery
    'extra_command_namespaces' => [
        ['namespace' => 'App\\Commands', 'path' => __DIR__ . '/../app/Commands'],
        // Example: ['namespace' => 'Other\\Commands', 'path' => __DIR__ . '/../other/Commands'],
    ],
];
```

- If omitted, Sinan defaults to `src/Command` and `Scriptmancer\Sinan\Command` for generation.
- You can register multiple command packs by adding them to `extra_command_namespaces`.

## Extending Sinan

### Creating Custom Commands

Create a new command in the `App\Commands` namespace:

```php
<?php

namespace App\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:custom-command',
    description: 'My custom command',
)]
class CustomCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Hello from my custom command!</info>');
        return Command::SUCCESS;
    }
}
```

### Using League/CLImate

Sinan CLI uses [League/CLImate](https://github.com/thephpleague/climate) for beautiful, expressive CLI output.

#### Installation
CLImate is installed automatically as a dependency. No extra steps needed.

#### Example Usage
```php
use League\CLImate\CLImate;

$climate = new CLImate();
$climate->bold()->backgroundBlue()->white()->out(' Sinan CLI ');
$climate->out('A modern, extensible CLI for the Nazım framework');
$climate->green('Version: v0.9.5');
$climate->yellow('Author: Gökhan SARIGÜL <gsarigul84@gmail.com>');
$climate->table([
    ['Feature' => 'Colors', 'Demo' => 'green, yellow, red, blue'],
    ['Feature' => 'Bold', 'Demo' => 'bold()'],
    ['Feature' => 'Backgrounds', 'Demo' => 'backgroundBlue()'],
    ['Feature' => 'Tables', 'Demo' => 'table()'],
]);
```

#### Showcase Command
Run the following to see a full demonstration of CLImate features:

```bash
./sinan climate:showcase
```

This will display colored messages, tables, progress bars, input prompts, and more.

See the [League/CLImate documentation](https://climate.thephpleague.com/) for all available features.

## License

MIT

## Author

Gökhan SARIGÜL <gsarigul84@gmail.com>
