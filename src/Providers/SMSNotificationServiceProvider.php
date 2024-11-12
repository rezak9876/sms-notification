<?php

namespace Rezak\SMSNotification\Providers;

use Illuminate\Support\ServiceProvider;
use Rezak\SMSNotification\Channels\SMSChannel;
use Illuminate\Notifications\ChannelManager;
use Rezak\SMSNotification\Services\SMSService\KavenegarSMSService;
use Kavenegar\KavenegarApi as KavenegarApi;
use Illuminate\Support\Facades\Notification;
use Kavenegar\Laravel\Channel\KavenegarChannel;

class SMSNotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->bindSMSService();
        $this->mergeConfig();
        $this->registerKavenegarApi();
    }

    public function boot(): void
    {
        $this->extendNotificationChannel();
        $this->publishConfig();
    }

    protected function bindSMSService(): void
    {
        $this->app->bind(SMSServiceInterface::class, function ($app) {
            $config = $this->getSMSConfig();
            $smsServiceClass = $config['class'];

            return new $smsServiceClass($config);
        });
    }

    protected function mergeConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/sms.php', 'sms');
    }

    protected function registerKavenegarApi(): void
    {
        $this->app->singleton('kavenegar', function ($app) {
            return new KavenegarApi($app['config']->get('sms.drivers.kavenegar.token'));
        });
    }

    protected function extendNotificationChannel(): void
    {
        $this->app->make(ChannelManager::class)->extend('SMS', function ($app) {
            return new SMSChannel($app->make(SMSServiceInterface::class));
        });
    }

    protected function publishConfig(): void
    {
        $this->publishes([__DIR__ . '/../../config/sms.php' => config_path('sms.php')]);
    }

    protected function getSMSConfig(): array
    {
        $driver = config('sms.default');
        return config("sms.drivers.$driver");
    }
}
