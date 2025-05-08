<?php

namespace Sinan\Command\Hello\World;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:hello-world',
    description: 'Add command description here',
)]
class WorldCommand extends Command
{
 
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;    
    }
}
