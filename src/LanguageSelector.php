<?php


namespace leinonen\HostnameLanguage;

use yii\base\BootstrapInterface;

class LanguageSelector implements BootstrapInterface
{
    /**
     * @var array
     * The hosts configuration for this component. Configured in the application components following manner:
     * 'hostlanguage' => [
     *      'class' => LanguageSelector::class,
     *      'hosts' => [
     *          'example.com' => 'fi-FI',
     *          'foobar.se' => 'se-SE',
     *      ]
     * ]
     * Also remember to bootstrap the component.
     */
    public $hosts = [];

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        //Bootstrap the current configurations
        $app->set(LanguageSelector::class, $this);

        if (!empty($this->hosts)) {
            $url = parse_url($app->request->hostInfo);
            $host = $url['host'];

            if (isset($this->hosts[$host])) {
                $app->language = $this->hosts[$host];
            }
        }
    }

}
