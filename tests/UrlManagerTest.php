<?php

use yii\helpers\Url;

class UrlManagerTest extends TestCase
{
    public function tearDown()
    {
        parent::tearDown();
    }

    /** @test */
    public function it_can_create_an_url_to_different_language()
    {
        $this->host = 'http://example.com';
        $this->mockRequest('example/url');
        $this->assertEquals('fi-FI', \Yii::$app->language);

        $this->assertEquals('http://foobar.se/example/url', Url::to(['/example/url', 'language' => 'se-SE'], true));
        $this->assertEquals('http://foobar.se/example/url?x=y',
            Url::to(['/example/url', 'language' => 'se-SE', 'x' => 'y'], true));
    }

    /** @test */
    public function it_defaults_to_current_host_if_no_such_language_configured()
    {
        $this->host = 'http://example.com';
        $this->mockRequest('example/url');

        $this->assertEquals('http://example.com/example/asd', Url::to(['/example/asd', 'language' => 'de-DE'], true));
        $this->assertEquals('http://example.com/example/asd?foo=bar&x=3',
            Url::to(['/example/asd', 'language' => 'de-DE', 'foo' => 'bar', 'x' => 3], true));
    }

    /** @test */
    public function it_also_works_when_show_script_name_is_true()
    {
        $this->host = 'http://example.com';
        $this->showScriptName = true;
        $this->mockRequest('example/url');

        $this->assertEquals('http://foobar.se/index.php/example/url',
            Url::to(['/example/url', 'language' => 'se-SE'], true));
        $this->assertEquals('http://foobar.se/index.php/example/url?x=y',
            Url::to(['/example/url', 'language' => 'se-SE', 'x' => 'y'], true));
    }
}
