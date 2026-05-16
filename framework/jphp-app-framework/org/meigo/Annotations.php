<?php
namespace org\meigo;

use php\lib\str;
use php\util\Regex;

/**
 * Class Annotations
 */
class Annotations
{
    private function __construct()
    {
    }

    public static function getContent(string $comment, string $lang = null): string
    {
        $content = str::trim((new Regex('^\\@.*', Regex::DOTALL | Regex::MULTILINE | Regex::UNIX_LINES, $comment))->replaceGroup(0, ''));

        if (!$lang) return $content;

        $lang = str::lower($lang);
        $c_lang = 'def';

        $result = [];
        foreach (str::lines($content) as $line) {
            if (str::startsWith($line, '--') && str::endsWith($line, '--')) {
                $c_lang = str::lower(str::sub($line, 2, str::length($line) - 2));
                continue;
            }

            $result[$c_lang][] = $line;
        }

        if (!$result[$lang]) {
            foreach ($result as $value) {
                if ($value) {
                    return str::join((array)$value, "\n");
                }
            }

            return "";
        } else {
            return str::join((array)$result[$lang], "\n");
        }
    }

    /**
     * @param string $annotationName
     * @param string $comment
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $annotationName, string $comment, $default = null)
    {
        if (!$comment) {
            return $default;
        }

        $result = static::parse($comment)[$annotationName];

        if ($result && is_array($default) && !is_array($result)) {
            return [$result];
        }

        return $result ?? $default;
    }

    /**
     * @param string $comment
     * @param callable|null $callback
     * @return array
     */
    public static function parse(string $comment, callable $callback = null): array
    {
        $regex = new Regex('\\@([a-z0-9\\-\\_]+)([ ]+(.+))?', 'im', $comment);

        $result = [];

        while ($regex->find()) {
            $groups = $regex->groups();

            $name = $groups[1];
            $value = $groups[3] ?: true;

            if ($callback) {
                if (!$callback($name, $value)) {
                    break;
                }
            }

            if ($result[$name]) {
                if (!is_array($result[$name])) {
                    $result[$name] = [$result[$name]];
                }

                $result[$name][] = $value;
            } else {
                $result[$name] = $value;
            }
        }

        return $result;
    }
}