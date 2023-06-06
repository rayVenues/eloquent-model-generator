<?php

namespace Ray\EloquentModelGenerator\Model;

use Ray\EloquentModelGenerator\RenderableModel;

/**
 * Class PHPClassTrait
 * @package App\CodeGenerator\Model
 */
class UseTraitModel extends RenderableModel
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * PHPClassTrait constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function toLines(): string
    {
        return sprintf('use %s;', $this->name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
