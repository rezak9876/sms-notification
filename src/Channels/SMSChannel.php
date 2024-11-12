<?php

namespace Rezak\SMSNotification\Channels;

use Illuminate\Notifications\Notification;
use InvalidArgumentException;
use Rezak\SMSNotification\Contracts\SMSServiceInterface;

class SMSChannel
{
    protected SMSServiceInterface $SMSService;

    public function __construct(SMSServiceInterface $SMSService)
    {
        $this->SMSService = $SMSService;
    }

    public function send($notifiable, Notification $notification): void
    {
        $phones = $this->getPhoneNumbers($notifiable);

        $SMSmessage = $notification->toSMS($notifiable);

        $this->sendMessage($phones, $SMSmessage);
    }

    protected function getPhoneNumbers($notifiable): string|array
    {
        $phones = $notifiable->routeNotificationFor('SMS');
        
        if (!$phones) {
            throw new InvalidArgumentException("No phone number provided for SMS notification.");
        }

        return $phones;
    }

    protected function sendMessage(string|array $phone, $SMSmessage): void
    {
        $this->SMSService->setPhones($phone);

        if ($SMSmessage->isTemplated()) {
            $this->sendTemplatedSMS($SMSmessage);
        } else {
            $this->sendRawSMS($SMSmessage);
        }
    }

    protected function sendTemplatedSMS($SMSmessage): void
    {
        $this->SMSService->setTemplateName($SMSmessage->getTemplate());
        $this->SMSService->setTemplateParams($SMSmessage->getData());
        $this->SMSService->sendTemplatedSMS();
    }

    protected function sendRawSMS($SMSmessage): void
    {
        $this->SMSService->sendRawSMS($SMSmessage->getContent());
    }
}
