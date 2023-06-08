<?php

namespace Ray\EloquentModelGenerator\Helper;

use Exception;
use Ray\EloquentModelGenerator\Exception\GeneratorException;

class NamespaceValidator
{

    /**
     * @throws Exception
     */
    static function pathToModelNamespace($path): string | bool
    {
        $path = trim($path);

        $pathSegments = explode('\\', $path);

        $pathSegments = array_filter($pathSegments);

        $pathSegments = array_map(function ($segment) {
            return ucfirst($segment);
        }, $pathSegments);

        $namespace = implode('\\', $pathSegments);

        $appBasePath = getcwd();
        if (! str_starts_with($path, '/')) {
            $path = getcwd() . '/app/' . $path;
        }

        if (! str_contains(strtolower($path), realpath(strtolower($appBasePath)) . '/')) {
            throw new GeneratorException('The path "' . $path . '" is not within your app\'s  directory structure.');
        }

        if (! str_starts_with($namespace, 'App/')) {
            $namespace = 'App\\' . $namespace;
        }

        return $namespace;
    }
}