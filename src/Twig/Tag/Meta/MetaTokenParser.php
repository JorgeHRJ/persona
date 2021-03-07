<?php

namespace App\Twig\Tag\Meta;

use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class MetaTokenParser extends AbstractTokenParser
{
    /**
     * @param Token $token
     * @return MetaNode
     * @throws \Twig\Error\SyntaxError
     */
    public function parse(Token $token): MetaNode
    {
        $stream = $this->parser->getStream();

        $value = $this->parser->getExpressionParser()->parseExpression();
        $stream->expect(Token::BLOCK_END_TYPE);

        return new MetaNode($value, $this->getTag(), $token->getLine(), $this->getTag());
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return 'meta';
    }
}
