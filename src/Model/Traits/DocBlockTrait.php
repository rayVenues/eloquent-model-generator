<?php

namespace Ray\EloquentModelGenerator\Model\Traits;

use Ray\EloquentModelGenerator\Model\DocBlockModel;

/**
 * Trait DocBlockTrait
 * @package App\CodeGenerator\Model\Traits
 */
trait DocBlockTrait
{
    public function __construct()
    {
        $this->docBlock = new DocBlockModel();
    }

    /**
     * @var DocBlockModel
     */
    protected DocBlockModel $docBlock;

    /**
     * @return DocBlockModel
     */
    public function getDocBlock(): DocBlockModel
    {
        return $this->docBlock;
    }

    /**
     * @param DocBlockModel $docBlock
     *
     * @return $this
     */
    public function setDocBlock(DocBlockModel $docBlock): static
    {
        $this->docBlock = $docBlock;

        return $this;
    }
}
