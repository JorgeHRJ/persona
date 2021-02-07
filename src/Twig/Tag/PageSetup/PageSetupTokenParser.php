<?php

namespace App\Twig\Tag\PageSetup;

use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class PageSetupTokenParser extends AbstractTokenParser
{
    /**
     * @param Token $token
     * @return PageSetupNode
     * @throws \Twig\Error\SyntaxError
     */
    public function parse(Token $token)
    {
        $stream = $this->parser->getStream();

        $value = $this->parser->getExpressionParser()->parseExpression();
        $stream->expect(Token::BLOCK_END_TYPE);

        return new PageSetupNode($value, $this->getTag(), $token->getLine(), $this->getTag());
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return 'page_setup';
    }
}
