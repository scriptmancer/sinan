<?php

namespace Scriptmancer\Sinan\Command\About;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Termwind\{render};
#[AsCommand(
    name: 'info',
    description: 'Show information about Sinan',
)]
class InfoCommand extends Command
{
 
    public function execute(InputInterface $input, OutputInterface $output): int
{
    $app = $this->getApplication();
    $config = method_exists($app, 'getConfig') ? $app->getConfig() : [];
    $commandDir = $config['command_directory'] ?? '(default)';
    $commandNs = $config['command_namespace'] ?? '(default)';
    $extra = $config['extra_command_namespaces'] ?? [];

    $climate = new \League\CLImate\CLImate();
    $climate->bold()->backgroundGreen()->white()->out(' Sinan CLI Configuration ');
    $climate->table([
        ['Key' => 'command_directory', 'Value' => $commandDir],
        ['Key' => 'command_namespace', 'Value' => $commandNs]
    ]);
    $climate->out('Extra Command Namespaces:');
    if (!empty($extra)) {
        foreach ($extra as $ns) {
            $climate->blue('- ' . ($ns['namespace'] ?? '') . ' : ' . ($ns['path'] ?? ''));
        }
    } else {
        $climate->yellow('  (none)');
    }
    return Command::SUCCESS;
}

}
