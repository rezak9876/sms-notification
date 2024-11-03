<?php

namespace Rezak\SMSNotification;

use Illuminate\Support\ServiceProvider;
use Rezak\KavenegarSMS\KavenegarSMSService;

class SMSNotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load the configuration file
        $this->publishes([
            __DIR__.'/../config/SMS.php' => config_path('SMS.php'),
        ], 'SMS-config');

        // Register the SMS notification channel
        $this->app['Illuminate\Notifications\ChannelManager']->extend('SMS', function ($app) {
            return new SMSChannel(new KavenegarSMSService(config('SMS.kavenegar_token')));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge the package's config file with the application's config
        $this->mergeConfigFrom(__DIR__.'/../config/SMS.php', 'SMS');
    }
}
