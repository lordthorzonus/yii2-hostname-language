# Yii2 Hostname Language Component #

This simple component sets the Yii's app locale based on the requests hostname. So for example, if you have **my-awesome-app.com** which is English version of your site and **my-awesome-app.fi** which is Finnish and they both point to the same Yii2 app, this component sets the app language automatically on every request. 

## Configuration ##

The component is configured in the main config. The pattern for configuring the hosts is `'hostname' => 'language'`. The scheme of the request (http/https) must be left out.

```php
<?php

use leinonen\HostnameLanguage\LanguageSelector;

'bootstrap' => ['hostlanguage'],
'components' => [
  'hostlanguage' => [
    'class' => LanguageSelector::class,
    'hosts' => [
       'example.com' => 'fi-FI',
       'foobar.se' => 'se-SE',
    ]
  ]
],

```
### URL Manager ###

You can also configure a custom URL manager which allows creating urls to another language version of your site. This extends the `\yii\web\UrlManager` so all the normal features are also available.

```php
<?php

use leinonen\HostnameLanguage\UrlManager;

'components' => [
  'urlManager' => [ 
     'class' => UrlManager::class,
     'enablePrettyUrl' => true,
     'showScriptName' => false,
  ]
]   
```

It then works by feeding a `language` parameter to the URL helper function: `Url::to(['site/my-route', 'language' => 'en-US'], true);`. Remember to set scheme param to true, cause we need absolute urls. If you are already using an url parameter called language it can easily be changed it with the config: 
```php
<?php
'urlManager' => [ 
  ...
  'languageParam' => 'lang'
]
```

And then: `Url::to(['site/my-route', 'lang' => 'en-US'], true)`;