# Kirby Resend

![Release](https://flat.badgen.net/packagist/v/bnomei/kirby-resend?color=ae81ff)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby-resend?color=272822)
[![Build Status](https://flat.badgen.net/travis/bnomei/kirby-resend)](https://travis-ci.com/bnomei/kirby-resend)
[![Coverage Status](https://flat.badgen.net/coveralls/c/github/bnomei/kirby-resend)](https://coveralls.io/github/bnomei/kirby-resend) 
[![Maintainability](https://flat.badgen.net/codeclimate/maintainability/bnomei/kirby-resend)](https://codeclimate.com/github/bnomei/kirby-resend) 
[![Twitter](https://flat.badgen.net/badge/twitter/bnomei?color=66d9ef)](https://twitter.com/bnomei)

Send transactional E-Mail with [Resend](https://resend.com)

## Sending via SMTP
```php
<?php

// regular "transactional" email
$to = 'roger.rabbit@disney.com';

$success = kirby()->email([
    'from' => new \Kirby\Cms\User([
        'email' => 'resend@example.com', // your verified resend sender
        'name' => 'Example Name', // your name
    ]),
    'to' => $to,
    'subject' => 'Sending E-Mails is fun',
    'body' => [
        'html' => '<h1>Headline</h1><p>Text</p>',
        'text' => "Headline\n\nText",
    ],
    'transport' => resend()->transport(), // plugin helper to get SMTP config array
])->isSent();
```

## Sending via PHP lib (work in progress)

```php
$client = resend()->client();
$sendResult = $client->emails->send([
    'from' => 'onboarding@resend.dev',
    'to' => 'delivered@resend.dev',
    'subject' => 'Hello World',
    'html' => '<strong>it works!</strong>',
]);

// Getting the ID from the response Email object
// https://github.com/resendlabs/resend-php/blob/main/src/Email.php
echo $sendResult->id;
```

## Sending Kirby Panel emails via Resend

If you want to make Kirby use Resend to send emails like the *password reset* from panel you have to set global `auth` and `email` config values.

**site/config/config.php**
```php
return [
    // …other settings
    'auth' => [
        'methods' => ['password', 'password-reset'],
        'challenge' => [
            'email' => [
                'from'     => 'resend@example.com', // your verified postmark sender
                'fromName' => 'Example Name',
            ]
        ]
    ],
    'email' => [
        'transport' => [
            'type' => 'smtp',
            'host' => 'smtp.resend.com',
            'port' => 587,
            'security' => true,
            'auth' => true,
            'username' => 'resend', // needs to be 'resend'
            'password' => 'YOUR-APIKEY-HERE', // your resend apikey
        ]
    ]
];
```

## Settings

You can set the apikey in the config.

**site/config/config.php**
```php
return [
    // other config settings ...
    'bnomei.resend.apikey' => 'YOUR-APIKEY-HERE',
];
```

You can also set a callback if you use the [dotenv Plugin](https://github.com/bnomei/kirby3-dotenv).

**site/config/config.php**
```php
return [
    // other config settings ...
    'bnomei.resend.apikey' => function() { return env('RESEND_APIKEY_TOKEN'); },
];
```

## Settings

| bnomei.resend. | Default        | Description               |            
|----------------|----------------|---------------------------|
| apikey         | `null callback` |  |

## Dependencies

- [Resend API PHP](https://github.com/resendlabs/resend-php)

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby-resend/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
