<?php
namespace spark\lexer\inspector\entry;

/**
 * Class MethodEntry
 * @package spark\lexer\inspector\entry
 */
class MethodEntry extends FunctionEntry
{
    public $final = false;
    public $static = false;
    public $abstract = false;
    public $interfacable = false;
}