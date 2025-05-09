<?php

namespace Scriptmancer\Sinan\Command\About;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Termwind\{render};
#[AsCommand(
    name: 'about',
    description: 'Show information about Sinan',
)]
class DefaultCommand extends Command
{
 
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $climate = new \League\CLImate\CLImate();
        $climate->bold()->backgroundBlue()->white()->out(' Sinan CLI ');
        $climate->out('A modern, extensible CLI for the Nazım framework');
        $climate->green('Version: v0.9.5');
        $climate->green('Author: Gökhan SARIGÜL <gsarigul84@gmail.com>');
        $climate->out('Powered by Symfony Console and CLImate.');
        return Command::SUCCESS;
    }
}
