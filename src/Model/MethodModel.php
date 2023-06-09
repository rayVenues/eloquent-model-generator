<?php

namespace Ray\EloquentModelGenerator\Model;

use Ray\EloquentModelGenerator\Exception\ValidationException;
use Ray\EloquentModelGenerator\Model\Traits\AbstractMethodModifierTrait;
use Ray\EloquentModelGenerator\Model\Traits\AccessModifierTrait;
use Ray\EloquentModelGenerator\Model\Traits\DocBlockTrait;
use Ray\EloquentModelGenerator\Model\Traits\FinalMethodModifierTrait;
use Ray\EloquentModelGenerator\Model\Traits\StaticModifierTrait;

/**
 * Class PHPClassMethod
 * @package App\CodeGenerator\Model
 */
class MethodModel extends BaseMethodModel
{
    use AbstractMethodModifierTrait;
    use AccessModifierTrait;
    use DocBlockTrait;
    use FinalMethodModifierTrait;
    use StaticModifierTrait;

    /**
     * @var string
     */
    protected string $body;

    protected string $returnType;

    /**
     * MethodModel constructor.
     * @param string $name
     * @param string $access
     */
    public function __construct(string $name, string $access = 'public')
    {
        $this->setName($name)
            ->setAccess($access);
    }

    /**
     * {@inheritDoc}
     */
    public function toLines(): string | array
    {
        $lines = [];
        $lines = array_merge($lines, $this->docBlock->toLines());

        $function = '';
        if ($this->final) {
            $function .= 'final ';
        }
        if ($this->abstract) {
            $function .= 'abstract ';
        }
        $function .= $this->access . ' ';
        if ($this->static) {
            $function .= 'static ';
        }

        $function .= 'function ' . $this->name . '(' . $this->renderArguments() . ')' . $this->getReturnType();

        if ($this->abstract) {
            $function .= ';';
        }

        $lines[] = $function;
        if (! $this->abstract) {
            $lines[] = '{';
            if ($this->body) {
                $lines[] = sprintf('    %s', $this->body); // TODO: make body render-able
            }
            $lines[] = '}';
        }

        return $lines;
    }

    public function getReturnType(): string
    {
        return $this->returnType;
    }

    public function setReturnType($returnType): static
    {
        $this->returnType = $returnType;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    /**
     * {@inheritDoc}
     * @throws ValidationException
     */
    protected function validate(): bool
    {
        if ($this->abstract and ($this->final or $this->static)) {
            throw new ValidationException('Entity cannot be abstract and final or static at the same time');
        }

        return parent::validate();
    }
}
