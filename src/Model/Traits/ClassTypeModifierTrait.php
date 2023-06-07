<?php

namespace Ray\EloquentModelGenerator\Model\Traits;

/**
 * Trait AbstractModifierTrait
 * @package App\CodeGenerator\Model\Traits
 */
trait ClassTypeModifierTrait
{
    /**
     * @var string;
     */
    protected string $classType = '';

    /**
     * @return string
     */
    public function getClassType(): string
    {
        return $this->classType;
    }

    /**
     * @return $this
     */
    public function setClassType(string $classType): static
    {
        $this->classType = $classType;

        return $this;
    }
}
