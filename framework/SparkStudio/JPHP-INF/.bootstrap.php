<?php

use ide\editors\FormEditor;
use ide\formats\FormFormat;
use ide\Ide;
use ide\IdeClassLoader;
use ide\Logger;
use ide\systems\IdeSystem;
use php\gui\UXDialog;
use php\gui\text\UXFont;
use php\io\Stream;
use php\lang\System;

$cache = !System::getProperty('spark.noCodeCache');

$loader = new IdeClassLoader($cache, IdeSystem::getOwnLibVersion());
$loader->register(true);

IdeSystem::setLoader($loader);

if (!IdeSystem::isDevelopment()) {
    Logger::setLevel(Logger::LEVEL_INFO);
}

$app = new Ide();

$toCartoon = Stream::of(System::getProperty('spark.path') . '/framework/SparkStudio/.theme/techone/cartoon.ttf');
UXFont::load($toCartoon, 14);
$toCartoon->close();

$toNotes = Stream::of(System::getProperty('spark.path') . '/framework/SparkStudio/.theme/techone/notes.ttf');
UXFont::load($toNotes, 14);
$toNotes->close();

$app->addStyle('/.theme/style.css');
$app->addStyle('/.theme/custom.css');
$app->addStyle('/.theme/techone-fx.css');
$app->addStyle('/.theme/ASC.css');

$app->launch();

function _($code, ...$args) {
    static $l10n;

    if (!$l10n) {
        $l10n = Ide::get()->getL10n();
    }

    return $l10n->get($code, ...$args);
}

function dump($arg)
{
    ob_start();
    var_dump($arg);
    $str = ob_get_contents();
    ob_end_clean();

    UXDialog::showAndWait($str);
}

/**
 * @param $name
 * @return \php\gui\UXImageView
 */
function ico($name)
{
    return Ide::get()->getImage("icons/$name.png");
}
