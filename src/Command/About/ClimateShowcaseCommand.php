<?php

namespace Scriptmancer\Sinan\Command\About;

use League\CLImate\CLImate;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'climate:showcase',
    description: 'Showcase League\CLImate output styles',
)]
class ClimateShowcaseCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $climate = new CLImate();
        $climate->bold()->backgroundBlue()->white()->out(' CLImate Showcase ');
        $climate->out('This is a demonstration of League\CLImate features:');
        $climate->green('Success message');
        $climate->yellow('Warning message');
        $climate->red('Error message');
        $climate->blue('Informational message');
        $climate->table([
            ['Feature' => 'Colors', 'Demo' => 'green, yellow, red, blue'],
            ['Feature' => 'Bold', 'Demo' => 'bold()'],
            ['Feature' => 'Backgrounds', 'Demo' => 'backgroundBlue()'],
            ['Feature' => 'Tables', 'Demo' => 'table()'],
        ]);
        $climate->br();
        $climate->out('Multi-line output:');
        $climate->out(["Line 1", "Line 2", "Line 3"]);
        $climate->br();
        $climate->out('Progress bar:');
        $progress = $climate->progress(10);
        for ($i = 0; $i < 10; $i++) {
            usleep(100000); // 0.1s
            $progress->advance();
        }
        $climate->br();
        $climate->out('Input prompt (simulated):');
        $climate->out('> What is your name? [Simulated: John Doe]');
        $climate->br();
        $climate->out('JSON output:');
        $climate->out(json_encode(['foo' => 'bar', 'baz' => 123], JSON_PRETTY_PRINT));
        $climate->br();
        $climate->out('Inline overwriting:');
        for ($i = 0; $i <= 5; $i++) {
            $climate->inline("Counter: $i\r");
            usleep(200000); // 0.2s
        }
        $climate->br();
        $climate->out('Enjoy building beautiful CLI tools with CLImate!');
        return Command::SUCCESS;
    }
}
