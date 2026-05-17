<?php
namespace ide;

use ide\l10n\L10n;
use ide\systems\IdeSystem;
use php\lib\fs;
use php\util\Configuration;
use React\EventLoop\Factory;
use React\Promise\PromiseInterface;
use React\Promise\Deferred;

class IdeLanguage
{
    private $code;
    private $l10n;
    private $directory;

    private $title;
    private $titleEn;

    private $restartMessage;

    private $altLang;

    function __construct($code, $directory)
    {
        $this->code = $code;
        $this->l10n = new L10n();
        $this->l10n->setLanguage($code);

        $this->directory = $directory;

        $config = new Configuration("$directory/description.ini");
        $this->title = $config->get('name');
        $this->titleEn = $config->get('name.en');
        $this->altLang = $config->get('alt.lang');

        $this->restartMessage = $config->get('restart.message');
        $this->restartYes = $config->get('restart.yes');
        $this->restartNo = $config->get('restart.no');
    }

    public function loadAsync()
    {
        $deferred = new Deferred();
        $loop = Factory::create();

        $loop->futureTick(function() use ($deferred) {
            if (fs::isFile($file = "$this->directory/messages.ini")) {
                $this->l10n->putFile($file);
                $deferred->resolve();
            } else {
                $deferred->reject(new \Exception("File not found: $file"));
            }
        });

        $loop->run();

        return $deferred->promise();
    }

    public function getDirectory() { return $this->directory; }
    public function getAltLang() { return $this->altLang; }
    public function getCode() { return $this->code; }
    public function getTitle() { return $this->title; }
    public function getTitleEn() { return $this->titleEn; }
    public function getRestartMessage() { return $this->restartMessage; }
    public function getRestartNo() { return $this->restartNo; }
    public function getRestartYes() { return $this->restartYes; }

    public function getIcon() {
        if (fs::isFile($file = "$this->directory/icon.png")) {
            return $file;
        }
        return null;
    }

    public function getBigIcon() {
        if (fs::isFile($file = "$this->directory/icon32.png")) {
            return $file;
        }
        return null;
    }

    public function getL10n(L10n $altLanguage = null) {
        if ($altLanguage) {
            $this->l10n->setAlternatives([$altLanguage]);
            return $this->l10n;
        } else {
            return $this->l10n;
        }
    }

    public function load() {
        if (fs::isFile($file = "$this->directory/messages.ini")) {
            $this->l10n->putFile($file);
        }
    }
}
