<?php

namespace Ray\EloquentModelGenerator\Model;

/**
 * Class VirtualMethodModel
 * @package App\CodeGenerator\Model
 */
class VirtualMethodModel extends BaseMethodModel
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * VirtualMethodModel constructor.
     * @param string $name
     * @param string|null $type
     */
    public function __construct(string $name, string $type = null)
    {
        $this->setName($name)
            ->setType($type);
    }

    /**
     * {@inheritDoc}
     */
    public function toLines(): string
    {
        $type = $this->type ?: 'void';

        return '@method ' . $type . ' ' . $this->name . '(' . $this->renderArguments() . ')';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
