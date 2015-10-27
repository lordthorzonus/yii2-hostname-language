<?php

namespace leinonen\HostnameLanguage;

use Yii;

class UrlManager extends \yii\web\UrlManager
{
    /**
     * @var string
     * A configurable language parameter that is used when creating urls
     * If a parameter with this name is passed to the createUrl() method an url with the corresponding host
     * to that language is created. Ie. with this you can create urls to different language variatons of your app.
     */
    public $languageParam = 'language';

    /**
     * @var LanguageSelector
     */
    private $languageSelector;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->languageSelector = Yii::$app->get(LanguageSelector::class);
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function createAbsoluteUrl($params, $scheme = null)
    {
        if (isset($params[$this->languageParam])) {
            $language = $params[$this->languageParam];

            //Remove the language parameter so it doesn't get appended to the url
            unset($params[$this->languageParam]);

            $host = $this->getLanguageHost($language);

            if ($host !== null) {
                $url = parse_url($this->createAbsoluteUrl($params));
                $urlToLanguageVersion = $url['scheme'] . '://' . $host . $url['path'];

                if (isset($url['query'])) {
                    $urlToLanguageVersion .= '?' . $url['query'];
                }

                return $urlToLanguageVersion;
            }
        }

        return parent::createAbsoluteUrl($params, $scheme);
    }

    /**
     * Retrieves the host specified for the corresponding language from the config.
     *
     * @param $language
     *
     * @return string|null
     */
    private function getLanguageHost($language)
    {
        $hosts = $this->languageSelector->hosts;

        foreach ($hosts as $host => $hostLanguage) {
            if ($language === $hostLanguage) {
                return $host;
            }
        }

        return;
    }
}
