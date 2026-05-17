<?php
namespace spark\bundle\httpclient\components;

use bundle\http\HttpClient;
use ide\scripts\AbstractScriptComponent;
use script\MailScript;

class HttpClientComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof HttpClientComponent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'HTTP Клиент';
    }

    public function getIcon()
    {
        return 'spark/bundle/httpclient/httpClient16.png';
    }

    public function getIdPattern()
    {
        return "httpClient%s";
    }

    public function getGroup()
    {
        return 'Интернет и сеть';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return HttpClient::class;
    }

    public function getDescription()
    {
        return null;
    }
}