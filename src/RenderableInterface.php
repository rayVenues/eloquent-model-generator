<?php

namespace Ray\EloquentModelGenerator;

/**
 * Interface RenderableInterface
 * @package App\CodeGenerator
 */
interface RenderableInterface
{
    /**
     * @param int $indent
     * @param string $delimiter
     * @return string
     */
    public function render(int $indent = 0, string $delimiter = PHP_EOL): string;
}
