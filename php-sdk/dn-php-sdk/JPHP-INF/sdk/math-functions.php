<?php

/**
 * Returns a random number from $from (include) and $to (include).
 * --RU--
 * Возвращает случайное число от $from (включительно) до $to (включительно).
 * @param int $from
 * @param int $to
 * @return int
 */
function rand($from, $to)
{
}

/**
 * --RU--
 * Возвращает минимальное значение.
 * @param array|mixed $one Первый элемент или массив.
 * @param ... $args (optional) Дополнительные элементы.
 * @return float|int Минимальное значение.
 */
function min($one, ...$args)
{

}

/**
 * --RU--
 * Возвращает максимальное значение.
 * @param array|mixed $one Первый элемент или массив.
 * @param ... $args (optional) Дополнительные элементы.
 * @return float|int Максимальное значение.
 */
function max($one, ...$args)
{

}

/**
 * Absolute value
 * --RU--
 * Возвращает абсолютное значение числа.
 *
 * @return number Абсолютное значение.
 */
function abs ($number) {}

/**
 * Round fractions up
 * --RU--
 * Округляет число в большую сторону.
 *
 * @param float $value Значение для округления.
 * @return float Округленное значение.
 */
function ceil ($value) {}

/**
 * Round fractions down
 * --RU--
 * Округляет число в меньшую сторону.
 *
 * @param float $value Значение для округления.
 * @return float Округленное значение.
 */
function floor ($value) {}

/**
 * Rounds a float.
 * --RU--
 * Округляет число.
 *
 * @param float $val Значение для округления.
 * @param int $precision Точность округления.
 * @param int $mode Режим округления.
 */
function round ($val, $precision = 0, $mode = PHP_ROUND_HALF_UP) {}

/**
 * The sine of arg.
 * --RU--
 * Синус числа.
 * @param float $arg Аргумент.
 * @return float Синус аргумента.
 */
function sin ($arg) {}

/**
 * The cosine of arg
 * --RU--
 * Косинус числа.
 *
 * @param float $arg Аргумент.
 * @return float Косинус аргумента.
 */
function cos ($arg) {}

/**
 * Tangent
 * --RU--
 * Тангенс числа.
 * 
 * @param float $arg Аргумент в радианах.
 * @return float Тангенс аргумента.
 */
function tan ($arg) {}

/**
 * Arc sine
 * --RU--
 * Арксинус числа.
 * 
 * @param float $arg Аргумент.
 * @return float Арксинус аргумента в радианах.
 */
function asin ($arg) {}

/**
 * Arc cosine
 * --RU--
 * Арккосинус числа.
 * 
 * @param float $arg Аргумент.
 * @return float Арккосинус аргумента в радианах.
 */
function acos ($arg) {}

/**
 * Arc tangent
 * --RU--
 * Арктангенс числа.
 * 
 * @param float $arg Аргумент.
 * @return float Арктангенс аргумента в радианах.
 */
function atan ($arg) {}

/**
 * Inverse hyperbolic tangent
 * --RU--
 * Обратный гиперболический тангенс числа.
 * 
 * @param float $arg Аргумент.
 * @return float Обратный гиперболический тангенс аргумента.
 */
function atanh ($arg) {}

/**
 * Arc tangent of two variables
 * --RU--
 * Арктангенс отношения двух переменных.
 * 
 * @param float $y Дивиденд.
 * @param float $x Делитель.
 * @return float Арктангенс отношения y/x в радианах.
 */
function atan2 ($y, $x) {}

/**
 * Hyperbolic sine
 * --RU--
 * Гиперболический синус числа.
 * 
 * @param float $arg Аргумент.
 * @return float Гиперболический синус аргумента.
 */
function sinh ($arg) {}

/**
 * Hyperbolic cosine
 * --RU--
 * Гиперболический косинус числа.
 * 
 * @param float $arg Аргумент.
 * @return float Гиперболический косинус аргумента.
 */
function cosh ($arg) {}

/**
 * Hyperbolic tangent
 * --RU--
 * Гиперболический тангенс числа.
 * 
 * @param float $arg Аргумент.
 * @return float Гиперболический тангенс аргумента.
 */
function tanh ($arg) {}

/**
 * Inverse hyperbolic sine
 * --RU--
 * Обратный гиперболический синус числа.
 * 
 * @param float $arg Аргумент.
 * @return float Обратный гиперболический синус аргумента.
 */
function asinh ($arg) {}

/**
 * Inverse hyperbolic cosine
 * --RU--
 * Обратный гиперболический косинус числа.
 * 
 * @param float $arg Аргумент.
 * @return float Обратный гиперболический косинус аргумента.
 */
function acosh ($arg) {}

/**
 * Returns exp(number) - 1, computed in a way that is accurate even
 * when the value of number is close to zero
 * --RU--
 * Возвращает exp(число) - 1, рассчитанное таким образом, чтобы быть точным, даже если значение числа близко к нулю.
 * 
 * @param float $arg Аргумент.
 * @return float 'e' в степени аргумента минус один.
 */
function expm1 ($arg) {}

/**
 * Returns log(1 + number), computed in a way that is accurate even when the value of number is close to zero
 * --RU--
 * Возвращает log(1 + число), рассчитанное таким образом, чтобы быть точным, даже если значение числа близко к нулю.
 * 
 * @param float $number Аргумент.
 * @return float log(1 + аргумент).
 */
function log1p ($number) {}

/**
 * Get value of pi
 * --RU--
 * Возвращает значение числа Пи.
 * 
 * @return float Значение числа Пи в виде float.
 */
function pi () {}

/**
 * Finds whether a value is a legal finite number
 * --RU--
 * Определяет, является ли значение допустимым конечным числом.
 * 
 * @param float $val Значение для проверки.
 * @return bool true, если значение является допустимым конечным числом в пределах допустимого диапазона для PHP float на данной платформе, иначе false.
 */
function is_finite ($val) {}

/**
 * Finds whether a value is not a number
 * --RU--
 * Определяет, является ли значение не числом (NaN).
 * 
 * @param float $val Значение для проверки.
 * @return bool true, если значение является 'не числом' (NaN), иначе false.
 */
function is_nan ($val) {}

/**
 * Finds whether a value is infinite
 * --RU--
 * Определяет, является ли значение бесконечным.
 * 
 * @param float $val Значение для проверки.
 * @return bool true, если значение является бесконечным, иначе false.
 */
function is_infinite ($val) {}

/**
 * Exponential expression
 * --RU--
 * Возвращает значение выражения в виде базы в степени экспоненты.
 * 
 * @param number $base Основание.
 * @param number $exp Экспонента.
 * @return number Основание, возведенное в степень экспоненты.
 * Если результат может быть представлен как целое число, он будет возвращен в виде целого числа, иначе в виде float.
 * Если значение степени не может быть вычислено, будет возвращено false.
 */
function pow ($base, $exp) {}

/**
 * Calculates the exponent of <constant>e</constant>
 * --RU--
 * Вычисляет экспоненту числа e.
 * 
 * @param float $arg Аргумент.
 * @return float 'e' в степени аргумента.
 */
function exp ($arg) {}

/**
 * Natural logarithm
 * --RU--
 * Вычисляет натуральный логарифм числа.
 * 
 * @param float $arg Значение для вычисления логарифма.
 * @param float $base [optional] Логарифмическое основание (по умолчанию 'e' и, следовательно, натуральный логарифм).
 * @return float Логарифм аргумента по основанию, если указано, или натуральный логарифм.
 */
function log ($arg, $base = null) {}

/**
 * Base-10 logarithm
 * --RU--
 * Десятичный логарифм числа.
 * 
 * @param float $arg Аргумент.
 * @return float Десятичный логарифм аргумента.
 */
function log10 ($arg) {}

/**
 * Square root
 * --RU--
 * Квадратный корень числа.
 * 
 * @param float $arg Аргумент.
 * @return float Квадратный корень аргумента или специальное значение NAN для отрицательных чисел.
 */
function sqrt ($arg) {}

/**
 * Calculate the length of the hypotenuse of a right-angle triangle
 * --RU--
 * Вычисляет длину гипотенузы прямоугольного треугольника.
 * 
 * @param float $x Длина первого катета.
 * @param float $y Длина второго катета.
 * @return float Вычисленная длина гипотенузы.
 */
function hypot ($x, $y) {}

/**
 * Converts the number in degrees to the radian equivalent
 * --RU--
 * Преобразует число в градусах в эквивалентное значение в радианах.
 * 
 * @param float $number Угловое значение в градусах.
 * @return float Радианное значение аргумента.
 */
function deg2rad ($number) {}

/**
 * Converts the radian number to the equivalent number in degrees
 * --RU--
 * Преобразует радианное значение в эквивалентное значение в градусах.
 * 
 * @param float $number Радианное значение.
 * @return float Эквивалентное значение аргумента в градусах.
 */
function rad2deg ($number) {}

/**
 * Binary to decimal
 * --RU--
 * Преобразует двоичное число в десятичное.
 * 
 * @param string $binary_string Двоичная строка для преобразования.
 * @return number Десятичное значение двоичной строки.
 */
function bindec ($binary_string) {}

/**
 * Hexadecimal to decimal
 * --RU--
 * Преобразует шестнадцатеричное число в десятичное.
 * 
 * @param string $hex_string Шестнадцатеричная строка для преобразования.
 * @return number Десятичное представление шестнадцатеричной строки.
 */
function hexdec ($hex_string) {}

/**
 * Octal to decimal
 * --RU--
 * Преобразует восьмеричное число в десятичное.
 * 
 * @param string $octal_string Восьмеричная строка для преобразования.
 * @return number Десятичное представление восьмеричной строки.
 */
function octdec ($octal_string) {}

/**
 * Decimal to binary
 * --RU--
 * Преобразует десятичное число в двоичное.
 * 
 * @param int $number Десятичное значение для преобразования.
 * @return string Двоичная строка представления числа.
 */
function decbin ($number) {}

/**
 * Decimal to octal
 * --RU--
 * Преобразует десятичное число в восьмеричное.
 * 
 * @param int $number Десятичное значение для преобразования.
 * @return string Восьмеричная строка представления числа.
 */
function decoct ($number) {}

/**
 * Decimal to hexadecimal
 * --RU--
 * Преобразует десятичное число в шестнадцатеричное.
 * 
 * @param int $number Десятичное значение для преобразования.
 * @return string Шестнадцатеричная строка представления числа.
 */
function dechex ($number) {}

/**
 * Convert a number between arbitrary bases
 * --RU--
 * Преобразует число между произвольными системами счисления.
 * 
 * @param string $number Число для преобразования.
 * @param int $frombase Основание системы счисления исходного числа.
 * @param int $tobase Основание системы счисления для преобразованного числа.
 * @return string Число, преобразованное в систему счисления tobase.
 */
function base_convert ($number, $frombase, $tobase) {}
