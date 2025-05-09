<?php

namespace Scriptmancer\Sinan;

use Symfony\Component\Console\Application as ConsoleApplication;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class Application extends ConsoleApplication
{
    /**
     * @var array Configuration array loaded from sinan.php
     */
    protected array $config = [];
    /**
     * @var array<string,string> List of base command namespaces to auto-discover
     */
    protected array $commandNamespaces = [
        'Scriptmancer\\Sinan\\Command' => __DIR__ . '/Command',
    ];

    public function __construct(array $config = [])
    {
        parent::__construct('Sinan', 'v0.9.5');
        $this->config = $config;
        // Register commands from Scriptmancer\Sinan\Command namespace
        $this->registerCommands();
        // If in a project context, register commands from App\Commands namespace
        // $this->registerAppCommands();
    }
    
    /**
     * Register a new commands namespace for auto-discovery
     */
    public function registerNamespace(string $namespace, string $path): void
    {
        if (!is_dir($path)) {
            return;
        }
        
        $this->commandNamespaces[$namespace] = $path;
    }
    
    /**
     * Register commands from all registered namespaces
     */
    public function registerCommands(): void
    {
        foreach ($this->commandNamespaces as $namespace => $directory) {
            $this->registerCommandsFromNamespace($namespace, $directory);
        }
    }
    
    /**
     * Register commands from App\Commands namespace if we're in a project context
     */
   /* protected function registerAppCommands(): void
    {
        // Check if we're in a project context by looking for app/Commands directory
        $appCommandsDir = getcwd() . '/app/Commands';
        
        if (is_dir($appCommandsDir)) {
            $this->registerNamespace('App\\Commands', $appCommandsDir);
            $this->registerCommandsFromNamespace('App\\Commands', $appCommandsDir);
        }
    } */
    
    /**
     * Register all commands found in a specific namespace directory
     */
    protected function registerCommandsFromNamespace(string $namespace, string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }
        
        // Find all PHP files in the directory and subdirectories
        $directoryIterator = new RecursiveDirectoryIterator($directory);
        $iterator = new RecursiveIteratorIterator($directoryIterator);
        $phpFiles = new RegexIterator($iterator, '/^.+\.php$/i', RegexIterator::GET_MATCH);
        
        foreach ($phpFiles as $phpFile) {
            $filePath = $phpFile[0];
            
            // Skip abstract classes, interfaces, and traits
            if ($this->isAbstractOrInterfaceOrTrait($filePath)) {
                continue;
            }
            
            // Get the fully qualified class name
            $relativeFilePath = str_replace($directory, '', $filePath);
            $className = $this->convertPathToClassName($namespace, $relativeFilePath);
            
            // Skip if the class doesn't exist or is not a command
            if (!class_exists($className)) {
                continue;
            }
            
            if (!is_subclass_of($className, 'Symfony\Component\Console\Command\Command')) {
                continue;
            }
            
            // Add the command to the application
            try {
                $this->add(new $className());
            } catch (\Throwable $e) {
                // Skip commands that cause errors
            }
        }
    }
    
    /**
     * Convert a file path to a fully qualified class name
     */
    protected function convertPathToClassName(string $namespace, string $path): string
    {
        // Remove .php extension
        $path = str_replace('.php', '', $path);
        
        // Convert directory separators to namespace separators
        $path = str_replace(['/', '\\'], '\\', $path);
        
        // Remove leading namespace separator if it exists
        $path = ltrim($path, '\\');
        
        return $namespace . '\\' . $path;
    }
    
    /**
     * Check if a file defines an abstract class, interface, or trait
     */
    protected function isAbstractOrInterfaceOrTrait(string $filePath): bool
    {
        // Read only the first 200 lines to improve performance
        $file = new \SplFileObject($filePath);
        $content = '';
        $lineCount = 0;
        
        while(!$file->eof() && $lineCount < 200) {
            $content .= $file->fgets();
            $lineCount++;
        }
        
        return 
            preg_match('/^\s*abstract\s+class\s+/im', $content) ||
            preg_match('/^\s*interface\s+/im', $content) ||
            preg_match('/^\s*trait\s+/im', $content);
    }

    /**
     * Get config value by key, or the whole config array.
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfig(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->config;
        }
        return $this->config[$key] ?? $default;
    }
}

