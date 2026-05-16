<?php

/**
 * Delay execution in seconds.
 * --RU--
 * Задержка выполнения на указанное количество секунд.
 * @param int $seconds Количество секунд.
 */
function sleep($seconds)
{
}

/**
 * Delay execution in microseconds.
 * --RU--
 * Задержка выполнения на указанное количество микросекунд.
 * @param int $micro_seconds Количество микросекунд.
 */
function usleep($micro_seconds)
{
}

/**
 * Create array containing variables and their values.
 * --RU--
 * Создает массив, содержащий переменные и их значения.
 * @param mixed $var Переменная.
 * @param mixed ...$vars Дополнительные переменные.
 * @return array Массив с переменными и их значениями.
 */
function compact($var, ...$vars)
{
}

/**
 * Import variables from an array into the current symbol table.
 * --RU--
 * Импортирует переменные из массива в текущую таблицу символов.
 * @param array $names Ассоциативный массив, содержащий имена переменных и их значения.
 * @param int $extractType Тип извлечения (EXTR_OVERWRITE, EXTR_SKIP и т.д.).
 * @return int Количество успешно извлеченных переменных.
 */
function extract(array $names, $extractType = EXTR_OVERWRITE)
{
}

/**
 * Checks whether a constant exists.
 * --RU--
 * Проверяет, существует ли константа.
 * @param string $constName Имя константы.
 * @return bool true, если константа определена, иначе false.
 */
function defined($constName)
{
}

/**
 * Defines a named constant.
 * --RU--
 * Определяет именованную константу.
 * @param string $name Имя константы.
 * @param mixed $value Значение константы.
 * @param bool $caseSensitive Учитывать регистр (по умолчанию true).
 */
function define($name, $value, $caseSensitive = true)
{
}

/**
 * Returns the value of a constant.
 * --RU--
 * Возвращает значение константы.
 * @param string $name Имя константы.
 * @return mixed Значение константы.
 */
function constant($name)
{
}

/**
 * Get the type of a variable.
 * --RU--
 * Возвращает тип переменной.
 * @param mixed $variable Переменная.
 * @return string Тип переменной.
 */
function gettype($variable)
{
}

/**
 * Finds whether a variable is an array.
 * --RU--
 * Проверяет, является ли переменная массивом.
 * @param mixed $value Переменная.
 * @return bool true, если переменная является массивом, иначе false.
 */
function is_array($value)
{
}

/**
 * Verify that the contents of a variable is an iterable value.
 * --RU--
 * Проверяет, является ли содержимое переменной итерируемым значением.
 * @param mixed $value Переменная.
 * @return bool true, если переменная итерируема, иначе false.
 */
function is_iterable($value)
{
}

/**
 * Finds out whether a variable is a boolean.
 * --RU--
 * Проверяет, является ли переменная булевым значением.
 * @param mixed $value Переменная.
 * @return bool true, если переменная является булевой, иначе false.
 */
function is_bool($value)
{
}

/**
 * Alias of is_float.
 * --RU--
 * Псевдоним функции is_float.
 * @param mixed $value Переменная.
 * @return bool true, если переменная является float, иначе false.
 */
function is_double($value)
{
}

/**
 * Finds whether the type of a variable is float.
 * --RU--
 * Проверяет, является ли тип переменной float.
 * @param mixed $value Переменная.
 * @return bool true, если переменная является float, иначе false.
 */
function is_float($value)
{
}

/**
 * Find whether the type of a variable is integer.
 * --RU--
 * Проверяет, является ли тип переменной integer.
 * @param mixed $value Переменная.
 * @return bool true, если переменная является integer, иначе false.
 */
function is_int($value)
{
}

/**
 * Alias of is_int.
 * --RU--
 * Псевдоним функции is_int.
 * @param mixed $value Переменная.
 * @return bool true, если переменная является integer, иначе false.
 */
function is_integer($value)
{
}

/**
 * Finds whether a variable is null.
 * --RU--
 * Проверяет, является ли переменная null.
 * @param mixed $value Переменная.
 * @return bool true, если переменная является null, иначе false.
 */
function is_null($value)
{
}

/**
 * Finds whether a variable is an object.
 * --RU--
 * Проверяет, является ли переменная объектом.
 * @param mixed $value Переменная.
 * @return bool true, если переменная является объектом, иначе false.
 */
function is_object($value)
{
}

/**
 * Find whether the type of a variable is string.
 * --RU--
 * Проверяет, является ли тип переменной string.
 * @param mixed $value Переменная.
 * @return bool true, если переменная является string, иначе false.
 */
function is_string($value)
{
}

/**
 * Finds whether a variable is a number or a numeric string.
 * --RU--
 * Проверяет, является ли переменная числом или числовой строкой.
 * @param mixed $value Переменная.
 * @return bool true, если переменная является числом или числовой строкой, иначе false.
 */
function is_numeric($value)
{
}

/**
 * Finds whether a variable is a scalar.
 * --RU--
 * Проверяет, является ли переменная скалярной.
 * @param mixed $value Переменная.
 * @return bool true, если переменная является скалярной, иначе false.
 */
function is_scalar($value)
{
}

/**
 * Verify that the contents of a variable can be called as a function.
 * --RU--
 * Проверяет, может ли содержимое переменной быть вызвано как функция.
 * @param mixed $value Переменная.
 * @return bool true, если переменная может быть вызвана как функция, иначе false.
 */
function is_callable($value)
{
}

/**
 * Get the boolean value of a variable.
 * --RU--
 * Возвращает булевое значение переменной.
 * @param mixed $value Переменная.
 * @return bool Булевое значение переменной.
 */
function boolval($value)
{
}

/**
 * Get the string value of a variable.
 * --RU--
 * Возвращает строковое значение переменной.
 * @param mixed $value Переменная.
 * @return string Строковое значение переменной.
 */
function strval($value)
{
}

/**
 * Get the integer value of a variable.
 * --RU--
 * Возвращает целочисленное значение переменной.
 * @param mixed $value Переменная.
 * @return int Целочисленное значение переменной.
 */
function intval($value)
{
}

/**
 * Get float value of a variable.
 * --RU--
 * Возвращает значение переменной в виде числа с плавающей запятой.
 * @param mixed $value Переменная.
 * @return float Значение переменной в виде числа с плавающей запятой.
 */
function floatval($value)
{
}

/**
 * Alias of floatval.
 * --RU--
 * Псевдоним функции floatval.
 * @param mixed $value Переменная.
 * @return float Значение переменной в виде числа с плавающей запятой.
 */
function doubleval($value)
{
}

/**
 * Returns an array comprising a function's argument list.
 * --RU--
 * Возвращает массив, содержащий список аргументов функции.
 * @return array Массив с аргументами функции.
 */
function func_get_args()
{
}

/**
 * Returns the number of arguments passed to the function.
 * --RU--
 * Возвращает количество аргументов, переданных функции.
 * @return int Количество аргументов.
 */
function func_num_args()
{
}

/**
 * Returns the specified argument from a function's argument list.
 * --RU--
 * Возвращает указанный аргумент из списка аргументов функции.
 * @param int $num Порядковый номер аргумента.
 * @return mixed Значение аргумента.
 */
function func_get_arg($num)
{
}

/**
 * Call a user function given by the first parameter.
 * --RU--
 * Вызывает пользовательскую функцию, указанную в первом параметре.
 * @param string|array|object $name Имя функции, массив с именем класса и методом или объект и метод.
 * @param mixed ...$args Аргументы, передаваемые функции.
 */
function call_user_func($name, ...$args)
{
}

/**
 * Call a user function given with an array of parameters.
 * --RU--
 * Вызывает пользовательскую функцию с массивом параметров.
 * @param string|array|object $name Имя функции, массив с именем класса и методом или объект и метод.
 * @param array $args Массив аргументов, передаваемых функции.
 */
function call_user_func_array($name, array $args)
{
}

/**
 * Generate a backtrace.
 * --RU--
 * Генерирует обратный трассировку.
 * @param int $options Опции (например, DEBUG_BACKTRACE_PROVIDE_OBJECT).
 * @param int $limit Лимит количества шагов в обратной трассировке.
 * @return array Массив с обратной трассировкой.
 */
function debug_backtrace($options = DEBUG_BACKTRACE_PROVIDE_OBJECT, $limit = 0)
{
}

/**
 * Return true if the given function has been defined.
 * --RU--
 * Возвращает true, если указанная функция определена.
 * @param string $name Имя функции.
 * @return bool true, если функция определена, иначе false.
 */
function function_exists($name)
{
}

/**
 * Checks if the class has been defined.
 * --RU--
 * Проверяет, определен ли класс.
 * @param string $name Имя класса.
 * @param bool $autoLoad Автоматическая загрузка класса (по умолчанию true).
 * @return bool true, если класс определен, иначе false.
 */
function class_exists($name, $autoLoad = true)
{
}

/**
 * Checks if the interface has been defined.
 * --RU--
 * Проверяет, определен ли интерфейс.
 * @param string $name Имя интерфейса.
 * @param bool $autoLoad Автоматическая загрузка интерфейса (по умолчанию true).
 * @return bool true, если интерфейс определен, иначе false.
 */
function interface_exists($name, $autoLoad = true)
{
}

/**
 * Checks if the trait has been defined.
 * --RU--
 * Проверяет, определен ли трейт.
 * @param string $name Имя трейта.
 * @param bool $autoLoad Автоматическая загрузка трейта (по умолчанию true).
 * @return bool true, если трейт определен, иначе false.
 */
function trait_exists($name, $autoLoad = true)
{
}

/**
 * Checks if the class method exists.
 * --RU--
 * Проверяет, существует ли метод класса.
 * @param string|object $objectOrClass Объект или имя класса.
 * @param string $name Имя метода.
 * @return bool true, если метод существует, иначе false.
 */
function method_exists($objectOrClass, $name)
{
}

/**
 * Checks if the object or class has a property.
 * --RU--
 * Проверяет, существует ли свойство у объекта или класса.
 * @param string|object $objectOrClass Объект или имя класса.
 * @param string $name Имя свойства.
 * @return bool true, если свойство существует, иначе false.
 */
function property_exists($objectOrClass, $name)
{
}

/**
 * Checks if the object is of this class or has this class as one of its parents.
 * --RU--
 * Проверяет, является ли объект экземпляром класса или наследует его.
 * @param object $object Объект.
 * @param string $className Имя класса.
 * @param bool $allowedString Разрешить строку с именем класса (по умолчанию false).
 * @return bool true, если объект является экземпляром или наследует класс, иначе false.
 */
function is_a($object, $className, $allowedString = false)
{
}

/**
 * Checks if the object has this class as one of its parents.
 * --RU--
 * Проверяет, наследует ли объект указанный класс.
 * @param object $object Объект.
 * @param string $className Имя класса.
 * @param bool $allowedString Разрешить строку с именем класса (по умолчанию false).
 * @return bool true, если объект наследует класс, иначе false.
 */
function is_subclass_of($object, $className, $allowedString = false)
{
}

/**
 * Returns the name of the class of an object.
 * --RU--
 * Возвращает имя класса объекта.
 * @param object $object Объект.
 * @return string Имя класса.
 */
function get_class($object = null)
{
}

/**
 * Gets the name of the class the static method is called in.
 * --RU--
 * Возвращает имя класса, в котором вызван статический метод.
 * @return string Имя класса.
 */
function get_called_class()
{
}

/**
 * Gets the class methods' names.
 * --RU--
 * Возвращает имена методов класса.
 * @param string|object $objectOrClass Имя класса или объект.
 * @return array Массив с именами методов класса.
 */
function get_class_methods($objectOrClass)
{
}

/**
 * Get the default properties of the class.
 * --RU--
 * Возвращает значения свойств класса по умолчанию.
 * @param string|object $objectOrClass Имя класса или объект.
 * @return array Массив со значениями свойств класса.
 */
function get_class_vars($objectOrClass)
{
}

/**
 * Get the object variables.
 * --RU--
 * Возвращает свойства объекта.
 * @param object $object Объект.
 * @return array Массив со свойствами объекта.
 */
function get_object_vars($object)
{
}

/**
 * Retrieves the parent class name for the given object or class.
 * --RU--
 * Возвращает имя родительского класса для указанного объекта или класса.
 * @param object $object Объект.
 * @return string Имя родительского класса.
 */
function get_parent_class($object)
{
}

/**
 * Gets the name of the owner of the current PHP script.
 * --RU--
 * Возвращает имя владельца текущего PHP скрипта.
 * @return string Имя текущего пользователя.
 */
function get_current_user()
{
}

/**
 * Returns an associative array with the names of all the constants and their values.
 * --RU--
 * Возвращает ассоциативный массив с именами всех констант и их значениями.
 * @param bool $capitalize Если true, то имена констант будут в верхнем регистре (по умолчанию false).
 * @return array Массив констант и их значений.
 */
function get_defined_constants($capitalize = false)
{
}

/**
 * Returns an array with the names of all declared classes.
 * --RU--
 * Возвращает массив с именами всех объявленных классов.
 * @return array Массив имен классов.
 */
function get_declared_classes()
{
}

/**
 * Returns an array with the names of all declared interfaces.
 * --RU--
 * Возвращает массив с именами всех объявленных интерфейсов.
 * @return array Массив имен интерфейсов.
 */
function get_declared_interfaces()
{
}

/**
 * Returns an array with the names of all declared traits.
 * --RU--
 * Возвращает массив с именами всех объявленных трейтов.
 * @return array Массив имен трейтов.
 */
function get_declared_traits()
{
}

/**
 * Returns an array with the names of all defined functions.
 * --RU--
 * Возвращает массив с именами всех объявленных функций.
 * @return array Массив имен функций.
 */
function get_defined_functions()
{
}

/**
 * Gets the current PHP process ID.
 * --RU--
 * Возвращает ID текущего PHP процесса.
 * @return int ID процесса.
 */
function getmypid()
{
}

/**
 * Prints human-readable information about a variable.
 * --RU--
 * Выводит в удобочитаемом виде информацию о переменной.
 * @param mixed $value Переменная.
 * @param bool $return Если true, то возвращает результат в виде строки, иначе выводит его (по умолчанию false).
 * @return string|void Строка с информацией о переменной или ничего.
 */
function print_r($value, $return = false)
{
}

/**
 * Dumps information about one or more variables.
 * --RU--
 * Выводит информацию о переменной или переменных.
 * @param mixed $value Переменная.
 * @param mixed ...$values Дополнительные переменные.
 */
function var_dump($value, ...$values)
{
}

/**
 * Outputs or returns a parsable string representation of a variable.
 * --RU--
 * Выводит или возвращает строковое представление переменной, пригодное для анализа.
 * @param mixed $value Переменная.
 * @param bool $return Если true, то возвращает результат в виде строки, иначе выводит его (по умолчанию false).
 * @return string|void Строка с представлением переменной или ничего.
 */
function var_export($value, $return = false)
{
}

/**
 * Counts all elements in an array, or something in an object.
 * --RU--
 * Подсчитывает количество элементов массива или количество чего-то в объекте.
 * @param array|Countable $array Массив или объект, реализующий Countable.
 * @return int Количество элементов.
 */
function count($array)
{
}

/**
 * Alias of count.
 * --RU--
 * Псевдоним функции count.
 * @param array|Countable $array Массив или объект, реализующий Countable.
 * @return int Количество элементов.
 */
function sizeof($array)
{
}

/**
 * Return a specific character.
 * --RU--
 * Возвращает символ по его коду.
 * @param int $codePoint Код символа.
 * @return string Символ.
 */
function chr($codePoint)
{
}

/**
 * Return ASCII value of character.
 * --RU--
 * Возвращает ASCII-код символа.
 * @param string $char Символ.
 * @return int ASCII-код символа.
 */
function ord($char)
{
}

/**
 * Generates a storable representation of a value.
 * --RU--
 * Генерирует представление значения, пригодное для хранения.
 * @param mixed $value Переменная, которую нужно сериализовать.
 * @return string Строка с сериализованным представлением переменной.
 */
function serialize($value)
{
}

/**
 * Creates a PHP value from a stored representation.
 * --RU--
 * Создает значение PHP из сохраненного представления.
 * @param string $string Строка с сериализованным значением.
 * @return mixed Восстановленное значение PHP.
 */
function unserialize($string)
{
}

/**
 * Returns the current working directory.
 * --RU--
 * Возвращает текущую рабочую директорию.
 * @return string Путь к текущей рабочей директории.
 */
function getcwd()
{
}

/**
 * Gets the value of an environment variable.
 * --RU--
 * Получает значение переменной окружения.
 * @param string $varname Имя переменной окружения.
 * @return string Значение переменной окружения.
 */
function getenv($varname)
{
}

/**
 * Sets the value of an environment variable.
 * --RU--
 * Устанавливает значение переменной окружения.
 * @param string $setting Переменная окружения и ее значение в формате "VAR=value".
 * @return bool true, если операция выполнена успешно, иначе false.
 */
function putenv($setting)
{
}

/**
 * Returns information about the operating system PHP is running on.
 * --RU--
 * Возвращает информацию о операционной системе, на которой выполняется PHP.
 * @param string $mode Режим информации о системе. По умолчанию "a" (вся информация).
 * @return string Информация о системе в виде строки.
 */
function php_uname($mode = "a")
{
}

/**
 * Returns the current PHP version.
 * --RU--
 * Возвращает текущую версию PHP.
 * @return string Версия PHP в виде строки.
 */
function phpversion()
{
}
