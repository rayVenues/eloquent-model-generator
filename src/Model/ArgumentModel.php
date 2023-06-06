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
    public function __construct(string $name, string $type = null, mixed $default = null)
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
      return $this->type . ' $' . $this->name;
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

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return $this
     */
    public function setType(mixed $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefault(): mixed
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     *
     * @return $this
     */
    public function setDefault(mixed $default): static
    {
        $this->default = $default;

        return $this;
    }
}
