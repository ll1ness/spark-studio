<?php
namespace spark\lexer\token;

/**
 * @package spark\lexer\token
 */
class StaticAccessExprToken extends SimpleToken
{
    /**
     * @return SimpleToken
     */
    public function getClazz()
    {
    }

    /**
     * @return SimpleToken
     */
    public function getField()
    {
    }

    /**
     * @return ExprStmtToken
     */
    public function getFieldExpr()
    {
    }
}