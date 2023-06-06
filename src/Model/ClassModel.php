<?php

namespace Ray\EloquentModelGenerator\Model;

use Ray\EloquentModelGenerator\Exception\GeneratorException;
use Ray\EloquentModelGenerator\Model\Traits\DocBlockTrait;
use Ray\EloquentModelGenerator\RenderableModel;

/**
 * Class ClassModel
 * @package App\CodeGenerator\Model
 */
class ClassModel extends RenderableModel
{
    use DocBlockTrait;
    protected DocBlockModel   $docBlock;
    public function __construct(
        protected ?ClassNameModel $name = null,
        protected ?NamespaceModel $namespace = null,
        protected ?array          $uses = null,
        protected array           $traits = [],
        protected array           $constants = [],
        protected array           $properties = [],
        protected array           $methods = [],
        )
    {
        $this->docBlock = new DocBlockModel();
    }

    /**
     * {@inheritDoc}
     * @throws GeneratorException
     */
    public function toLines(): string|array
    {
        $lines = [];
        $lines[] = $this->ln('<?php');
        if ($this->namespace !== null) {
            $lines[] = $this->ln($this->namespace->render());
        }
        if (count($this->uses) > 0) {
            $lines[] = $this->renderArrayLn($this->uses);
        }
        $this->prepareDocBlock();
        $lines[] = $this->docBlock->render();
        $lines[] = $this->name->render();
        if (count($this->traits) > 0) {
            $lines[] = $this->renderArrayLn($this->traits, 4);
        }
        if (count($this->constants) > 0) {
            $lines[] = $this->renderArrayLn($this->constants, 4);
        }
        $this->processProperties($lines);
        $this->processMethods($lines);
        /**
         * Fix the bug with empty line before closing bracket
         */
        $lines[count($lines) - 1] = rtrim($lines[count($lines) - 1]);
        $lines[] = $this->ln('}');

        return $lines;
    }

    /**
     * @return ClassNameModel
     */
    public function getName(): ClassNameModel
    {
        return $this->name;
    }

    /**
     * @param ClassNameModel $name
     *
     * @return $this
     */
    public function setName(ClassNameModel $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return NamespaceModel
     */
    public function getNamespace(): NamespaceModel
    {
        return $this->namespace;
    }

    /**
     * @param NamespaceModel $namespace
     *
     * @return $this
     */
    public function setNamespace(NamespaceModel $namespace): static
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @return UseClassModel|array
     */
    public function getUses(): ?UseClassModel
    {
        return $this->uses;
    }

    /**
     * @param UseClassModel $use
     *
     * @return $this
     */
    public function addUses(UseClassModel $use): static
    {
        $this->uses[] = $use;

        return $this;
    }

    /**
     * @return UseTraitModel[]
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * @param UseTraitModel $trait
     *
     * @return $this
     */
    public function addTrait(UseTraitModel $trait): static
    {
        $this->traits[] = $trait;

        return $this;
    }

    /**
     * @return ConstantModel[]
     */
    public function getConstants(): array
    {
        return $this->constants;
    }

    /**
     * @param ConstantModel $constant
     *
     * @return $this
     */
    public function addConstant(ConstantModel $constant): static
    {
        $this->constants[] = $constant;

        return $this;
    }

    /**
     * @return BasePropertyModel[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param BasePropertyModel $property
     *
     * @return $this
     */
    public function addProperty(BasePropertyModel $property): static
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * @return BaseMethodModel[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param BaseMethodModel $method
     *
     * @return $this
     */
    public function addMethod(BaseMethodModel $method): static
    {
        $this->methods[] = $method;

        return $this;
    }

    /**
     * Convert virtual properties and methods to DocBlock content
     */
    protected function prepareDocBlock(): void
    {
        $content = [];

        foreach ($this->properties as $property) {
            if ($property instanceof VirtualPropertyModel) {
                $content[] = $property->toLines();
            }
        }

        foreach ($this->methods as $method) {
            if ($method instanceof VirtualMethodModel) {
                $content[] = $method->toLines();
            }
        }

        if ($content) {
            $this->docBlock->addContent($content);
        }
    }

    /**
     * @param array $lines
     * @throws GeneratorException
     */
    protected function processProperties(array &$lines): void
    {
        $properties = array_filter($this->properties, function ($property) {
            return !$property instanceof VirtualPropertyModel;
        });
        if (count($properties) > 0) {
            $lines[] = $this->renderArrayLn($properties, 4, str_repeat(PHP_EOL, 2));
        }
    }

    /**
     * @param array $lines
     * @throws GeneratorException
     */
    protected function processMethods(array &$lines): void
    {
        $methods = array_filter($this->methods, function ($method) {
            return !$method instanceof VirtualMethodModel;
        });
        if (count($methods) > 0) {
            $lines[] = $this->renderArray($methods, 4, str_repeat(PHP_EOL, 2));
        }
    }
}
