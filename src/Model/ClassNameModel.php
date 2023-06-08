<?php

namespace Ray\EloquentModelGenerator\Model;

use Exception;
use Ray\EloquentModelGenerator\Model\Traits\ClassTypeModifierTrait;
use Ray\EloquentModelGenerator\RenderableModel;

/**
 * Class Name
 * @package App\CodeGenerator\Model
 */
class ClassNameModel extends RenderableModel
{
    use ClassTypeModifierTrait;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $extends = '';

    /**
     * @var array
     */
    protected array $implements = [];

    /**
     * PHPClassName constructor.
     * @param string $name
     * @param string|null $extends
     * @param string|null $classType
     * @throws Exception
     */
    public function __construct(string $name, string $extends = null, string $classType = null)
    {
        $this->setName($name)
            ->setExtends($extends)
            ->setClassType($classType);
    }

    /**
     * {@inheritDoc}
     */
    public function toLines(): string | array
    {
        $lines = [];

        $name = trim($this->getClassType() . ' class ' . $this->name);

        $name .= sprintf(' extends %s', $this->extends);
        if (count($this->implements) > 0) {
            $name .= sprintf(' implements %s', implode(', ', $this->implements));
        }

        $lines[] = $name;
        $lines[] = '{';

        return $lines;
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
    public function getExtends(): string
    {
        return $this->extends;
    }

    /**
     * @param string $extends
     *
     * @return $this
     */
    public function setExtends(string $extends): static
    {
        $this->extends = $extends;

        return $this;
    }

    /**
     * @return array
     */
    public function getImplements(): array
    {
        return $this->implements;
    }

    /**
     * @param string $implements
     *
     * @return $this
     */
    public function addImplements(string $implements): static
    {
        $this->implements[] = $implements;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function validate(): bool
    {
        return parent::validate();
    }
}
