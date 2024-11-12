<?php

use Rezak\SMSNotification\SMSMessage;

it('can set and get template', function () {
    $sms = new SMSMessage();
    $sms->setTemplate('welcome_template');
    
    expect($sms->getTemplate())->toBe('welcome_template');
});

it('can set and get data', function () {
    $sms = new SMSMessage();
    $data = ['name' => 'John Doe', 'code' => '1234'];
    $sms->setData($data);
    
    expect($sms->getData())->toBe($data);
});

it('can set and get body', function () {
    $sms = new SMSMessage();
    $sms->setBody('This is a test message.');
    
    expect($sms->getBody())->toBe('This is a test message.');
});

it('throws exception when getting body if not set', function () {
    $sms = new SMSMessage();
    
    expect(fn() => $sms->getBody())->toThrow(InvalidArgumentException::class, "Body has not been set.");
});

it('can check if template is set', function () {
    $sms = new SMSMessage();
    
    expect($sms->isTemplated())->toBeFalse();
    
    $sms->setTemplate('welcome_template');
    
    expect($sms->isTemplated())->toBeTrue();
});
