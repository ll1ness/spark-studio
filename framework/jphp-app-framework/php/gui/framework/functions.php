<?php

use php\gui\framework\Application;
use php\gui\framework\behaviour\TextableBehaviour;
use php\gui\framework\behaviour\ValuableBehaviour;
use php\gui\UXAlert;
use php\gui\UXApplication;
use php\gui\UXComboBox;
use php\gui\UXComboBoxBase;
use php\gui\UXDesktop;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\xml\DomDocument;
use php\gui\UXLabeled;
use php\gui\UXListView;
use php\gui\UXTab;
use php\gui\UXTextInputControl;
use php\lang\Process;
use php\lang\Thread;
use php\lib\Items;
use php\lib\Str;
use timer\AccurateTimer;
use ide\forms\MessageBoxForm;
use ide\ui\Notifications;
use ide\utils\Json;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\gui\UXTrayNotification;
use php\gui\UXImageArea;
use php\gui\UXImage;
use ide\utils\Json;

/**
 * Возвращает главный объект программы.
 *
 * @return Application
 * @throws Exception
 */
function app()
{
    return Application::get();
}

/**
 * Открывает файл.
 * @param string $file
 */
function open($file)
{
    (new UXDesktop())->open($file);
}

/**
 * Открывает url в браузере.
 * @param string $url
 */
function browse($url)
{
    (new UXDesktop())->browse($url);
}

/**
 * Выполняет команду в рамках ОС и возвращает процесс.
 * @param string $command
 * @param bool $wait
 * @return Process
 */
function execute($command, $wait = false)
{
    $process = new Process(Str::split($command, ' '));
    return $wait ? $process->startAndWait() : $process->start();
}

/**
 * Пауза в выполнении кода в миллисекундах или во временном периоде.
 * 1 сек = 1000 млсек.
 *
 * Например '2h 30m 10s' или '2.5s' или '2000' или '1m 30s'
 *
 * @param int|string $period
 */
function wait($period)
{
    Thread::sleep(\php\time\Timer::parsePeriod($period));
}

/**
 * Ассинхронная пауза в выполнении кода с колбэком.
 *
 * @param int|string $period
 * @param callable $callback
 * @return AccurateTimer
 */
function waitAsync($period, callable $callback)
{
    return AccurateTimer::executeAfter(\php\time\Timer::parsePeriod($period), $callback);
}

/**
 * Выполнить колбэк позже в UI потоке.
 * Необходимо для работы с UI из других параллельных потоков.
 *
 * @param callable $callback
 */
function uiLater(callable $callback)
{
    UXApplication::runLater($callback);
}

/**
 * Выполнить колбэк позже в UI потоке и ждать его выполнения и результата.
 * Необходимо для работы с UI из других параллельных потоков.
 *
 * @param callable $callback
 * @return mixed
 */
function uiLaterAndWait(callable $callback)
{
    return UXApplication::runLaterAndWait($callback);
}

/**
 * Возвращает значение UI элемента.
 * @param mixed $object
 * @return mixed
 */
function uiValue($object)
{
    if (!$object) {
        return null;
    }

    if ($object instanceof ValuableBehaviour) {
        return $object->getObjectValue();
    }

    if ($object instanceof UXListView || $object instanceof UXComboBox) {
        return $object->selectedIndex;
    }

    if (property_exists($object, 'value')) {
        return $object->value;
    }

    return uiText($object);
}

/**
 * Возвращает текст UI элемента.
 * @param mixed $object
 * @return string
 */
function uiText($object)
{
    if (!$object) {
        return "";
    }

    if ($object instanceof TextableBehaviour) {
        return (string)$object->getObjectText();
    }

    if ($object instanceof UXLabeled || $object instanceof UXTextInputControl || $object instanceof UXTab) {
        return $object->text;
    }

    if ($object instanceof UXComboBoxBase) {
        return $object->editable ? $object->text : $object->value;
    }

    if ($object instanceof UXListView) {
        return Items::first($object->selectedItems);
    }

    return "$object";
}

/**
 * Функция для отображения подтверждающего диалога.
 * @param string $message Сообщение для подтверждения.
 * @param callable|null $onResult Если передан, показывает in-window модал (асинхронно).
 * @return bool|null Вернет true, если пользователь выбрал 'Да', иначе false (синхронный режим).
 */
function uiConfirm($message, callable $onResult = null)
{
    if ($onResult !== null) {
        MessageBoxForm::confirmModal($message, $onResult);
        return null;
    }

    $alert = new UXAlert('CONFIRMATION');
    $alert->headerText = $alert->title = 'Вопрос';
    $alert->contentText = $message;
    $buttons = ['Да', 'Нет'];

    $alert->setButtonTypes($buttons);

    return $alert->showAndWait() == $buttons[0];
}

/**
 * Показать значение переменной как print_r.
 * @param mixed $var Переменная для отображения.
 */
function pre($var)
{
    alert(print_r($var, true));
}

/**
 * Показать значение переменной как var_dump.
 * @param mixed $var Переменная для отображения.
 */
function dump($var)
{
    ob_start();
    var_dump($var);
    $text = ob_get_contents();
    ob_end_clean();

    alert($text);
}

/**
 * Простое сообщение с ожиданием закрытия.
 * @param string $message Сообщение для отображения.
 * @param callable|null $onClose Если передан, показывает in-window модал (асинхронно).
 */
function alert($message, callable $onClose = null)
{
    if ($onClose !== null) {
        \ide\Ide::showMessage($message, $onClose);
        return;
    }
    UXDialog::showAndWait($message);
}

/**
 * Простое сообщение с ожиданием закрытия.
 * @param string $message Сообщение для отображения.
 * @param callable|null $onClose Если передан, показывает in-window модал (асинхронно).
 */
function message($message, callable $onClose = null)
{
    if ($onClose !== null) {
        \ide\Ide::showMessage($message, $onClose);
        return;
    }
    UXDialog::showAndWait($message);
}

/**
 * Открывает блокнот с заданным текстом.
 * @param string $text Текст для отображения.
 */
function notepad($text)
{
    $tempFile = tempnam(sys_get_temp_dir(), 'note') . '.txt';
    file_put_contents($tempFile, $text);
    execute("notepad $tempFile", true);
}

/**
 * Выполняет HTML код и открывает его в браузере.
 * @param string $html HTML код для отображения.
 */
function execute_html($html)
{
    $tempFile = tempnam(sys_get_temp_dir(), 'html') . '.html';
    file_put_contents($tempFile, $html);
    $url = str_replace('\\', '/', $tempFile);
    browse("file://$url");
}

/**
 * Возвращает все запущенные процессы.
 * @return string Список процессов.
 */
function get_system_tasklist()
{
    return cmd('tasklist', true);
}

/**
 * Возвращает активные сетевые подключения.
 * @return string Сетевые подключения.
 */
function get_system_netstat()
{
    return cmd('netstat -an', true);
}

/**
 * Возвращает информацию о системе.
 * @return string Информация о системе.
 */
function get_system()
{
    return cmd('systeminfo', true);
}

/**
 * Возвращает информацию о всех сетевых адаптерах.
 * @return string Сетевые адаптеры.
 */
function get_networks()
{
    return cmd('ipconfig /all', true);
}

/**
 * Возвращает список всех подключенных USB-устройств.
 * @return string Список USB-устройств.
 */
function get_system_usbdevices()
{
    return cmd('wmic path Win32_USBHub', true);
}

/**
 * Возвращает список установленных драйверов.
 * @return string Список драйверов.
 */
function get_system_drivers()
{
    return cmd("pnputil /enum-drivers", true);
}

/**
 * Возвращает информацию о видеокарте.
 * @return string Информация о видеокарте.
 */
function get_gpu()
{
    return cmd('wmic path win32_videocontroller get caption', true);
}

/**
 * Воспроизводит текст с помощью встроенных голосов Windows.
 * @param string $text Текст для воспроизведения.
 * @param string $voiceName Имя голоса для выбора (например, "Microsoft Tatyana Desktop - Russian").
 * @param int $rate Скорость воспроизведения (в диапазоне от -10 до 10, где 0 — стандартная скорость).
 */
function speak($text, $voiceName = '', $rate = 0)
{
    $tempFile = tempnam(sys_get_temp_dir(), 'tts') . '.vbs';

    $vbsContent = "Dim sapi\nSet sapi = CreateObject(\"SAPI.SpVoice\")\n";

    if ($voiceName) {
        $vbsContent .= "Dim voices\nSet voices = sapi.GetVoices\nFor i = 0 To voices.Count - 1\n";
        $vbsContent .= "    If voices.Item(i).GetDescription = \"$voiceName\" Then\n";
        $vbsContent .= "        sapi.Voice = voices.Item(i)\n";
        $vbsContent .= "        Exit For\n";
        $vbsContent .= "    End If\n";
        $vbsContent .= "Next\n";
    }

    $vbsContent .= "sapi.Rate = $rate\n";
    $vbsContent .= "sapi.Speak \"$text\"";

    file_put_contents($tempFile, $vbsContent);
    execute("wscript $tempFile", true);

    unlink($tempFile);
}

/**
 * Получить модель материнской платы.
 * @return string Модель материнской платы.
 */
function get_motherboard_model()
{
    return cmd('wmic baseboard get product', true);
}

/**
 * Получить серийный номер материнской платы.
 * @return string Серийный номер материнской платы.
 */
function get_motherboard_serialnumber()
{
    return cmd('wmic baseboard get serialnumber', true);
}

/**
 * Получить производителя материнской платы.
 * @return string Производитель материнской платы.
 */
function get_motherboard_manufacturer()
{
    return cmd('wmic baseboard get manufacturer', true);
}

/**
 * Получить память видеокарты.
 * @return string Объем памяти видеокарты.
 */
function get_gpu_memory()
{
    return cmd('wmic path win32_videocontroller get adapterram', true);
}

/**
 * Получить объем свободной оперативной памяти.
 * @return string Объем свободной оперативной памяти.
 */
function get_free_ram()
{
    return cmd('systeminfo | findstr /C:"Available Physical Memory"', true);
}

/**
 * Получить объем оперативной памяти.
 * @return string Объем оперативной памяти.
 */
function get_total_ram()
{
    return cmd('systeminfo | findstr /C:"Total Physical Memory"', true);
}

/**
 * Получить время работы системы.
 * @return string Время работы системы.
 */
function get_system_uptime()
{
    return cmd('net stats workstation | find "Statistics since"', true);
}

/**
 * Получить разрядность системы.
 * @return string Разрядность системы.
 */
function get_system_architecture()
{
    return cmd('wmic os get osarchitecture', true);
}

/**
 * Выполнить скрипт VBScript (одна строка).
 * @param string $script Скрипт для выполнения.
 */
function vbscript($script)
{
    $tempFile = tempnam(sys_get_temp_dir(), 'vbs') . '.vbs';
    file_put_contents($tempFile, $script);
    cmd("wscript $tempFile", true);
    unlink($tempFile);
}

/**
 * Получить список программ, находящихся в автозагрузке.
 * @return string Список программ в автозагрузке.
 */
function get_startup_programs()
{
    return cmd('wmic startup get caption,command', true);
}

/**
 * Получить температуру видеокарты.
 * @return string Температура видеокарты.
 */
function get_gpu_temperature()
{
    return cmd('nvidia-smi --query-gpu=temperature.gpu --format=csv,noheader', true);
}

/**
 * Получить версию BIOS.
 * @return string Версия BIOS.
 */
function get_bios_version()
{
    return cmd('wmic bios get smbiosbiosversion', true);
}

/**
 * Получить количество ядер процессора.
 * @return string Количество ядер процессора.
 */
function get_cpu_cores()
{
    return cmd('wmic cpu get numberofcores', true);
}

/**
 * Получить количество потоков процессора.
 * @return string Количество потоков процессора.
 */
function get_cpu_threads()
{
    return cmd('wmic cpu get numberoflogicalprocessors', true);
}

/**
 * Получить модель процессора.
 * @return string Модель процессора.
 */
function get_cpu_model()
{
    return cmd('wmic cpu get name', true);
}

/**
 * Получить серийный номер процессора.
 * @return string Серийный номер процессора.
 */
function get_cpu_serialnumber()
{
    return cmd('wmic cpu get processorid', true);
}

/**
 * Получить максимальную частоту процессора.
 * @return string Максимальная частота процессора.
 */
function get_cpu_maxfrequency()
{
    return cmd('wmic cpu get MaxClockSpeed', true);
}

/**
 * Получить тайминги ОЗУ.
 * @return string Тайминги ОЗУ.
 */
function get_ram_speed()
{
    return cmd('wmic memorychip get speed', true);
}

/**
 * Получить сокет процессора.
 * @return string Сокет процессора.
 */
function get_cpu_socket()
{
    return cmd('wmic cpu get socketdesignation', true);
}

/**
 * Получить текущий план энергопитания.
 * @return string План энергопитания.
 */
function get_windows_powerplan()
{
    return cmd('powercfg /query', true);
}

/**
 * Получить название монитора.
 * @return string Название монитора.
 */
function get_monitor_name()
{
    return cmd('wmic path Win32_DesktopMonitor get caption', true);
}

/**
 * Получить имя сети к которой подключен пользователь.
 * @return string Имя сети.
 */
function get_networks_name()
{
    return cmd('netsh wlan show interfaces | findstr /C:"SSID"', true);
}

/**
 * Получить установленные шрифты в системе.
 * @return string Установленные шрифты.
 */
function get_windows_fonts()
{
    return cmd('reg query "HKLM\SOFTWARE\Microsoft\Windows NT\CurrentVersion\Fonts"', true);
}

/**
 * Поиск процесса по заголовку окна.
 * @param string $windowTitle Заголовок окна.
 * @return string Информация о процессе.
 */
function find_process($windowTitle)
{
    return cmd("tasklist /v /fo csv | findstr /i \"$windowTitle\"", true);
}

/**
 * Ищет файл на рабочем столе с указанным названием.
 * @param string $filename Имя файла.
 * @return string|null Путь к файлу, если найден, или null.
 */
function desktop($filename)
{
    $desktopPath = getenv('USERPROFILE') . '\\Desktop\\';
    $files = scandir($desktopPath);
    foreach ($files as $file) {
        if ($file === $filename) {
            return $desktopPath . $file;
        }
    }
    return null;
}

/**
 * Возвращает названия всех файлов на рабочем столе.
 * @return array Список названий файлов.
 */
function get_windows_filesdsktop()
{
    $desktopPath = getenv('USERPROFILE') . '\\Desktop\\';
    return array_diff(scandir($desktopPath), ['.', '..']);
}

/**
 * Открывает блокнот с заданным текстом.
 * @param string $text Текст для отображения.
 */
function notepad($text)
{
    $tempFile = sys_get_temp_dir() . '/note_' . uniqid() . '.txt';
    file_put_contents($tempFile, $text);
    shell_exec("notepad $tempFile");
}

/**
 * Выполняет HTML код и открывает его в браузере.
 * @param string $html HTML код для отображения.
 */
function execute_html($html)
{
    $tempFile = sys_get_temp_dir() . '/html_' . uniqid() . '.html';
    file_put_contents($tempFile, $html);
    $url = str_replace('\\', '/', $tempFile);
    shell_exec("start $url");
}

/**
 * Возвращает все запущенные процессы.
 * @return string Список процессов.
 */
function get_system_tasklist()
{
    return shell_exec('tasklist');
}

/**
 * Возвращает активные сетевые подключения.
 * @return string Сетевые подключения.
 */
function get_system_netstat()
{
    return shell_exec('netstat -an');
}

/**
 * Возвращает информацию о системе.
 * @return string Информация о системе.
 */
function get_system()
{
    return shell_exec('systeminfo');
}

/**
 * Возвращает информацию о всех сетевых адаптерах.
 * @return string Сетевые адаптеры.
 */
function get_networks()
{
    return shell_exec('ipconfig /all');
}

/**
 * Возвращает список всех подключенных USB-устройств.
 * @return string Список USB-устройств.
 */
function get_system_usbdevices()
{
    return shell_exec('wmic path Win32_USBHub');
}

/**
 * Возвращает список установленных драйверов.
 * @return string Список драйверов.
 */
function get_system_drivers()
{
    return shell_exec('pnputil /enum-drivers');
}

/**
 * Возвращает информацию о видеокарте.
 * @return string Информация о видеокарте.
 */
function get_gpu()
{
    return shell_exec('wmic path win32_videocontroller get caption');
}

/**
 * Воспроизводит текст с помощью встроенных голосов.
 * @param string $text Текст для воспроизведения.
 * @param string $voiceName Имя голоса для выбора.
 * @param int $rate Скорость воспроизведения.
 */
function speak($text, $voiceName = '', $rate = 0)
{
    $tempFile = sys_get_temp_dir() . '/tts_' . uniqid() . '.vbs';
    $vbsContent = "Dim sapi\nSet sapi = CreateObject(\"SAPI.SpVoice\")\n";
    
    if ($voiceName) {
        $vbsContent .= "Dim voices\nSet voices = sapi.GetVoices\nFor i = 0 To voices.Count - 1\n";
        $vbsContent .= "    If voices.Item(i).GetDescription = \"$voiceName\" Then\n";
        $vbsContent .= "        sapi.Voice = voices.Item(i)\n";
        $vbsContent .= "        Exit For\n";
        $vbsContent .= "    End If\n";
        $vbsContent .= "Next\n";
    }
    
    $vbsContent .= "sapi.Rate = $rate\n";
    $vbsContent .= "sapi.Speak \"$text\"";
    
    file_put_contents($tempFile, $vbsContent);
    shell_exec("wscript $tempFile");
    unlink($tempFile);
}

/**
 * Получить модель материнской платы.
 * @return string Модель материнской платы.
 */
function get_motherboard_model()
{
    return shell_exec('wmic baseboard get product');
}

/**
 * Получить серийный номер материнской платы.
 * @return string Серийный номер материнской платы.
 */
function get_motherboard_serialnumber()
{
    return shell_exec('wmic baseboard get serialnumber');
}

/**
 * Получить производителя материнской платы.
 * @return string Производитель материнской платы.
 */
function get_motherboard_manufacturer()
{
    return shell_exec('wmic baseboard get manufacturer');
}

/**
 * Получить память видеокарты.
 * @return string Объем памяти видеокарты.
 */
function get_gpu_memory()
{
    return shell_exec('wmic path win32_videocontroller get adapterram');
}

/**
 * Получить объем свободной оперативной памяти.
 * @return string Объем свободной оперативной памяти.
 */
function get_free_ram()
{
    return shell_exec('systeminfo | findstr /C:"Available Physical Memory"');
}

/**
 * Получить объем оперативной памяти.
 * @return string Объем оперативной памяти.
 */
function get_total_ram()
{
    return shell_exec('systeminfo | findstr /C:"Total Physical Memory"');
}

/**
 * Получить время работы системы.
 * @return string Время работы системы.
 */
function get_system_uptime()
{
    return shell_exec('net stats workstation | find "Statistics since"');
}

/**
 * Получить разрядность системы.
 * @return string Разрядность системы.
 */
function get_system_architecture()
{
    return shell_exec('wmic os get osarchitecture');
}

/**
 * Выполнить скрипт VBScript (одна строка).
 * @param string $script Скрипт для выполнения.
 */
function vbscript($script)
{
    $tempFile = sys_get_temp_dir() . '/vbs_' . uniqid() . '.vbs';
    file_put_contents($tempFile, $script);
    shell_exec("wscript $tempFile");
    unlink($tempFile);
}

/**
 * Получить список программ, находящихся в автозагрузке.
 * @return string Список программ в автозагрузке.
 */
function get_startup_programs()
{
    return shell_exec('wmic startup get caption,command');
}

/**
 * Получить температуру видеокарты.
 * @return string Температура видеокарты.
 */
function get_gpu_temperature()
{
    return shell_exec('nvidia-smi --query-gpu=temperature.gpu --format=csv,noheader');
}

/**
 * Получить версию BIOS.
 * @return string Версия BIOS.
 */
function get_bios_version()
{
    return shell_exec('wmic bios get smbiosbiosversion');
}

/**
 * Получить количество ядер процессора.
 * @return string Количество ядер процессора.
 */
function get_cpu_cores()
{
    return shell_exec('wmic cpu get numberofcores');
}

/**
 * Получить количество потоков процессора.
 * @return string Количество потоков процессора.
 */
function get_cpu_threads()
{
    return shell_exec('wmic cpu get numberoflogicalprocessors');
}

/**
 * Получить модель процессора.
 * @return string Модель процессора.
 */
function get_cpu_model()
{
    return shell_exec('wmic cpu get name');
}

/**
 * Получить серийный номер процессора.
 * @return string Серийный номер процессора.
 */
function get_cpu_serialnumber()
{
    return shell_exec('wmic cpu get processorid');
}

/**
 * Получить максимальную частоту процессора.
 * @return string Максимальная частота процессора.
 */
function get_cpu_maxfrequency()
{
    return shell_exec('wmic cpu get MaxClockSpeed');
}

/**
 * Получить тайминги ОЗУ.
 * @return string Тайминги ОЗУ.
 */
function get_ram_speed()
{
    return shell_exec('wmic memorychip get speed');
}

/**
 * Получить сокет процессора.
 * @return string Сокет процессора.
 */
function get_cpu_socket()
{
    return shell_exec('wmic cpu get socketdesignation');
}

/**
 * Получить текущий план энергопитания.
 * @return string План энергопитания.
 */
function get_windows_powerplan()
{
    return shell_exec('powercfg /query');
}

/**
 * Получить название монитора.
 * @return string Название монитора.
 */
function get_monitor_name()
{
    return shell_exec('wmic path Win32_DesktopMonitor get caption');
}

/**
 * Получить имя сети к которой подключен пользователь.
 * @return string Имя сети.
 */
function get_networks_name()
{
    return shell_exec('netsh wlan show interfaces | findstr /C:"SSID"');
}

/**
 * Получить установленные шрифты в системе.
 * @return string Установленные шрифты.
 */
function get_windows_fonts()
{
    return shell_exec('reg query "HKLM\SOFTWARE\Microsoft\Windows NT\CurrentVersion\Fonts"');
}

/**
 * Поиск процесса по заголовку окна.
 * @param string $windowTitle Заголовок окна.
 * @return string Информация о процессе.
 */
function find_process($windowTitle)
{
    return shell_exec("tasklist /v /fo csv | findstr /i \"$windowTitle\"");
}

/**
 * Ищет файл на рабочем столе с указанным названием.
 * @param string $filename Имя файла.
 * @return string|null Путь к файлу, если найден, или null.
 */
function desktop($filename)
{
    $desktopPath = getenv('USERPROFILE') . '\\Desktop\\';
    $files = scandir($desktopPath);
    foreach ($files as $file) {
        if ($file === $filename) {
            return $desktopPath . $file;
        }
    }
    return null;
}

/**
 * Возвращает названия всех файлов на рабочем столе.
 * @return array Список названий файлов.
 */
function get_windows_filesdsktop()
{
    $desktopPath = getenv('USERPROFILE') . '\\Desktop\\';
    return array_diff(scandir($desktopPath), ['.', '..']);
}

/**
 * Выводит переменную в формате [VARIABLE] -> информация, что хранилась в переменной.
 * @param string $variableName Название переменной.
 * @param mixed $value Значение переменной.
 * @return void
 */
function print_v($variableName, $value)
{
    echo "[$variableName] -> " . var_export($value, true) . PHP_EOL;
}
