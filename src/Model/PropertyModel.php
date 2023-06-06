<?php

namespace Ray\EloquentModelGenerator\Model;

use Ray\EloquentModelGenerator\Model\Traits\AccessModifierTrait;
use Ray\EloquentModelGenerator\Model\Traits\DocBlockTrait;
use Ray\EloquentModelGenerator\Model\Traits\StaticModifierTrait;
use Ray\EloquentModelGenerator\Model\Traits\ValueTrait;

/**

 * Class PHPClassProperty
 * @package App\CodeGenerator\Model
 */
class PropertyModel extends BasePropertyModel
{
    use AccessModifierTrait;
    use DocBlockTrait;
    use StaticModifierTrait;
    use ValueTrait;

    /**
     * @var string
     */
    protected string $name;

    /**
     * PropertyModel constructor.
     * @param string $name
     * @param string $access
     * @param mixed|null $value
     */
    public function __construct(string $name, string $access = 'public', mixed $value = null)
    {
        $this->setName($name)
            ->setAccess($access)
            ->setValue($value);
    }

    /**
     * {@inheritDoc}
     */
    public function toLines(): string|array
    {
        $lines = [];
        $lines = array_merge($lines, $this->docBlock->toLines());

        $property = $this->access . ' ';
        if ($this->static) {
            $property .= 'static ';
        }
        $property .= '$' . $this->name;

        if ($this->value !== null) {
            $value = $this->renderValue();
            if ($value !== null) {
                $property .= sprintf(' = %s', $this->renderValue());
            }
        }
        $property .= ';';
        $lines[] = $property;

        return $lines;
    }
}
