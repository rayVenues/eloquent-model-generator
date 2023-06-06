<?php

namespace Ray\EloquentModelGenerator\Model;

use Ray\EloquentModelGenerator\RenderableModel;

/**
 * Class PHPClassNamespace
 * @package App\CodeGenerator\Model
 */
class NamespaceModel extends RenderableModel
{
    /**
     * @var string
     */
    protected string $namespace;

    /**
     * PHPClassNamespace constructor.
     * @param string $namespace
     */
    public function __construct(string $namespace)
    {
        $this->setNamespace($namespace);
    }

    /**
     * {@inheritDoc}
     */
    public function toLines(): string
    {
        return sprintf('namespace %s;', $this->namespace);
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     *
     * @return $this
     */
    public function setNamespace(string $namespace): static
    {
        $this->namespace = $namespace;

        return $this;
    }
}
