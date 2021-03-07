<?php

namespace App\Twig\Tag\Meta;

use Twig\Compiler;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;

class MetaNode extends Node
{
    public function __construct(AbstractExpression $value, string $name, int $line, string $tag = null)
    {
        parent::__construct(['value' => $value], ['name' => $name], $line, $tag);
    }

    public function compile(Compiler $compiler): void
    {
        $compiler
            ->raw('$this->env->getExtension(\'App\Twig\Extension\MetaExtension\')->configure(')
            ->subcompile($this->getNode('value'))
            ->raw(');');
    }
}
