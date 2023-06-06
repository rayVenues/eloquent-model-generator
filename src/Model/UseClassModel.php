<?php

namespace Ray\EloquentModelGenerator\Model;

use Ray\EloquentModelGenerator\RenderableModel;

/**
 * Class UseClassModel
 * @package App\CodeGenerator\Model
 */
class UseClassModel extends RenderableModel
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * PHPClassUse constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
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
