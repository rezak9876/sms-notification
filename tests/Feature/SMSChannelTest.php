<?php

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Rezak\SMSNotification\SMSChannel;
use Rezak\SMSNotification\SMSMessage;

class User
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForSMS()
    {
        return '09123456789'; // Return the user's phone number for SMS notifications
    }

    public function getKey()
    {
        return 1; // Unique identifier for the user
    }

    public function getMorphClass()
    {
        return 'users'; // Optional, can return the morph type for the user
    }
}

class TestSMSNotification extends Notification
{
    public function via($notifiable)
    {
        return [SMSChannel::class]; // Specify the channels to be used for the notification
    }

    public function toSMS($notifiable)
    {
        $message = new SMSMessage();
        $message->setTemplate('test_template')
                ->setData(['param1' => 'value1']); // Set the template and data for the SMS
        return $message;
    }
}

it('works with Laravel notification system', function () {
    NotificationFacade::fake(); // Prevent actual notifications from being sent

    $user = new User(); // Create a new user instance
    $notification = new TestSMSNotification(); // Create an instance of the SMS notification

    NotificationFacade::send($user, $notification); // Send the notification

    NotificationFacade::assertSentTo($user, TestSMSNotification::class); // Assert that the notification was sent
});
