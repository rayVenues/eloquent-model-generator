<?php

namespace Ray\EloquentModelGenerator\Model\Traits;

use Ray\EloquentModelGenerator\Exception\GeneratorException;
use Ray\EloquentModelGenerator\Exception\ValidationException;

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
     * @param string $classType
     * @return $this
     * @throws GeneratorException
     */
    public function setClassType(string $classType): static
    {
        if (! in_array($classType, ['final', 'abstract', ''])) {
            throw new GeneratorException(sprintf('Class type must be either final or abstract, %s given.', $classType));
        }
        $this->classType = $classType;

        return $this;
    }
}
