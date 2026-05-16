<?php
use php\io\Stream;

/**
 * Returns the base name of a file.
 * --RU--
 * Возвращает базовое имя файла.
 * @param string $path Путь к файлу.
 * @param string $suffix [optional] Суффикс для удаления из базового имени.
 * @return string Базовое имя файла.
 */
function basename($path, $suffix = null)
{
}

/**
 * Copies a file.
 * --RU--
 * Копирует файл.
 * @param string $source Путь к исходному файлу.
 * @param string $dest Путь к целевому файлу.
 * @return bool true при успешном завершении, иначе false.
 */
function copy($source, $dest)
{
}

/**
 * Removes a directory.
 * --RU--
 * Удаляет директорию.
 * @param string $dirname Путь к директории.
 * @return bool true при успешном завершении, иначе false.
 */
function rmdir($dirname)
{
}

/**
 * Deletes a file.
 * --RU--
 * Удаляет файл.
 * @param string $filename Путь к файлу.
 * @return bool true при успешном завершении, иначе false.
 */
function unlink($filename)
{
}

/**
 * Renames a file or directory.
 * --RU--
 * Переименовывает файл или директорию.
 * @param string $oldname Старое имя файла или директории.
 * @param string $newname Новое имя файла или директории.
 * @return bool true при успешном завершении, иначе false.
 */
function rename($oldname, $newname)
{
}

/**
 * Returns the directory name from a path.
 * --RU--
 * Возвращает имя директории из пути.
 * @param string $path Путь к файлу или директории.
 * @return string Имя директории.
 */
function dirname($path)
{
}

/**
 * Returns the available disk space.
 * --RU--
 * Возвращает доступное пространство на диске.
 * @param string $directory Путь к директории для проверки.
 * @return int Доступное пространство на диске в байтах.
 */
function disk_free_space($directory)
{
}

/**
 * Returns the total disk space.
 * --RU--
 * Возвращает общий объем диска.
 * @param string $directory Путь к директории для проверки.
 * @return int Общий объем диска в байтах.
 */
function disk_total_space($directory)
{
}

/**
 * Closes a file stream.
 * --RU--
 * Закрывает поток файла.
 * @param Stream $stream Поток файла для закрытия.
 * @return bool true при успешном завершении, иначе false.
 */
function fclose(Stream $stream)
{
}

/**
 * Opens a file or URL.
 * --RU--
 * Открывает файл или URL.
 * @param string $path Путь к файлу или URL.
 * @param string $mode Режим открытия (например, "r" для чтения).
 * @return Stream Открытый поток файла.
 */
function fopen($path, $mode)
{
}

/**
 * Writes data to a file stream.
 * --RU--
 * Записывает данные в поток файла.
 * @param Stream $stream Поток файла для записи.
 * @param string $data Данные для записи.
 * @param int $length [optional] Длина данных для записи.
 */
function fwrite(Stream $stream, $data, $length = null)
{
}

/**
 * Reads data from a file stream.
 * --RU--
 * Читает данные из потока файла.
 * @param Stream $stream Поток файла для чтения.
 * @param int $length Количество байт для чтения.
 * @return string Прочитанные данные.
 */
function fread(Stream $stream, $length)
{
}

/**
 * Checks if the end of the file has been reached.
 * --RU--
 * Проверяет, достигнут ли конец файла.
 * @param Stream $stream Поток файла для проверки.
 * @return bool true, если конец файла достигнут, иначе false.
 */
function feof(Stream $stream)
{
}

/**
 * Reads a single character from a file stream.
 * --RU--
 * Читает один символ из потока файла.
 * @param Stream $stream Поток файла для чтения.
 * @return string Прочитанный символ.
 */
function fgetc(Stream $stream)
{
}

/**
 * Reads a line from a file stream.
 * --RU--
 * Читает строку из потока файла.
 * @param Stream $stream Поток файла для чтения.
 * @param int $length [optional] Максимальная длина строки.
 * @return string Прочитанная строка.
 */
function fgets(Stream $stream, $length = null)
{
}

/**
 * Seeks to a specific position in a file stream.
 * --RU--
 * Перемещает указатель на определенную позицию в потоке файла.
 * @param Stream $stream Поток файла для перемещения.
 * @param int $offset Смещение от начала.
 * @param int $whence [optional] Режим смещения (например, SEEK_SET).
 * @return int Новая позиция в потоке.
 */
function fseek(Stream $stream, $offset, $whence = SEEK_SET)
{
}

/**
 * Returns the current position of the file pointer in a file stream.
 * --RU--
 * Возвращает текущую позицию указателя файла в потоке.
 * @param Stream $stream Поток файла для проверки.
 * @return int Текущая позиция указателя.
 */
function ftell(Stream $stream)
{
}

/**
 * Reads the entire file into an array.
 * --RU--
 * Читает весь файл в массив.
 * @param string $filename Путь к файлу.
 * @param int $flags [optional] Флаги для изменения поведения.
 * @return array Массив строк из файла.
 */
function file($filename, $flags = 0)
{
}

/**
 * Checks if a file or directory exists.
 * --RU--
 * Проверяет, существует ли файл или директория.
 * @param string $filename Путь к файлу или директории.
 * @return bool true, если файл или директория существует, иначе false.
 */
function file_exists($filename)
{
}

/**
 * Checks if a path is a directory.
 * --RU--
 * Проверяет, является ли путь директорией.
 * @param string $path Путь для проверки.
 * @return bool true, если путь является директорией, иначе false.
 */
function is_dir($path)
{
}

/**
 * Checks if a path is a file.
 * --RU--
 * Проверяет, является ли путь файлом.
 * @param string $path Путь для проверки.
 * @return bool true, если путь является файлом, иначе false.
 */
function is_file($path)
{
}

/**
 * Checks if a path is a symbolic link.
 * --RU--
 * Проверяет, является ли путь символической ссылкой.
 * @param string $path Путь для проверки.
 * @return bool true, если путь является символической ссылкой, иначе false.
 */
function is_link($path)
{
}

/**
 * Checks if a file is executable.
 * --RU--
 * Проверяет, является ли файл исполняемым.
 * @param string $filename Путь к файлу.
 * @return bool true, если файл исполняем, иначе false.
 */
function is_executable($filename)
{
}

/**
 * Checks if a file is readable.
 * --RU--
 * Проверяет, является ли файл читаемым.
 * @param string $filename Путь к файлу.
 * @return bool true, если файл читаем, иначе false.
 */
function is_readable($filename)
{
}

/**
 * Checks if a file is writable.
 * --RU--
 * Проверяет, является ли файл записываемым.
 * @param string $filename Путь к файлу.
 * @return bool true, если файл записываем, иначе false.
 */
function is_writable($filename)
{
}

/**
 * Checks if a file is writable (alias of is_writable).
 * --RU--
 * Проверяет, является ли файл записываемым (псевдоним функции is_writable).
 * @param string $filename Путь к файлу.
 * @return bool true, если файл записываем, иначе false.
 */
function is_writeable($filename)
{
}

/**
 * Reads a file into a string.
 * --RU--
 * Читает содержимое файла в строку.
 * @param string $filename Путь к файлу.
 * @param bool $useIncludePaths [optional] Использовать пути включения.
 * @param null $context [optional] Контекст потока.
 * @param int $offset [optional] Смещение для чтения.
 * @param int $maxLentgh [optional] Максимальная длина читаемых данных.
 * @return string|false Содержимое файла или false при ошибке.
 */
function file_get_contents($filename, $useIncludePaths = false, $context = null, $offset = 0, $maxLentgh = null)
{
}

/**
 * Writes data to a file.
 * --RU--
 * Записывает данные в файл.
 * @param string $filename Путь к файлу.
 * @param string $data Данные для записи.
 * @param int $flags [optional] Флаги для управления поведением записи.
 * @return int Количество записанных байт.
 */
function file_put_contents($filename, $data, $flags = 0)
{
}

/**
 * Gets the last access time of a file.
 * --RU--
 * Получает время последнего доступа к файлу.
 * @param string $filename Путь к файлу.
 * @return int Время последнего доступа в формате UNIX timestamp.
 */
function fileatime($filename)
{
}

/**
 * Gets the last modification time of a file.
 * --RU--
 * Получает время последнего изменения файла.
 * @param string $filename Путь к файлу.
 * @return int Время последнего изменения в формате UNIX timestamp.
 */
function filemtime($filename)
{
}

/**
 * Gets the inode change time of a file.
 * --RU--
 * Получает время изменения inode файла.
 * @param string $filename Путь к файлу.
 * @return int Время изменения inode в формате UNIX timestamp.
 */
function filectime($filename)
{
}

/**
 * Gets the size of a file.
 * --RU--
 * Получает размер файла.
 * @param string $filename Путь к файлу.
 * @return int Размер файла в байтах.
 */
function filesize($filename)
{
}

/**
 * Gets the file type.
 * --RU--
 * Получает тип файла.
 * @param string $filename Путь к файлу.
 * @return string Тип файла (например, "file", "dir").
 */
function filetype($filename)
{
}

/**
 * Creates a directory.
 * --RU--
 * Создает директорию.
 * @param string $path Путь к создаваемой директории.
 * @param int $mode [optional] Права доступа к директории (по умолчанию 0777).
 * @param bool $recursive [optional] Если true, создаются вложенные директории.
 * @return bool true при успешном создании, иначе false.
 */
function mkdir($path, $mode = 0777, $recursive = false)
{
}

/**
 * Returns information about a file path.
 * --RU--
 * Возвращает информацию о пути файла.
 * @param string $path Путь к файлу.
 * @param int $options [optional] Опции для получения информации о пути (например, PATHINFO_DIRNAME).
 * @return string|array Информация о пути файла в зависимости от опций.
 */
function pathinfo($path, $options = PATHINFO_DIRNAME | PATHINFO_BASENAME | PATHINFO_EXTENSION | PATHINFO_FILENAME)
{
}
