<?php

namespace Scriptmancer\Sinan\Command\Create;

use Scriptmancer\Sinan\Support;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'create:command',
    description: 'Create a new command',
    hidden: false,
    aliases: ['c:c']
)]
class DefaultCommand extends Command
{
 
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');
        
        // Get config from Application
        $app = $this->getApplication();
        $config = method_exists($app, 'getConfig') ? $app->getConfig() : [];

        // Set defaults if not set in config
        $namespaceBase = $config['command_namespace'] ?? 'Scriptmancer\\Sinan\\Command';
        $directoryBase = $config['command_directory'] ?? (getcwd() . '/src/Command');

        // Generate namespace, class name and signature
        $namespace = Support::generateNamespace($path, $namespaceBase);
        $className = Support::generateClassName($path);
        $signature = Support::generateCommandSignature($path);
        
        // Get and replace placeholders in the stub
        $stub = Support::getStub('create-command');
        $stub = str_replace('<Namespace>', $namespace, $stub);
        $stub = str_replace('<CommandName>', $className, $stub);
        $stub = str_replace('<Signature>', $signature, $stub);
        
        // Create the command file
        $commandFilePath = Support::generateCommandFilePath($path, $directoryBase);
        
        // Create directory if it doesn't exist
        $directory = dirname($commandFilePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Write the command file
        file_put_contents($commandFilePath, $stub);
        
        $output->writeln("<info>Command created successfully at:</info> {$commandFilePath}");
        $output->writeln("<info>Run with:</info> ./sinan {$signature}");
        
        return Command::SUCCESS;    
    }

    public function configure(): void
    {
        $this->addArgument('path', InputArgument::REQUIRED, 'Full path to the command file. Each slash (/) will create a new directory.');
    }
}

