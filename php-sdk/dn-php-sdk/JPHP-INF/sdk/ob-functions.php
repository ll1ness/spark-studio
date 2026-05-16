<?php

/**
 * Turn on output buffering.
 * --RU--
 * Включает буферизацию вывода.
 *
 * @param callable $output_callback Функция обратного вызова для обработки содержимого буфера вывода.
 * @param int $chunk_size Размер блока данных в байтах. Если указан, данные передаются в буфер по частям.
 * @param bool $erase [optional] Указывает, нужно ли очищать (стирание) текущий буфер при переключении буферизации.
 */
function ob_start ($output_callback = null, $chunk_size = null, $erase = null) {}

/**
 * Flush (send) the output buffer.
 * --RU--
 * Отправляет содержимое буфера вывода.
 */
function ob_flush () {}

/**
 * Clean (erase) the output buffer.
 * --RU--
 * Очищает (стирает) содержимое буфера вывода.
 */
function ob_clean () {}

/**
 * Flush (send) the output buffer and turn off output buffering.
 * --RU--
 * Отправляет содержимое буфера вывода и отключает буферизацию вывода.
 * @return bool Успех операции.
 */
function ob_end_flush () {}

/**
 * Clean (erase) the output buffer and turn off output buffering.
 * --RU--
 * Очищает (стирает) содержимое буфера вывода и отключает буферизацию вывода.
 * @return bool Успех операции.
 */
function ob_end_clean () {}

/**
 * Flush the output buffer, return it as a string and turn off output buffering.
 * --RU--
 * Отправляет содержимое буфера вывода, возвращает его в виде строки и отключает буферизацию вывода.
 * @return string Содержимое буфера вывода.
 */
function ob_get_flush () {}

/**
 * Get current buffer contents and delete current output buffer.
 * --RU--
 * Возвращает содержимое текущего буфера вывода и удаляет текущий буфер.
 * @return string Содержимое буфера вывода.
 */
function ob_get_clean () {}

/**
 * Return the length of the output buffer.
 * --RU--
 * Возвращает длину содержимого буфера вывода.
 * @return int Длина содержимого буфера в байтах.
 */
function ob_get_length () {}

/**
 * Return the nesting level of the output buffering mechanism.
 * --RU--
 * Возвращает уровень вложенности механизма буферизации вывода.
 * @return int Уровень вложенности.
 */
function ob_get_level () {}

/**
 * Get status of output buffers.
 * --RU--
 * Возвращает статус буферов вывода.
 * @param null $full_status Если указано, возвращает подробный статус.
 * @return array Статус буферов вывода.
 */
function ob_get_status ($full_status = null) {}

/**
 * Return the contents of the output buffer.
 * --RU--
 * Возвращает содержимое буфера вывода.
 * @return string Содержимое буфера.
 */
function ob_get_contents () {}

/**
 * Turn implicit flush on/off.
 * --RU--
 * Включает/выключает автоматическую отправку данных в буфер вывода.
 * @param int $flag [optional] Если 1, включается автоматическая отправка, если 0 - выключается.
 */
function ob_implicit_flush ($flag = null) {}

/**
 * List all output handlers in use.
 * --RU--
 * Возвращает список всех используемых обработчиков вывода.
 * @return array Список обработчиков вывода.
 */
function ob_list_handlers () {}
