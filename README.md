# Yii2 Hostname Language Component #
[![Build Status](https://travis-ci.org/lordthorzonus/yii2-hostname-language.svg?branch=master)](https://travis-ci.org/lordthorzonus/yii2-hostname-language)

This simple component sets the Yii's app locale based on the requests hostname. So for example, if you have **my-awesome-app.com** which is English version of your site and **my-awesome-app.fi** which is Finnish and they both point to the same Yii2 app, this component sets the app language automatically on every request. 

## Configuration ##

The component is configured in the main config. The pattern for configuring the hosts is `'hostname' => 'language'`. The scheme of the request (http/https) must be left out.

```php
use leinonen\HostnameLanguage\LanguageSelector;

'bootstrap' => ['hostLanguage'],
'components' => [
    'hostLanguage' => [
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
use leinonen\HostnameLanguage\UrlManager;

'components' => [
    'urlManager' => [ 
      'class' => UrlManager::class,
      'enablePrettyUrl' => true,
      'showScriptName' => false,
    ]
]   
```

It then works by feeding a `language` parameter to the URL helper function: 
```php
Url::to(['site/my-route', 'language' => 'en-US'], true);
```
Remember to set scheme param to **true**, cause you need to create absolute urls. If you are already using an url parameter called language it can easily be changed with the config: 
```php
'urlManager' => [ 
    ...
    'languageParam' => 'lang'
]
```

And then: 
```php 
Url::to(['site/my-route', 'lang' => 'en-US'], true);
```
