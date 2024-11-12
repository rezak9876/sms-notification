<?php

use Illuminate\Notifications\Notification;
use Rezak\SMSNotification\SMSChannel;
use Rezak\SMSNotification\SMSServiceInterface;
use Rezak\SMSNotification\SMSMessage;

beforeEach(function () {
    $this->SMSServiceMock = $this->createMock(SMSServiceInterface::class);
    $this->SMSChannel = new SMSChannel($this->SMSServiceMock);
});

function createNotifiable($phone = '09123456789')
{
    return new class($phone) {
        private $phone;

        public function __construct($phone) {
            $this->phone = $phone;
        }

        public function routeNotificationForSMS() {
            return $this->phone;
        }
    };
}

function createNotification($template = null, $body = null, $data = [])
{
    return new class($template, $body, $data) extends Notification {
        private $template;
        private $body;
        private $data;

        public function __construct($template, $body, $data) {
            $this->template = $template;
            $this->body = $body;
            $this->data = $data;
        }

        public function via($notifiable) {
            return [SMSChannel::class];
        }

        public function toSMS($notifiable) {
            $message = new SMSMessage();
            if ($this->template) {
                $message->setTemplate($this->template)->setData($this->data);
            }
            if ($this->body) {
                $message->setBody($this->body);
            }
            return $message;
        }
    };
}

it('sends templated SMS notification', function () {
    $this->SMSServiceMock
        ->expects($this->once())
        ->method('setTemplateName')
        ->with('test_template');

    $this->SMSServiceMock
        ->expects($this->once())
        ->method('setPhone')
        ->with('09123456789');

    $this->SMSServiceMock
        ->expects($this->once())
        ->method('sendTemplatedSMS')
        ->willReturn(true);

    $notifiable = createNotifiable();
    $notification = createNotification('test_template', null, ['param1' => 'value1']);

    $this->SMSChannel->send($notifiable, $notification);
});

it('handles non-templated SMS notifications gracefully', function () {
    $this->SMSServiceMock
        ->expects($this->never())
        ->method('setTemplateName');

    $this->SMSServiceMock
        ->expects($this->once())
        ->method('setPhone')
        ->with('09123456789');

    $this->SMSServiceMock
        ->expects($this->never())
        ->method('sendTemplatedSMS');

    $notifiable = createNotifiable();
    $notification = createNotification(null, 'This is a raw SMS');

    $this->SMSChannel->send($notifiable, $notification);
});

it('throws an exception when no phone number is provided', function () {
    $notifiable = createNotifiable(null);
    $notification = createNotification(null, 'This is a non-templated SMS message.');

    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage("No phone number provided for SMS notification.");

    $this->SMSChannel->send($notifiable, $notification);
});

it('handles exceptions thrown by the SMS service', function () {
    $this->SMSServiceMock
        ->expects($this->once())
        ->method('sendTemplatedSMS')
        ->willThrowException(new \Exception('SMS service failed'));

    $notifiable = createNotifiable();
    $notification = createNotification('test_template', null, ['param1' => 'value1']);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('SMS service failed');

    $this->SMSChannel->send($notifiable, $notification);
});

it('sends SMS with multiple data tokens', function () {
    $tokens = [];
    $this->SMSServiceMock
        ->expects($this->once())
        ->method('setTemplateName')
        ->with('test_template');

    $this->SMSServiceMock
        ->expects($this->once())
        ->method('setPhone')
        ->with('09123456789');

    $this->SMSServiceMock
        ->expects($this->exactly(2))
        ->method('setToken')
        ->willReturnCallback(function ($key, $value) use (&$tokens) {
            $tokens[] = [$key, $value];
            return $this->SMSServiceMock;
        });

    $this->SMSServiceMock
        ->expects($this->once())
        ->method('sendTemplatedSMS');

    $notifiable = createNotifiable();
    $notification = createNotification('test_template', null, ['param1' => 'value1', 'param2' => 'value2']);

    $this->SMSChannel->send($notifiable, $notification);

    expect($tokens)->toBe([['param1', 'value1'], ['param2', 'value2']]);
});
