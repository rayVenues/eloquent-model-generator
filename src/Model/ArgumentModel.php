<?php

namespace Ray\EloquentModelGenerator\Model;

use Ray\EloquentModelGenerator\RenderableModel;

/**
 * Class Argument
 * @package App\CodeGenerator\Model
 */
class ArgumentModel extends RenderableModel
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var mixed
     */
    protected mixed $default;

    /**
     * ArgumentModel constructor.
     * @param string $name
     * @param string|null $type
     * @param mixed|null $default
     */
    public function __construct(string $name, $type = null, $default = null)
    {
        $this->setName($name)
            ->setType($type)
            ->setDefault($default);
    }

    /**
     * {@inheritDoc}
     */
    public function toLines(): string
    {
        if ($this->type !== null) {
            return $this->type . ' $' . $this->name;
        } else {
            return '$' . $this->name;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }
}
