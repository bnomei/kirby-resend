<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('bnomei/resend', [
    'options' => [
        'username' => 'resend', // or callback
        'apikey' => null, // or callback
        'trap' => null, // or callback
        'email' => [
            'transport' => [
                'type' => 'smtp',
                'host' => 'smtp.resend.com',
                'port' => 587,
                'security' => 'tsl',
                'auth' => true,
//                'username' => null, // will default to username
//                'password' => null, // will default to apikey
            ]
        ],
        'cache' => true,
        'expires' => 1, // minutes
    ],
]);

if (!class_exists('Bnomei\Resend')) {
    require_once __DIR__ . '/classes/Resend.php';
}

if (!function_exists('resend')) {
    function resend()
    {
        return \Bnomei\Resend::singleton();
    }
}
