<?php

namespace Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use Rezak\SMSNotification\SMSServiceInterface;

beforeEach(function () {
    $this->mockSMSService = $this->createMock(SMSServiceInterface::class);
});

it('can set the template name', function () {
    $templateName = 'welcome';
    
    $this->mockSMSService
        ->method('setTemplateName')
        ->with($templateName)
        ->willReturnSelf();

    $result = $this->mockSMSService->setTemplateName($templateName);
    
    expect($result)->toBe($this->mockSMSService);
});

it('can set the phone number', function () {
    $phoneNumber = '1234567890';
    
    $this->mockSMSService
        ->method('setPhone')
        ->with($phoneNumber)
        ->willReturnSelf();

    $result = $this->mockSMSService->setPhone($phoneNumber);
    
    expect($result)->toBe($this->mockSMSService);
});

it('can set a token', function () {
    $key = 'api_key';
    $value = 'secret_value';
    
    $this->mockSMSService
        ->method('setToken')
        ->with($key, $value)
        ->willReturnSelf();

    $result = $this->mockSMSService->setToken($key, $value);
    
    expect($result)->toBe($this->mockSMSService);
});

it('can send a templated SMS', function () {
    $this->mockSMSService
        ->method('sendTemplatedSMS')
        ->willReturn(true);

    $result = $this->mockSMSService->sendTemplatedSMS();
    
    expect($result)->toBeTrue();
});

it('can send a raw SMS', function () {
    $body = 'Hello, this is a test message.';
    
    $this->mockSMSService
        ->method('sendRawSMS')
        ->with($body)
        ->willReturn(true);

    $result = $this->mockSMSService->sendRawSMS($body);
    
    expect($result)->toBeTrue();
});
