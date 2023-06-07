<?php

namespace Ray\EloquentModelGenerator\Helper;

use Exception;

class NamespaceValidator {

    /**
     * @throws Exception
     */
    static function pathToModelNamespace($path):string|bool
    {
        $path = trim($path);

        $pathSegments = explode('\\', $path);

        $pathSegments = array_filter($pathSegments);

        $pathSegments = array_map(function ($segment) {
            return ucfirst($segment);
        }, $pathSegments);

        $namespace = implode('\\', $pathSegments);

        if (function_exists('base_path')) {
            $laravelBasePath = base_path();
            $path = base_path($path);
        } else {
            $laravelBasePath = getcwd();
            // If the path is not absolute, make it absolute
            if (! str_starts_with($path, '/')) {
                $path = getcwd() . '/app/' . $path;
            }
        }

        if (! str_contains(strtolower($path), realpath(strtolower($laravelBasePath)) . '/')) {
            throw new Exception('The path "' . $path . '" is not within your app\'s  directory structure.');
        }

        if (! str_starts_with($namespace, 'App/')) {
            $namespace = 'App\\' . $namespace;
        }

        return $namespace;
    }
}