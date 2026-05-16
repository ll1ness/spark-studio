<?php

/**
 * Calculates the crc32 polynomial of a string.
 * --RU--
 * Вычисляет полином CRC32 строки.
 *
 * @param string $string
 * @return int
 */
function crc32($string)
{
}

/**
 * Split a string by string.
 * --RU--
 * Разбивает строку с помощью разделителя.
 *
 * @param string $delimiter
 * @param string $string
 * @param int $limit
 * @return array
 */
function explode($delimiter, $string, $limit = PHP_INT_MAX)
{
}

/**
 * Join array elements with a string.
 * --RU--
 * Объединяет элементы массива в строку.
 *
 * @param string $glue
 * @param array $pieces
 */
function implode($glue, array $pieces)
{
}

/**
 * Strip whitespace (or other characters) from the beginning and end of a string.
 * --RU--
 * Удаляет пробелы (или другие символы) из начала и конца строки.
 *
 * @param string $string
 * @param string $character_mask
 * @return string
 */
function trim($string, $character_mask = " \t\n\r\0\x0B")
{
}

/**
 * Strip whitespace (or other characters) from the beginning of a string.
 * --RU--
 * Удаляет пробелы (или другие символы) из начала строки.
 *
 * @param string $string
 * @param string $character_mask
 * @return string
 */
function ltrim($string, $character_mask = " \t\n\r\0\x0B")
{
}

/**
 * Strip whitespace (or other characters) from the ending of a string.
 * --RU--
 * Удаляет пробелы (или другие символы) из конца строки.
 *
 * @param string $string
 * @param string $character_mask
 * @return string
 */
function rtrim($string, $character_mask = " \t\n\r\0\x0B")
{
}

/**
 * Calculate md5 hash of a string.
 * --RU--
 * Вычисляет хеш md5 строки.
 * Alternative: \php\lib\str::hash($string, 'MD5')
 *
 * @param string $string
 * @return string
 */
function md5($string)
{
}

/**
 * Calculate md5 hash of a file.
 * --RU--
 * Вычисляет хеш md5 файла.
 * Alternative: \php\io\File::of($filename)->hash('MD5')
 *
 * @param string $filename
 * @return string
 */
function md5_file($filename)
{
}

/**
 * Calculate sha1 hash of a string.
 * --RU--
 * Вычисляет хеш sha1 строки.
 * Alternative: \php\lib\str::hash($string, 'SHA-1')
 *
 * @param string $string
 * @return string
 */
function sha1($string)
{
}

/**
 * Calculate sha1 hash of a file.
 * --RU--
 * Вычисляет хеш sha1 файла.
 * Alternative: \php\io\File::of($filename)->hash('SHA-1')
 *
 * @param string $filename
 * @return string
 */
function sha1_file($filename)
{
}

/**
 * Return string length.
 * --RU--
 * Возвращает длину строки.
 *
 * @param string $string
 * @return int
 */
function strlen($string)
{
}

/**
 * Find the position of the first occurrence of a substring in a string.
 * --RU--
 * Находит позицию первого вхождения подстроки в строку.
 * Alternative: \php\lib\str::pos($string, $search, $offset)
 *
 * @param string $string
 * @param string $search
 * @param int $offset
 * @return bool|int
 */
function strpos($string, $search, $offset = 0)
{
}

/**
 * Find the position of the first occurrence of a case-insensitive substring in a string.
 * --RU--
 * Находит позицию первого вхождения подстроки в строку без учета регистра.
 * Alternative: \php\lib\str::posIgnoreCase($string, $search, $offset)
 *
 * @param string $string
 * @param string $search
 * @param int $offset
 * @return bool|int
 */
function stripos($string, $search, $offset = 0)
{
}

/**
 * Find the position of the last occurrence of a substring in a string.
 * --RU--
 * Находит позицию последнего вхождения подстроки в строку.
 * Alternative: \php\lib\str::lastPos($string, $search, $offset)
 *
 * @param string $string
 * @param string $search
 * @param int $offset
 * @return bool|int
 */
function strrpos($string, $search, $offset = 0)
{
}

/**
 * Find the position of the last occurrence of a case-insensitive substring in a string.
 * --RU--
 * Находит позицию последнего вхождения подстроки в строку без учета регистра.
 * Alternative: \php\lib\str::lastPosIgnoreCase($string, $search, $offset)
 *
 * @param string $string
 * @param string $search
 * @param int $offset
 * @return bool|int
 */
function strripos($string, $search, $offset = 0)
{
}

/**
 * Return part of a string.
 * --RU--
 * Возвращает часть строки.
 * Alternative: \php\lib\str::sub($string, $startIndex, $endIndex)
 *
 * @param int $string
 * @param int $start
 * @param int $length (optional)
 * @return string
 */
function substr($string, $start, $length)
{
}

/**
 * Replace all occurrences of the search string with the replacement string.
 * --RU--
 * Заменяет все вхождения строки поиска на строку замены.
 * Alternative: \php\lib\str::replace($string, $search, $replace).
 *
 * @param array|string $search
 * @param array|string $replace
 * @param string $string
 * @param mixed $count
 * @return string
 */
function str_replace($search, $replace, $string, &$count = null)
{
}

/**
 * Case-insensitive version of str_replace().
 * --RU--
 * Версия str_replace() без учета регистра.
 *
 * @param array|string $search
 * @param array|string $replace
 * @param string $string
 * @param mixed $count
 * @return string
 */
function str_ireplace($search, $replace, $string, &$count = null)
{
}

/**
 * Convert the first character of each word to uppercase.
 * --RU--
 * Преобразует первый символ каждого слова в верхний регистр.
 *
 * @param string $string
 * @param string $delimiters
 * @return string
 */
function ucwords($string, $delimiters = ' \t\r\n\f\v')
{
}

/**
 * Make a string's first character uppercase.
 * --RU--
 * Преобразует первый символ строки в верхний регистр.
 *
 * @param string $string
 * @return string
 */
function ucfirst($string)
{
}

/**
 * Make a string's first character lowercase.
 * --RU--
 * Преобразует первый символ строки в нижний регистр.
 *
 * @param string $string
 * @return string
 */
function lcfirst($string)
{
}

/**
 * Make a string lowercase.
 * --RU--
 * Преобразует строку в нижний регистр.
 *
 * @param string $string
 * @return string
 */
function strtolower($string)
{
}

/**
 * Make a string uppercase.
 * --RU--
 * Преобразует строку в верхний регистр.
 *
 * @param string $string
 * @return string
 */
function strtoupper($string)
{
}

/**
 * Reverse a string.
 * --RU--
 * Переворачивает строку.
 *
 * @param string $string
 * @return string
 */
function strrev($string)
{
}

/**
 * Repeat a string.
 * --RU--
 * Повторяет строку указанное количество раз.
 *
 * @param string $string
 * @param int $multiplier
 * @return string
 */
function str_repeat($string, $multiplier)
{
}

/**
 * Randomly shuffle a string.
 * --RU--
 * Перемешивает строку случайным образом.
 *
 * @param string $string
 * @return string
 */
function str_shuffle($string)
{
}

/**
 * Convert a string to an array.
 * --RU--
 * Преобразует строку в массив.
 *
 * @param string $string
 * @param int $split_length
 * @return array
 */
function str_split($string, $split_length)
{
}

/**
 * Count the number of words in a string.
 * --RU--
 * Считает количество слов в строке.
 *
 * @param string $string
 * @param int $format
 * @param string $charList
 * @return array|int
 */
function str_word_count($string, $format = 0, $charList = null)
{
}

/**
 * Pad a string to a certain length with another string.
 * --RU--
 * Дополняет строку до определенной длины другой строкой.
 *
 * @param string $string
 * @param int $pad_length
 * @param string $pad_string
 * @param int $pad_type
 * @return string
 */
function str_pad($string, $pad_length, $pad_string = " ", $pad_type = STR_PAD_RIGHT)
{
}

/**
 * Strip HTML and PHP tags from a string.
 * --RU--
 * Удаляет HTML и PHP теги из строки.
 *
 * @param string $string
 * @param string $allowable_tags
 * @return string
 */
function strip_tags($string, $allowable_tags = null)
{
}

/**
 * Find the first occurrence of a string.
 * --RU--
 * Находит первое вхождение подстроки.
 *
 * @param string $string
 * @param string|int $needle
 * @param bool $before_needle
 * @return string
 */
function strstr($string, $needle, $before_needle = false)
{
}

/**
 * Binary safe comparison of two strings from an offset.
 * --RU--
 * Бинарное безопасное сравнение двух строк с заданного смещения.
 *
 * @param string $main_str
 * @param string $str
 * @param int $offset
 * @param int $length
 * @param bool $case_insensitivity
 * @return int
 */
function substr_compare($main_str, $str, $offset, $length = null, $case_insensitivity = false)
{
}

/**
 * Count the number of substring occurrences.
 * --RU--
 * Считает количество вхождений подстроки.
 *
 * @param string $string
 * @param string $needle
 * @param int $offset
 * @param int $length
 * @return int
 */
function substr_count($string, $needle, $offset = 0, $length = null)
{
}

/**
 * Replace text within a portion of a string.
 * --RU--
 * Заменяет часть строки.
 *
 * @param string|array $string
 * @param string|array $replacement
 * @param int $start
 * @param int $length
 * @return string|array
 */
function substr_replace($string, $replacement, $start, $length = null)
{
}

/**
 * Convert all applicable characters to HTML entities.
 * --RU--
 * Преобразует все подходящие символы в HTML сущности.
 *
 * @param string $text
 * @param int $flags
 * @param string $encoding
 * @param bool $double_encode
 * @return string
 */
function htmlentities($text, $flags = ENT_COMPAT | ENT_HTML401, $encoding = 'UTF-8', $double_encode = true)
{
}

/**
 * Convert special characters to HTML entities.
 * --RU--
 * Преобразует специальные символы в HTML сущности.
 *
 * @param string $text
 * @param int $flags
 * @param string $encoding
 * @param bool $double_encode
 * @return string
 */
function htmlspecialchars($text, $flags = ENT_COMPAT | ENT_HTML401, $encoding = 'UTF-8', $double_encode = true)
{
}

/**
 * Convert all HTML entities to their applicable characters.
 * --RU--
 * Преобразует все HTML сущности в соответствующие символы.
 *
 * @param string $string
 * @param int $flags
 * @param string $encoding
 * @return string
 */
function html_entity_decode($string, $flags = ENT_COMPAT | ENT_HTML401, $encoding = 'UTF-8')
{
}

/**
 * Convert special HTML entities back to characters.
 * --RU--
 * Преобразует специальные HTML сущности обратно в символы.
 *
 * @param string $string
 * @param int $flags
 * @return string
 */
function htmlspecialchars_decode($string, $flags = ENT_COMPAT | ENT_HTML401)
{
}

/**
 * Inserts HTML line breaks before all newlines in a string.
 * --RU--
 * Вставляет HTML-разрывы строк перед всеми новыми строками в тексте.
 *
 * @param string $text
 * @param bool $is_xhtml
 * @return string
 */
function nl2br($text, $is_xhtml = true)
{
}

/**
 * Format a number with grouped thousands.
 * --RU--
 * Форматирует число с разделением тысяч.
 *
 * @param float $number
 * @param int $decimals
 * @param string $dec_point
 * @param string $thousands_sep
 * @return string
 */
function number_format($number, $decimals = 0, $dec_point = ".", $thousands_sep = ",")
{
}

/**
 * Parse a URL and return its components.
 * --RU--
 * Разбирает URL и возвращает его компоненты.
 *
 * @param string $url
 * @param int $component
 * @return array|string
 */
function parse_url($url, $component = -1)
{
}

/**
 * URL-encodes a string.
 * --RU--
 * Кодирует строку в формате URL.
 *
 * @param string $string
 * @return string
 */
function urlencode($string)
{
}

/**
 * Decodes a URL-encoded string.
 * --RU--
 * Декодирует строку в формате URL.
 *
 * @param string $string
 * @return string
 */
function urldecode($string)
{
}

/**
 * Encodes data with MIME base64.
 * --RU--
 * Кодирует данные в формате MIME base64.
 *
 * @param string $string
 * @return string
 */
function base64_encode($string)
{
}

/**
 * Decodes data encoded with MIME base64.
 * --RU--
 * Декодирует данные, закодированные в формате MIME base64.
 *
 * @param string $string
 * @return string
 */
function base64_decode($string)
{
}

/**
 * URL-encode according to RFC 3986.
 * --RU--
 * Кодирует URL согласно RFC 3986.
 *
 * @param string $string
 * @return string
 */
function rawurlencode($string)
{
}

/**
 * Decode URL-encoded according to RFC 3986.
 * --RU--
 * Декодирует URL, закодированный согласно RFC 3986.
 *
 * @param string $string
 * @return string
 */
function rawurldecode($string)
{
}

/**
 * Wraps a string to a given number of characters.
 * --RU--
 * Переносит строку на заданное количество символов.
 *
 * @param string $str
 * @param int $width
 * @param string $break
 * @param bool $cut
 */
function wordwrap($str, $width = 75, $break = '\n', $cut = false)
{
}
