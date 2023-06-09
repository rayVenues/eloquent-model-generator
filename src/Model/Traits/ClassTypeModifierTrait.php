<?php

namespace Ray\EloquentModelGenerator\Model\Traits;

/**
 * Trait AbstractMethodModifierTrait
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
     * @throws \Exception
     */
    public function setClassType(string $classType): static
    {
        if (! in_array($classType, ['final', 'abstract', ''])) {
            throw new \InvalidArgumentException('Class type must be either final or abstract');
        }
        $this->classType = $classType;

        return $this;
    }
}
