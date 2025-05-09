<?php

namespace Scriptmancer\Sinan;

class Support
{
    public static function getStub(string $name): string
    {
        return file_get_contents(__DIR__ . '/../stubs/' . $name . '.stub');
    }

    public static function generateNamespace(string $path, ?string $namespaceOverride = null): string
    {
        $parts = explode('/', $path);
        if (count($parts) > 1) {
            // Remove the last part (class name)
            array_pop($parts);
        }
        $namespaceArray = array_map('ucfirst', $parts);
        if ($namespaceOverride) {
            // Append the subdirectory parts to the base namespace
            return rtrim($namespaceOverride, '\\') . (count($namespaceArray) ? '\\' . implode('\\', $namespaceArray) : '');
        }
        return implode('\\', $namespaceArray);
    }

    public static function getCommandPath(string $path): string
    {
        $path = explode('/', $path);
        if(count($path) > 1){
            $path = array_slice($path, 0, -1);
            $path = array_map('ucfirst', $path);
            return implode('\\', $path);
        }
        return ucfirst($path[0]);
    }

    public static function generateClassName(string $path): string
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

    public static function generateCommandSignature(string $path): string
    {
        $signature = strtolower(str_replace('/', '-', $path));
        return 'app:' . $signature;
    }
    public static function generateCommandFilePath(string $path, ?string $directoryOverride = null): string
    {
        $parts = explode('/', $path);
        $baseDir = $directoryOverride ? rtrim($directoryOverride, '/\\') : getcwd() . '/src/Command';

        if (count($parts) === 1) {
            // For "./sinan create:command Hello" format
            return $baseDir . '/' . ucfirst($parts[0]) . '/DefaultCommand.php';
        } else {
            // For "./sinan create:command Hello/World" or deeper
            $lastPart = array_pop($parts);
            $directory = implode('/', array_map('ucfirst', $parts));
            return $baseDir . '/' . $directory . '/' . ucfirst($lastPart) . 'Command.php';
        }
    }

}