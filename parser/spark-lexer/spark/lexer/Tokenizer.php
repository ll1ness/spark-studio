<?php
namespace spark\lexer;
use spark\lexer\token\SimpleToken;
use php\io\IOException;

/**
 * Class Tokenizer
 * @package spark\lexer
 */
class Tokenizer
{
    /**
     * @param Context $context
     * @throws IOException
     */
    public function __construct(Context $context)
    {
    }

    /**
     * @return Context
     */
    public function getContext()
    {
    }

    /**
     * @return SimpleToken
     */
    public function nextToken()
    {
    }

    /**
     * @return SimpleToken[]
     */
    public function fetchAll()
    {
    }

    /**
     * ...
     */
    public function reset()
    {
    }
}