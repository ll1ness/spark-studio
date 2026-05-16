<?php

/**
 * Return current Unix timestamp.
 * --RU--
 * Возвращает текущую метку времени Unix.
 *
 * @return int
 */
function time()
{
}

/**
 * Return current Unix timestamp with microseconds.
 * --RU--
 * Возвращает текущую метку времени Unix с микросекундами.
 *
 * @param bool $get_as_float
 * @return int|float
 */
function microtime($get_as_float = false)
{
}

/**
 * Get current time.
 * --RU--
 * Получает текущее время.
 *
 * @param bool $get_as_float
 * @return int|float
 */
function gettimeofday($get_as_float = false)
{
}

/**
 * Execute a command via shell and return the complete output as a string.
 * --RU--
 * Выполняет команду через shell и возвращает полный вывод в виде строки.
 *
 * @param string $command
 * @return string|null
 */
function shell_exec($command)
{
}

/**
 * Convert character encoding.
 * --RU--
 * Преобразует кодировку символов.
 *
 * @param string $string
 * @param string $to_encoding
 * @param string $from_encoding
 * @return string|false
 */
function mb_convert_encoding($string, $to_encoding, $from_encoding)
{
}

/**
 * Encrypt a string with a given salt.
 * --RU--
 * Шифрует строку с использованием заданной соли.
 *
 * @param string $str
 * @param string $salt
 * @return string|null
 */
function crypt($str, $salt)
{
}

/**
 * Perform the ROT13 transformation on a string.
 * --RU--
 * Выполняет преобразование ROT13 над строкой.
 *
 * @param string $str
 * @return string
 */
function str_rot13($str)
{
}

/**
 * Generate a random integer.
 * --RU--
 * Генерирует случайное целое число.
 *
 * @return int
 */
function mt_rand()
{
}

/**
 * Generate a random integer within a specified range.
 * --RU--
 * Генерирует случайное целое число в указанном диапазоне.
 *
 * @param int $min
 * @param int $max
 * @return int
 */
function mt_rand($min, $max)
{
}

/**
 * Assert a condition.
 * --RU--
 * Проверяет условие.
 *
 * @param string $expression
 * @return bool
 */
function assert_($expression)
{
}

/**
 * Get a substring of a string with a specified encoding.
 * --RU--
 * Получает подстроку строки с указанной кодировкой.
 *
 * @param string $str
 * @param int $start
 * @param int $length
 * @param string $encoding
 * @return string
 */
function mb_substr($str, $start, $length, $encoding)
{
}

/**
 * Find the position of a substring within a string with a specified encoding.
 * --RU--
 * Находит позицию подстроки в строке с указанной кодировкой.
 *
 * @param string $haystack
 * @param string $needle
 * @param int $offset
 * @param string $encoding
 * @return int|false
 */
function mb_strpos($haystack, $needle, $offset, $encoding)
{
}

/**
 * Get the IP address for a hostname.
 * --RU--
 * Получает IP-адрес для имени хоста.
 *
 * @param string $hostname
 * @return string
 */
function gethostbyname($hostname)
{
}

/**
 * Create a temporary file.
 * --RU--
 * Создает временный файл.
 *
 * @param string $dir
 * @param string $prefix
 * @return string|false
 */
function tempnam($dir, $prefix)
{
}

/**
 * Get the system temporary directory.
 * --RU--
 * Получает системную временную директорию.
 *
 * @return string
 */
function sys_get_temp_dir()
{
}

/**
 * Open a socket connection.
 * --RU--
 * Открывает соединение сокета.
 *
 * @param string $hostname
 * @param int $port
 * @param int $timeout
 * @return int|false
 */
function fsockopen($hostname, $port, $timeout)
{
}

/**
 * Close a socket connection.
 * --RU--
 * Закрывает соединение сокета.
 *
 * @param int $socketId
 * @return bool
 */
function fclose($socketId)
{
}

/**
 * Get the current Java version.
 * --RU--
 * Возвращает текущую версию Java.
 *
 * @return string
 */
function javaversion()
{
}
