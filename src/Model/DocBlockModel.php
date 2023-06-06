<?php

namespace Ray\EloquentModelGenerator\Model;

use Ray\EloquentModelGenerator\RenderableModel;

/**
 * Class DocBlockModel
 * @package App\CodeGenerator\Model
 */
class DocBlockModel extends RenderableModel
{
    /**
     * @var array
     */
    protected array $content = [];

    /**
     * DocBlockModel constructor.
     */
    public function __construct()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            $this->addContent($arg);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function toLines(): array
    {
        $lines = [];
        $lines[] = '/**';
        if ($this->content) {
            foreach ($this->content as $item) {
                $lines[] = sprintf(' * %s', $item);
            }
        } else {
            $lines[] = ' *';
        }
        $lines[] = ' */';

        return $lines;
    }

    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @param array|string $content
     *
     * @return $this
     */
    public function addContent(array | string $content): static
    {
        if (is_array($content)) {
            foreach ($content as $item) {
                $this->addContent($item);
            }
        } else {
            $this->content[] = $content;
        }

        return $this;
    }
}
