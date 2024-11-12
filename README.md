# **rezak/laravel-sms-channel**

A Laravel package that provides a flexible SMS channel integration supporting multiple SMS services, including Kavenegar, to send SMS notifications.

## **Installation**

You can install the package via Composer:

```bash
composer require rezak/laravel-sms-channel:dev-master
```

## **Publishing Vendor Files**

After installing, publish the configuration file by running the following command:

```bash
php artisan vendor:publish --provider="Rezak\\SMSNotification\\Providers\\SMSNotificationServiceProvider"
```

This command will publish the package's configuration files into your Laravel project, allowing you to customize the SMS service settings as needed.

## **Configuration**

Add the following to your `config/services.php`:

```php
<?php

return [
    'default' => env('SMS_DRIVER', 'mock'),
    'drivers' => [
        'mock' => [
            'class' => \\Rezak\\SMSNotification\\Services\\SMSService\\MockSMSService::class,
        ],
        'kavenegar' => [
            'class' => \\Rezak\\SMSNotification\\Services\\SMSService\\KavenegarSMSService::class,
            'token' => env('KAVENEGAR_API_TOKEN'),
            'sender'=> "10004346"
        ]
    ],
];
```

## **Usage**

### **1. Add the `routeNotificationForSMS` method to your User model**

In order to use the SMS channel for notifications, you need to add the following method to your `User` model:

```php
public function routeNotificationForSMS()
{
    return $this->phone;
}
```

This method should return the phone number of the user.

### **2. Create a Notification**

Create a notification class to send an OTP (or any SMS message). Here's an example:

```php
<?php

namespace App\\Notifications;

use Illuminate\\Notifications\\Notification;
use Rezak\\SMSNotification\\Messages\\SMSMessage;

class SendOtpNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return ['SMS'];
    }

    public function toSMS(object $notifiable): SMSMessage
    {
        $message = new SMSMessage();
        $message->setTemplate('test_template')
                ->setData(['param1' => 'value1']);

        return $message;
    }
}
```

### **3. Sending the Notification**

Send the notification to a specific user:

```php
$user = User::find(1);
$otpCode = rand(100000, 999999);
$user->notify(new SendOtpNotification($otpCode));
```

Alternatively, send the notification to multiple phone numbers:

```php
use Illuminate\\Support\\Facades\\Notification;

Notification::route('SMS', ['first_phone', 'second_phone'])
    ->notify(new SendOtpNotification('otp code'));
```

### **4. Customizing the SMS Message**

Use predefined templates (via `setTemplate()`) or send custom content (via `setContent()`).

Example:

```php
$message = new SMSMessage();
$message->setContent('This is a test message.');
return $message;
```

## **Supported SMS Services**

- **Kavenegar**: API integration for sending SMS messages.

You can extend the package to support more SMS services by adding the appropriate driver.

## **Environment Configuration**

Make sure to add your API key to the `.env` file:

```env
KAVENEGAR_API_KEY=your-api-key
```

## **License**

This package is open-sourced software licensed under the MIT license.
