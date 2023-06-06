<?php

namespace Ray\EloquentModelGenerator\Model\Traits;

/**
 * Trait FinalModifierTrait
 * @package App\CodeGenerator\Model\Traits
 */
trait FinalModifierTrait
{
    public function __construct()
    {
        $this->final = false;
    }

    /**
     * @var boolean
     */
    protected bool $final = false;

    /**
     * @return boolean
     */
    public function isFinal(): bool
    {
        return $this->final;
    }

    /**
     * @param boolean $final
     *
     * @return $this
     */
    public function setFinal(bool $final = true): static
    {
        $this->final = boolval($final);

        return $this;
    }
}
