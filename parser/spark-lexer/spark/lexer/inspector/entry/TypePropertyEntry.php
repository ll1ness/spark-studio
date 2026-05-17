<?php
namespace spark\lexer\inspector\entry;

/**
 * Class TypePropertyEntry
 * @package spark\lexer\inspector\entry
 */
class TypePropertyEntry extends AbstractEntry
{
    public $name;
    public $modifier = 'PUBLIC';
    public $value;

    public $static = false;

    /**
     * @var array
     */
    public $data = [];
}