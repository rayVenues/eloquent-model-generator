<?php

namespace Ray\EloquentModelGenerator\Model\Traits;

/**
 * Trait AbstractMethodModifierTrait
 * @package App\CodeGenerator\Model\Traits
 */
trait AbstractMethodModifierTrait
{
    /**
     * @var boolean;
     */
    protected bool $abstract = false;

    /**
     * @return boolean
     */
    public function getAbstract(): bool
    {
        return $this->abstract;
    }

    /**
     * @return $this
     */
    public function setAbstract(bool $abstract = true): static
    {
        $this->abstract = $abstract;

        return $this;
    }
}
