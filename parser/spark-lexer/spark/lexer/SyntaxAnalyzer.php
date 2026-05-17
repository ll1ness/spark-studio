<?php
namespace spark\lexer;

use spark\lexer\token\ClassStmtToken;
use spark\lexer\token\FunctionStmtToken;
use spark\lexer\token\SimpleToken;
use php\lang\Environment;

/**
 * Class SyntaxAnalyzer
 * @package spark\lexer
 */
class SyntaxAnalyzer
{
    public function __construct(Environment $env, Tokenizer $tokenizer)
    {
    }

    /**
     * @return Context
     */
    public function getContext()
    {
    }

    /**
     * ...
     */
    public function reset(Environment $env, Tokenizer $tokenizer)
    {
    }

    /**
     * @return Token[]
     */
    public function getTree()
    {
    }

    /**
     * @return ClassStmtToken[]
     */
    public function getClasses()
    {
    }

    /**
     * @return FunctionStmtToken[]
     */
    public function getFunctions()
    {
    }

    /**
     * @param $expression
     * @return SimpleToken[]
     */
    public static function analyzeExpressionForDetectType($expression)
    {
    }

    /**
     * @param string $name
     * @param SimpleToken $owner
     * @param string $type CLASS, FUNCTION, CONSTANT
     * @return string
     */
    public static function getRealName($name, SimpleToken $owner, $type)
    {
    }
}