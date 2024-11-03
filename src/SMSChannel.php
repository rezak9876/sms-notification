<?php

namespace Rezak\SMSNotification;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Notifications\NotificationSender;
use Illuminate\Support\Facades\App;

class SMSChannel
{
    protected SMSServiceInterface $SMSService;

    public function __construct(SMSServiceInterface $SMSService)
    {
        $this->SMSService = $SMSService;
    }

    public function send($notifiable, Notification $notification): void
    {
        $SMSmessage = $notification->toSMS($notifiable);

        if($SMSmessage->isTemplated()){
            $this->SMSService
                ->setTemplateName($SMSmessage->getTemplate())
                ->setPhone($notifiable->routeNotificationForSMS());

            foreach ($SMSmessage->getData() ?? [] as $key => $value) {
                $this->SMSService->setToken($key, $value);
            }

            $this->SMSService->sendTemplatedSMS();
        }
    }
}
