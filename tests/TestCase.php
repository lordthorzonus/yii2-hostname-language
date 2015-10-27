<?php

use leinonen\HostnameLanguage\LanguageSelector;
use leinonen\HostnameLanguage\UrlManager;
use yii\di\Container;
use yii\helpers\ArrayHelper;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string the base URL to use
     */
    protected $baseUrl = '';

    /**
     * @var bool
     */
    protected $showScriptName = false;

    /**
     * @var string the host to use
     */
    protected $host = 'http://myhost.com';

    /**
     * Destroy Yii app singleton, DI container, session and cookies.
     */
    protected function tearDown()
    {
        $_COOKIE = [];
        \Yii::$app->session->destroy();
        \Yii::$app = null;
        \Yii::$container = new Container();
        parent::tearDown();
    }

    /**
     * Mocks a HTTP request.
     *
     * This will set all required variables in the PHP environment to mock a HTTP
     * request with the given URL. It will then initialize a Yii web app and let
     * it resolve the request.
     *
     * @param string $url the relative request URL
     * @param array $config optional configuration for the `request` application component
     */
    protected function mockRequest($url, $config = [])
    {
        $url = $this->prepareUrl($url);
        $_SERVER['REQUEST_URI'] = $url;
        $_SERVER['SERVER_NAME'] = $this->baseUrl;
        $_SERVER['SCRIPT_NAME'] = $this->baseUrl . '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = __DIR__ . $this->baseUrl . '/index.php';
        $_SERVER['DOCUMENT_ROOT'] = __DIR__;
        $parts = explode('?', $url);
        if (isset($parts[1])) {
            $_SERVER['QUERY_STRING'] = $parts[1];
        }
        if ($config !== []) {
            $config = [
                'components' => [
                    'request' => $config,
                ],
            ];
        }
        $this->mockYiiApplication($config);
        Yii::$app->request->resolve();
    }

    /**
     * Helper method for creating the right mock url.
     *
     * @param string $url
     *
     * @return string
     */
    protected function prepareUrl($url)
    {
        if ($this->showScriptName) {
            $url = '/index.php' . $url;
        }

        return $this->baseUrl . $url;
    }

    /**
     * Mocks a Yii web application.
     *
     * This will create a new Yii application object and configure it with some default options.
     * Extra configuration passed via `$config` will override any of the above options.
     *
     * @param array $config application configuration
     * @param string $appClass default is `\yii\web\Application`
     */
    protected function mockYiiApplication($config = [], $appClass = '\yii\web\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'language' => 'en-US',
            'bootstrap' => ['hostlanguage'],
            'basePath' => __DIR__,
            'vendorPath' => __DIR__ . '/../vendor/',
            'components' => [
                'request' => [
                    'enableCookieValidation' => false,
                    'isConsoleRequest' => false,
                    'hostInfo' => $this->host,
                ],
                'hostlanguage' => [
                    'class' => LanguageSelector::class,
                    'hosts' => [
                        'example.com' => 'fi-FI',
                        'foobar.se' => 'se-SE',
                    ],
                ],
                'urlManager' => [
                    'class' => UrlManager::class,
                    'enablePrettyUrl' => true,
                    'showScriptName' => $this->showScriptName,
                ],
            ],
        ], $config));
    }
}
