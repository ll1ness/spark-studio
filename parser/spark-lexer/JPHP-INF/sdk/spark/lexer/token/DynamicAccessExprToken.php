<?php
namespace spark\lexer\token;

/**
 * Class DynamicAccessExprToken
 * @package spark\lexer\token
 */
class DynamicAccessExprToken extends SimpleToken
{
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