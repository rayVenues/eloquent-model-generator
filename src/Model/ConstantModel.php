<?php

namespace Ray\EloquentModelGenerator\Model;

use Ray\EloquentModelGenerator\Model\Traits\ValueTrait;
use Ray\EloquentModelGenerator\RenderableModel;

/**
 * Class PHPClassConstant
 * @package App\CodeGenerator\Model
 */
class ConstantModel extends RenderableModel
{
    use ValueTrait;

    /**
     * @var string
     */
    protected string $name;

    /**
     * PHPClassConstant constructor.
     * @param string $name
     * @param mixed $value
     */
    public function __construct(string $name, mixed $value)
    {
        $this->setName($name);
        $this->setValue($value);
    }

    /**
     * {@inheritDoc}
     */
    public function toLines(): string
    {
        $value = $this->renderValue();

        return sprintf('const %s = %s;', $this->name, $value);
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
