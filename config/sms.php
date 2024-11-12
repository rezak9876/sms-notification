<?php

return [
    'default' => env('SMS_DRIVER', 'mock'),
    'drivers' => [
        'mock' => [
            'class' => \Rezak\SMSNotification\Services\SMSService\MockSMSService::class,
        ],
        'kavenegar' => [
            'class' => \Rezak\SMSNotification\Services\SMSService\KavenegarSMSService::class,
            'token' => env('KAVENEGAR_API_TOKEN'),
            'sender'=> "10004346"
        ]
    ],
];
