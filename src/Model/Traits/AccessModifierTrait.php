<?php

namespace Ray\EloquentModelGenerator\Model\Traits;

/**
 * Trait AccessModifierTrait
 * @package App\CodeGenerator\Model\Traits
 */
trait AccessModifierTrait
{
    /**
     * @var string
     */
    protected string $access;

    /**
     * @return string
     */
    public function getAccess(): string
    {
        return $this->access;
    }

    /**
     * @param string $access
     *
     * @return $this
     */
    public function setAccess(string $access): static
    {
        if (!in_array($access, ['private', 'protected', 'public'])) {
            $access = 'public';
        }

        $this->access = $access;

        return $this;
    }
}
