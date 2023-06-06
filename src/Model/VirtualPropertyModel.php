<?php

namespace Ray\EloquentModelGenerator\Model;

use Ray\EloquentModelGenerator\Exception\ValidationException;

/**
 * Class VirtualPropertyModel
 * @package App\CodeGenerator\Model
 */
class VirtualPropertyModel extends BasePropertyModel
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @var boolean
     */
    protected bool $readable = true;

    /**
     * @bool
     */
    protected bool $writable = true;

    /**
     * VirtualPropertyModel constructor.
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
        $property = '@property';
        if (!$this->readable) {
            $property .= '-write';
        } elseif (!$this->writable) {
            $property .= '-read';
        }

        $property .= ' ' . $this->type;

        return $property . ' $' . $this->name;
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

    /**
     * @return boolean
     */
    public function isReadable(): bool
    {
        return $this->readable;
    }

    /**
     * @param boolean $readable
     *
     * @return $this
     */
    public function setReadable(bool $readable = true): static
    {
        $this->readable = $readable;

        return $this;
    }

    /**
     * @return bool
     */
    public function getWritable(): bool
    {
        return $this->writable;
    }

    /**
     * @param bool $writable
     *
     * @return $this
     */
    public function setWritable(bool $writable = true): static
    {
        $this->writable = $writable;

        return $this;
    }

    /**
     * {@inheritDoc}
     * @throws ValidationException
     */
    protected function validate(): bool
    {
        if (!$this->readable && !$this->writable) {
            throw new ValidationException('Property cannot be unreadable and un-writable at the same time');
        }
        return true;
    }
}
