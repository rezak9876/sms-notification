<?php

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Rezak\SMSNotification\SMSChannel;
use Rezak\SMSNotification\SMSMessage;
use Rezak\SMSNotification\SMSServiceInterface;
use Tests\TestCase;

beforeEach(function () {
    $this->SMSServiceMock = $this->createMock(SMSServiceInterface::class);
    $this->SMSChannel = new SMSChannel($this->SMSServiceMock);
});

it('sends SMS notification', function () {
    $this->SMSServiceMock->expects($this->once())
        ->method('setTemplateName')
        ->with('test_template');

    $this->SMSServiceMock->expects($this->once())
        ->method('setPhone')
        ->with('09123456789');

    $this->SMSServiceMock->expects($this->once())
        ->method('sendTemplatedSMS')
        ->willReturn(true); // Simulate successful sending

    NotificationFacade::fake();

    // Create a notifiable with the required methods
    $notifiable = new class {
        public function routeNotificationForSMS()
        {
            return '09123456789';
        }

        public function getKey()
        {
            return 1; // Or any unique identifier
        }

        public function getMorphClass()
        {
            return 'notifiable'; // Optional: specify the morph class
        }
    };

    // Define the notification
    $notification = new class extends Notification {
        public function via($notifiable)
        {
            return ['SMS'];
        }

        public function toSMS($notifiable)
        {
            $message = new SMSMessage();
            $message->setTemplate('test_template')
                    ->setData(['param1' => 'value1']);
            return $message;
        }
    };

    // Send the notification
    NotificationFacade::send($notifiable, $notification);

    // Call the SMSChannel send method
    $this->SMSChannel->send($notifiable, $notification);

    // Assert that the notification was sent
    NotificationFacade::assertSentTo($notifiable, get_class($notification));
});
