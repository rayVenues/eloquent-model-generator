<?php

namespace Ray\EloquentModelGenerator\Model\Traits;

/**
 * Trait StaticModifierTrait
 * @package App\CodeGenerator\Model\Traits
 */
trait StaticModifierTrait
{
    /**
     * @var boolean
     */
    protected bool $static = false;

    /**
     * @return boolean
     */
    public function isStatic(): bool
    {
        return $this->static;
    }

    /**
     * @param boolean $static
     *
     * @return $this
     */
    public function setStatic(bool $static = true): static
    {
        $this->static = $static;

        return $this;
    }
}
