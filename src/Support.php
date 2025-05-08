<?php

namespace Sinan;

class Support
{
    public static function getStub(string $name): string
    {
        return file_get_contents(__DIR__ . '/../stubs/' . $name . '.stub');
    }

    public static function generateNamespace(string $path): string
    {
        $path = explode('/', $path);
        $path = array_map('ucfirst', $path);
        return implode('\\', $path);
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
        if(count($parts) > 1){
            $className = end($parts);
            $className = ucfirst($className);
            return $className;
        }
        return 'Default';
    }

    public static function generateCommandSignature(string $path): string
    {
        $signature = strtolower(str_replace('/', '-', $path));
        return 'app:' . $signature;
    }
    
    
}