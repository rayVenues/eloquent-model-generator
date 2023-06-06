<?php

namespace Ray\EloquentModelGenerator;

use Ray\EloquentModelGenerator\Exception\GeneratorException;

/**
 * Class RenderableModel
 * @package App\CodeGenerator
 */
abstract class RenderableModel implements RenderableInterface, LineableInterface
{
    /**
     * {@inheritDoc}
     */
    final public function render(int $indent = 0, string $delimiter = PHP_EOL): string
    {
        $this->validate();
        $lines = $this->toLines();

        if (!is_array($lines)) {
            $lines = [$lines];
        }

        if ($indent > 0) {
            array_walk($lines, function (&$item) use ($indent) {
                $item = str_repeat(' ', $indent) . $item;
            });
        }

        return implode($delimiter, $lines);
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        return true;
    }

    /**
     * @param RenderableInterface[] $array
     * @param int $indent
     * @param string $delimiter
     * @return string
     * @throws GeneratorException
     */
    protected function renderArrayLn(array $array, int $indent = 0, string $delimiter = PHP_EOL): string
    {
        return $this->ln($this->renderArray($array, $indent, $delimiter));
    }

    /**
     * @param RenderableInterface[] $array
     * @param int $indent
     * @param string $delimiter
     * @return string
     * @throws GeneratorException
     */
    protected function renderArray(array $array, int $indent = 0, string $delimiter = PHP_EOL): string
    {
        $lines = [];
        foreach ($array as $item) {
            if (!$item instanceof RenderableInterface) {
                throw new GeneratorException('Invalid item type');
            }

            $lines[] = $item->render($indent);
        }

        return implode($delimiter, $lines);
    }

    /**
     * @param string $line
     * @return string
     */
    protected function ln(string $line): string
    {
        return $line . PHP_EOL;
    }
}
