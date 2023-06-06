<?php

namespace Ray\EloquentModelGenerator\Model\Traits;

/**
 * Trait PHPValueTrait
 * @package App\CodeGenerator\Model\Traits
 */
trait ValueTrait
{
    /**
     * @var mixed
     */
    protected mixed $value;

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    protected function renderValue(): ?string
    {
        return $this->renderTyped($this->value);
    }

    /**
     * @param mixed $value
     * @return string|null
     */
    protected function renderTyped(mixed $value): ?string
    {
        $type = gettype($value);

        switch ($type) {
            case 'boolean':
                $value = $value ? 'true' : 'false';

                break;
            case 'int':
            case 'integer':
                // do nothing

                break;
            case 'string':
                $value = sprintf('\'%s\'', addslashes($value));

                break;
            case 'array':
                $parts = [];
                foreach ($value as $item) {
                    $parts[] = $this->renderTyped($item);
                }
                $value = '[' . implode(', ', $parts) . ']';

                break;
            default:
                $value = null; // TODO: how to render null explicitly?
        }

        return $value;
    }
}
