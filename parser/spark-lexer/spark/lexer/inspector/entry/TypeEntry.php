<?php
namespace spark\lexer\inspector\entry;

use spark\lexer\inspector\entry\ConstantEntry;
use spark\lexer\inspector\entry\ExtendTypeEntry;
use spark\lexer\inspector\entry\MethodEntry;
use spark\lexer\inspector\entry\TypePropertyEntry;

class TypeEntry extends AbstractEntry
{
    public $name = '';
    public $fulledName = '';
    public $namespace = '';

    public $packages = [];

    public $final = false;
    public $abstract = false;

    public $kind = 'CLASS';

    /**
     * @var ExtendTypeEntry[]
     */
    public $extends = [];

    /**
     * @var TypePropertyEntry[]
     */
    public $properties = [];

    /**
     * @var MethodEntry[]
     */
    public $methods = [];

    /**
     * @var ConstantEntry[]
     */
    public $constants = [];

    /**
     * @var array
     */
    public $data = [];
}