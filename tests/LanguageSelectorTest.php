<?php


class LanguageSelectorTest extends TestCase
{
    /** @test */
    public function it_should_set_the_app_language_based_on_hostname()
    {
        $this->host = 'http://example.com';
        $this->mockRequest('example/url');

        $this->assertEquals('fi-FI', \Yii::$app->language);

        //destroy the yii instance
        parent::tearDown();

        $this->host = 'http://foobar.se';
        $this->mockRequest('example/url');

        $this->assertEquals('se-SE', \Yii::$app->language);

    }

    /** @test */
    public function it_should_default_to_set_language_if_the_host_is_not_found_in_config()
    {
        $this->host = 'http://notconfigured.com';
        $this->mockRequest('example/url');

        $this->assertEquals('en-US', \Yii::$app->language);
    }

}