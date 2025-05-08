<?php

namespace Sinan\Command\Create;

use Sinan\Support;
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
        
        // Generate namespace, class name and signature
        $namespace = $this->generateNamespace($path);
        $className = $this->generateClassName($path);
        $signature = $this->generateCommandSignature($path);
        
        // Get and replace placeholders in the stub
        $stub = Support::getStub('create-command');
        $stub = str_replace('<NamespacePrefix>', $namespace, $stub);
        $stub = str_replace('<CommandName>', $className, $stub);
        $stub = str_replace('<Signature>', $signature, $stub);
        
        // Create the command file
        $commandFilePath = $this->generateCommandFilePath($path);
        
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
    
    private function generateNamespace(string $path): string
    {
        $parts = explode('/', $path);
        $namespaceArray = array_map('ucfirst', $parts);
        return implode('\\', $namespaceArray);
    }
    
    private function generateClassName(string $path): string
    {
        $parts = explode('/', $path);
        if (count($parts) === 1) {
            // For commands with no slashes, like "Test", class name should be "Default"
            return "Default";
        } else {
            // For commands with slashes, use the last part
            $className = end($parts);
            return ucfirst($className);
        }
    }
    
    private function generateCommandSignature(string $path): string
    {
        $signature = strtolower(str_replace('/', '-', $path));
        return 'app:' . $signature;
    }
    
    private function generateCommandFilePath(string $path): string
    {
        $parts = explode('/', $path);
        
        if (count($parts) === 1) {
            // For "./sinan create:command Hello" format
            return getcwd() . '/src/Command/' . ucfirst($parts[0]) . '/DefaultCommand.php';
        } else {
            // For "./sinan create:command Hello/World" or "./sinan create:command Hello/World/Goodmorning" format
            $lastPart = array_pop($parts);
            $directory = implode('/', array_map('ucfirst', $parts));
            return getcwd() . '/src/Command/' . $directory . '/' . ucfirst($lastPart) . 'Command.php';
        }
    }
}
