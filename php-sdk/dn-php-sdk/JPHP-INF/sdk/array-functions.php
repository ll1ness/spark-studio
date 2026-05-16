<?php

/**
 * Changes all keys in an array to lowercase or uppercase.
 * --RU--
 * Приводит все ключи в массиве к нижнему или верхнему регистру.
 * @param array $array Массив для изменения.
 * @param int $case Регистровая константа (CASE_LOWER или CASE_UPPER).
 * @return array Измененный массив.
 */
function array_change_key_case(array $array, $case = CASE_LOWER)
{
}

/**
 * Splits an array into chunks.
 * --RU--
 * Разбивает массив на части.
 * @param array $array Массив для разбиения.
 * @param int $size Размер каждой части.
 * @param bool $save_keys Если true, ключи будут сохранены.
 * @return array Массив, состоящий из частей.
 */
function array_chunk(array $array, $size, $save_keys = false)
{
}

/**
 * Returns the values from a single column in the input array.
 * --RU--
 * Возвращает значения из одного столбца в входном массиве.
 * @param array $array Массив для извлечения данных.
 * @param $column_key Ключ столбца для извлечения.
 * @param $index_key [optional] Ключ для индексации.
 * @return array Массив значений из указанного столбца.
 */
function array_column(array $array, $column_key, $index_key = null)
{
}

/**
 * Creates an array by using one array for keys and another for its values.
 * --RU--
 * Создает массив, используя один массив для ключей, а другой для значений.
 * @param array $keys Массив ключей.
 * @param array $values Массив значений.
 * @return array Сформированный массив.
 */
function array_combine(array $keys, array $values)
{
}

/**
 * Counts all the values of an array.
 * --RU--
 * Считает все значения массива.
 * @param array $array Массив для подсчета.
 * @return array Ассоциативный массив с количеством каждого значения.
 */
function array_count_values(array $array)
{
}

/**
 * Computes the difference of arrays.
 * --RU--
 * Вычисляет разницу массивов.
 * @param array $array1 Первый массив.
 * @param array $array2 Второй массив.
 * @param ... $arrays [optional] Дополнительные массивы.
 * @return array Массив с разницей.
 */
function array_diff($array1, $array2, ...$arrays)
{
}

/**
 * Computes the difference of arrays with additional index check.
 * --RU--
 * Вычисляет разницу массивов с учетом индексов.
 * @param array $array1 Первый массив.
 * @param array $array2 Второй массив.
 * @param ... $arrays [optional] Дополнительные массивы.
 * @return array Массив с разницей с учетом индексов.
 */
function array_diff_assoc($array1, $array2, ...$arrays)
{
}

/**
 * Computes the difference of arrays by keys.
 * --RU--
 * Вычисляет разницу массивов по ключам.
 * @param array $array1 Первый массив.
 * @param array $array2 Второй массив.
 * @param ... $arrays [optional] Дополнительные массивы.
 * @return array Массив с разницей по ключам.
 */
function array_diff_key($array1, $array2, ...$arrays)
{
}

/**
 * Fills an array with a specified value.
 * --RU--
 * Заполняет массив указанным значением.
 * @param int $start_index Индекс для начала заполнения.
 * @param int $num Количество элементов для заполнения.
 * @param mixed $value Значение для заполнения.
 * @return array Заполненный массив.
 */
function array_fill($start_index, $num, $value)
{
}

/**
 * Fills an array with a specified value, using an array of keys.
 * --RU--
 * Заполняет массив указанным значением, используя массив ключей.
 * @param array $keys Массив ключей.
 * @param mixed $value Значение для заполнения.
 * @return array Заполненный массив.
 */
function array_fill_keys(array $keys, $value)
{
}

/**
 * Filters elements of an array using a callback function.
 * --RU--
 * Фильтрует элементы массива, используя функцию обратного вызова.
 * @param array $array Массив для фильтрации.
 * @param callable $filter Функция для фильтрации.
 * @param int $flag [optional] Флаги для контроля поведения фильтрации.
 * @return array Отфильтрованный массив.
 */
function array_filter(array $array, callable $filter, $flag = 0)
{
}

/**
 * Exchanges all keys with their lowercase or uppercase counterparts.
 * --RU--
 * Обменивает все ключи на их эквиваленты в нижнем или верхнем регистре.
 * @param array $array Массив для изменения.
 * @return array Массив с измененными ключами.
 */
function array_flip(array $array)
{
}

/**
 * Returns all the keys or a subset of the keys of an array.
 * --RU--
 * Возвращает все ключи или подмножество ключей массива.
 * @param array $arrays Массив для поиска ключей.
 * @param mixed $search_value [optional] Значение для поиска ключей.
 * @param bool $strict [optional] Если true, используется строгое сравнение.
 * @return array Массив ключей.
 */
function array_keys(array $arrays, $search_value = null, $strict = false)
{
}

/**
 * Applies a callback function to each element of an array.
 * --RU--
 * Применяет функцию обратного вызова к каждому элементу массива.
 * @param callable $callback Функция для применения.
 * @param array $array1 Первый массив.
 * @param ... $arrays [optional] Дополнительные массивы.
 * @return array Массив с результатами применения функции.
 */
function array_map(callable $callback, array $array1, ...$arrays)
{
}

/**
 * Merges one or more arrays.
 * --RU--
 * Объединяет один или несколько массивов.
 * @param array $array1 Первый массив.
 * @param ... $arrays [optional] Дополнительные массивы.
 * @return array Объединенный массив.
 */
function array_merge(array $array1, ...$arrays)
{
}

/**
 * Pads an array to the specified length with a specified value.
 * --RU--
 * Дополняет массив до указанной длины указанным значением.
 * @param array $array Массив для дополнения.
 * @param int $size Новая длина массива.
 * @param mixed $value Значение для дополнения.
 * @return array Дополненный массив.
 */
function array_pad(array $array, $size, $value)
{
}

/**
 * Pops the last value off the end of array.
 * --RU--
 * Удаляет и возвращает последний элемент массива.
 * @param array $array Массив для изменения.
 * @return mixed Удаленный элемент.
 */
function array_pop(array &$array)
{
}

/**
 * Calculates the product of values in an array.
 * --RU--
 * Вычисляет произведение значений в массиве.
 * @param array $array Массив для расчета.
 * @return int|float Произведение значений.
 */
function array_product(array $array)
{
}

/**
 * Pushes one or more elements onto the end of an array.
 * --RU--
 * Добавляет один или несколько элементов в конец массива.
 * @param array $array Массив для изменения.
 * @param mixed $value1 Первый элемент для добавления.
 * @param ... $values [optional] Дополнительные элементы для добавления.
 * @return int Новое количество элементов в массиве.
 */
function array_push(array &$array, $value1, ...$values)
{
}

/**
 * Picks one or more random keys out of an array.
 * --RU--
 * Выбирает один или несколько случайных ключей из массива.
 * @param array $array Массив для выбора ключей.
 * @param int $num [optional] Количество случайных ключей.
 * @return int|string|array Случайные ключи.
 */
function array_rand(array $array, $num = 1)
{
}

/**
 * Iteratively reduces the array to a single value using a callback function.
 * --RU--
 * Итеративно сворачивает массив в одно значение с использованием функции обратного вызова.
 * @param array $array Массив для сворачивания.
 * @param callable $callback Функция для сворачивания.
 * @param mixed $initial [optional] Начальное значение.
 * @return mixed Результат сворачивания.
 */
function array_reduce(array $array, callable $callback, $initial = null)
{
}

/**
 * Replaces elements in the first array with elements from subsequent arrays.
 * --RU--
 * Заменяет элементы в первом массиве элементами из последующих массивов.
 * @param array $array1 Первый массив.
 * @param array $array2 Второй массив.
 * @param ...$arrays [optional] Дополнительные массивы.
 * @return array Массив с замененными элементами.
 */
function array_replace(array $array1, array $array2, ...$arrays)
{
}

/**
 * Reverses the order of elements in an array.
 * --RU--
 * Переворачивает порядок элементов в массиве.
 * @param array $array Массив для переворота.
 * @param bool $save_keys [optional] Если true, ключи будут сохранены.
 * @return array Перевернутый массив.
 */
function array_reverse(array $array, $save_keys = false)
{
}

/**
 * Searches for a value in an array and returns the corresponding key.
 * --RU--
 * Ищет значение в массиве и возвращает соответствующий ключ.
 * @param $search Значение для поиска.
 * @param array $array Массив для поиска.
 * @param bool $strict [optional] Если true, используется строгое сравнение.
 * @return mixed Ключ найденного значения или false, если значение не найдено.
 */
function array_search($search, array $array, $strict = false)
{
}

/**
 * Shifts the first value off the beginning of array.
 * --RU--
 * Удаляет и возвращает первый элемент массива.
 * @param array $array Массив для изменения.
 * @return mixed Удаленный элемент.
 */
function array_shift(array &$array)
{
}

/**
 * Extracts a slice of the array.
 * --RU--
 * Извлекает часть массива.
 * @param array $array Массив для извлечения.
 * @param int $offset Начальный индекс для извлечения.
 * @param int $length [optional] Длина части.
 * @param bool $save_keys [optional] Если true, ключи будут сохранены.
 * @return array Извлеченная часть массива.
 */
function array_slice(array $array, $offset, $length = null, $save_keys = false)
{
}

/**
 * Calculates the sum of values in an array.
 * --RU--
 * Вычисляет сумму значений в массиве.
 * @param array $array Массив для расчета.
 * @return int|float Сумма значений.
 */
function array_sum(array $array)
{
}

/**
 * Computes the difference of arrays using a callback function for comparison.
 * --RU--
 * Вычисляет разницу массивов, используя функцию обратного вызова для сравнения.
 * @param array $array1 Первый массив.
 * @param array $array2 Второй массив.
 * @param callable $compare_func Функция для сравнения элементов.
 * @return array Массив с разницей.
 */
function array_udiff(array $array1, array $array2, callable $compare_func)
{
}

/**
 * Computes the difference of arrays with additional index check using a callback function.
 * --RU--
 * Вычисляет разницу массивов с учетом индексов, используя функцию обратного вызова для сравнения.
 * @param array $array1 Первый массив.
 * @param array $array2 Второй массив.
 * @param callable $compare_func Функция для сравнения элементов.
 * @return array Массив с разницей с учетом индексов.
 */
function array_udiff_assoc(array $array1, array $array2, callable $compare_func)
{
}

/**
 * Removes duplicate values from an array.
 * --RU--
 * Удаляет дубликаты значений из массива.
 * @param array $array Массив для очистки.
 * @param int $sort_flags [optional] Флаги для сортировки.
 * @return array Массив без дубликатов.
 */
function array_unique(array $array, $sort_flags = SORT_STRING)
{
}

/**
 * Prepends one or more elements to the beginning of an array.
 * --RU--
 * Добавляет один или несколько элементов в начало массива.
 * @param array $array Массив для изменения.
 * @param mixed $value1 Первый элемент для добавления.
 * @param ...$values [optional] Дополнительные элементы для добавления.
 * @return int Новое количество элементов в массиве.
 */
function array_unshift(array &$array, $value1, ...$values)
{
}

/**
 * Returns all the values from an array.
 * --RU--
 * Возвращает все значения из массива.
 * @param array $array Массив для получения значений.
 * @return array Массив значений.
 */
function array_values(array $array)
{
}

/**
 * Applies a user-defined function to every member of an array.
 * --RU--
 * Применяет функцию, определенную пользователем, к каждому элементу массива.
 * @param array $array Массив для обработки.
 * @param callable $callback Функция для обработки элементов.
 * @param mixed $userData [optional] Дополнительные данные для функции.
 * @return bool Возвращает true при успешном применении.
 */
function array_walk(array $array, callable $callback, $userData = null)
{
}

/**
 * Applies a user-defined function recursively to every member of an array.
 * --RU--
 * Рекурсивно применяет функцию, определенную пользователем, к каждому элементу массива.
 * @param array $array Массив для обработки.
 * @param callable $callback Функция для обработки элементов.
 * @param mixed $userData [optional] Дополнительные данные для функции.
 * @return bool Возвращает true при успешном применении.
 */
function array_walk_recursive(array $array, callable $callback, $userData = null)
{
}

/**
 * Sorts an array in reverse order according to a specified flag.
 * --RU--
 * Сортирует массив в обратном порядке в соответствии с указанным флагом.
 * @param array $array Массив для сортировки.
 * @param int $flags [optional] Флаги для сортировки.
 * @return bool Возвращает true при успешной сортировке.
 */
function arsort(array &$array, $flags = SORT_REGULAR)
{
}

/**
 * Sorts an array in ascending order according to a specified flag.
 * --RU--
 * Сортирует массив в порядке возрастания в соответствии с указанным флагом.
 * @param array $array Массив для сортировки.
 * @param int $flags [optional] Флаги для сортировки.
 * @return bool Возвращает true при успешной сортировке.
 */
function asort(array &$array, $flags = SORT_REGULAR)
{
}

/**
 * Sorts an array in ascending order.
 * --RU--
 * Сортирует массив в порядке возрастания.
 * @param array $array Массив для сортировки.
 * @param int $flags [optional] Флаги для сортировки.
 * @return bool Возвращает true при успешной сортировке.
 */
function sort(array &$array, $flags = SORT_REGULAR)
{
}

/**
 * Sorts an array by key in ascending order.
 * --RU--
 * Сортирует массив по ключам в порядке возрастания.
 * @param array $array Массив для сортировки.
 * @param int $flags [optional] Флаги для сортировки.
 * @return bool Возвращает true при успешной сортировке.
 */
function ksort(array &$array, $flags = SORT_REGULAR)
{
}

/**
 * Sorts an array by key in descending order.
 * --RU--
 * Сортирует массив по ключам в порядке убывания.
 * @param array $array Массив для сортировки.
 * @param int $flags [optional] Флаги для сортировки.
 * @return bool Возвращает true при успешной сортировке.
 */
function krsort(array &$array, $flags = SORT_REGULAR)
{
}

/**
 * Sorts an array using a natural order algorithm.
 * --RU--
 * Сортирует массив, используя алгоритм естественного порядка.
 * @param array $array Массив для сортировки.
 * @return bool Возвращает true при успешной сортировке.
 */
function natsort(array &$array)
{
}

/**
 * Sorts an array using a natural order algorithm, case insensitive.
 * --RU--
 * Сортирует массив, используя алгоритм естественного порядка, без учета регистра.
 * @param array $array Массив для сортировки.
 * @return bool Возвращает true при успешной сортировке.
 */
function natcasesort(array &$array)
{
}

/**
 * Sorts an array by values using a user-defined comparison function.
 * --RU--
 * Сортирует массив по значениям, используя функцию сравнения, определенную пользователем.
 * @param array $array Массив для сортировки.
 * @param callable $callback Функция для сравнения.
 * @return bool Возвращает true при успешной сортировке.
 */
function usort(array &$array, callable $callback)
{
}

/**
 * Sorts an array by keys using a user-defined comparison function.
 * --RU--
 * Сортирует массив по ключам, используя функцию сравнения, определенную пользователем.
 * @param array $array Массив для сортировки.
 * @param callable $callback Функция для сравнения.
 * @return bool Возвращает true при успешной сортировке.
 */
function uksort(array &$array, callable $callback)
{
}

/**
 * Sorts an array by values using a user-defined comparison function, maintaining key association.
 * --RU--
 * Сортирует массив по значениям, используя функцию сравнения, определенную пользователем, сохраняя ассоциацию ключей.
 * @param array $array Массив для сортировки.
 * @param callable $callback Функция для сравнения.
 * @return bool Возвращает true при успешной сортировке.
 */
function uasort(array &$array, callable $callback)
{
}

/**
 * Checks if a value exists in an array.
 * --RU--
 * Проверяет, существует ли значение в массиве.
 * @param mixed $needle Значение для поиска.
 * @param array $array Массив для поиска.
 * @param bool $strict [optional] Если true, используется строгое сравнение.
 * @return bool Возвращает true, если значение найдено, иначе false.
 */
function in_array($needle, array $array, $strict = false)
{
}

/**
 * Returns the current key of an array.
 * --RU--
 * Возвращает текущий ключ массива.
 * @param array $array Массив для получения ключа.
 * @return int|string Текущий ключ.
 */
function key(array &$array)
{
}

/**
 * Alias of array_key_exists().
 * --RU--
 * Альтернатива функции array_key_exists().
 * @param $key Ключ для проверки.
 * @param array $array Массив для проверки.
 * @return bool Возвращает true, если ключ существует, иначе false.
 */
function key_exists($key, array $array)
{
}

/**
 * Checks if a key exists in an array.
 * --RU--
 * Проверяет, существует ли ключ в массиве.
 * @param $key Ключ для проверки.
 * @param array $array Массив для проверки.
 * @return bool Возвращает true, если ключ существует, иначе false.
 */
function array_key_exists($key, array $array)
{
}

/**
 * Returns the current value of an array.
 * --RU--
 * Возвращает текущее значение массива.
 * @param array $array Массив для получения значения.
 * @return mixed Текущее значение.
 */
function current(array &$array)
{
}

/**
 * Fetches the current key and value pair from an array.
 * --RU--
 * Получает текущую пару ключ-значение из массива.
 * @param array $array Массив для получения пары.
 * @return array Массив с парой ключ-значение.
 */
function each(array &$array)
{
}

/**
 * Moves the internal pointer to the last element and returns its value.
 * --RU--
 * Перемещает внутренний указатель к последнему элементу и возвращает его значение.
 * @param array $array Массив для изменения.
 * @return mixed Значение последнего элемента.
 */
function end(array &$array)
{
}

/**
 * Moves the internal pointer to the previous element and returns its value.
 * --RU--
 * Перемещает внутренний указатель к предыдущему элементу и возвращает его значение.
 * @param array $array Массив для изменения.
 * @return mixed Значение предыдущего элемента.
 */
function prev(array &$array)
{
}

/**
 * Moves the internal pointer to the next element and returns its value.
 * --RU--
 * Перемещает внутренний указатель к следующему элементу и возвращает его значение.
 * @param array $array Массив для изменения.
 * @return mixed Значение следующего элемента.
 */
function next(array &$array)
{
}

/**
 * Moves the internal pointer to the first element and returns its value.
 * --RU--
 * Перемещает внутренний указатель к первому элементу и возвращает его значение.
 * @param array $array Массив для изменения.
 * @return mixed Значение первого элемента.
 */
function reset(array &$array)
{
}

/**
 * Creates an array containing a range of elements.
 * --RU--
 * Создает массив, содержащий диапазон элементов.
 * @param int $start Начальное значение.
 * @param int $end Конечное значение.
 * @param int $step [optional] Шаг изменения.
 * @return array Массив с диапазоном значений.
 */
function range($start, $end, $step = 1)
{
}

/**
 * Shuffles the elements of an array.
 * --RU--
 * Перемешивает элементы массива.
 * @param array $array Массив для перемешивания.
 * @return bool Возвращает true при успешном перемешивании.
 */
function shuffle(array &$array)
{
}
