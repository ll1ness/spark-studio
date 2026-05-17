<?php
namespace spark\lexer\inspector\entry;

/**
 * Class ConstantEntry
 * @package spark\lexer\inspector\entry
 */
class ConstantEntry extends AbstractEntry
{
    public $name;
    public $value;

    public $modifier = 'PUBLIC';
}