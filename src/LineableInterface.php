<?php

namespace Ray\EloquentModelGenerator;

/**
 * Interface LineableInterface
 * @package App\CodeGenerator
 */
interface LineableInterface
{
    /**
     * @return string|string[]
     */
    public function toLines(): string|array;
}
