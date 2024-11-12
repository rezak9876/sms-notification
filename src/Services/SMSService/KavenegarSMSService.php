<?php

namespace Rezak\SMSNotification\Services\SMSService;

use Rezak\SMSNotification\Contracts\SMSServiceInterface;
use Kavenegar\Laravel\Facade as Kavenegar;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;

class KavenegarSMSService implements SMSServiceInterface
{
    protected string $phone;
    protected string $templateName;
    protected array $tokens = [];

    public function setPhones(string|array $phones): static
    {
        $this->phones = $phones;
        return $this;
    }

    public function setTemplateName(string $templateName): static
    {
        $this->templateName = $templateName;
        return $this;
    }

    public function setTemplateParams(array $params): static
    {
        $this->tokens = array_values($params);
        return $this;
    }

    public function sendTemplatedSMS(): bool
    {
        try {
            $this->sendSMSWithTemplate();
        } catch (ApiException | HttpException $e) {
            return false;
        }
        return true;
    }

    public function sendRawSMS(string $body): bool
    {
        try {
            $this->sendSMSRaw($body);
        } catch (ApiException | HttpException $e) {
            return false;
        }
        return true;
    }

    protected function sendSMSWithTemplate(): void
    {
        $receptor = $this->phones;
        $template = $this->templateName;
        $token = $this->tokens[0] ?? null;
        $token2 = $this->tokens[1] ?? null;
        $token3 = $this->tokens[2] ?? null;

        Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template);
    }

    protected function sendSMSRaw(string $body): void
    {
        $sender = config('sms.drivers.kavenegar.sender');
        $receptor = $this->phones;

        Kavenegar::Send($sender, $receptor, $body);
    }
}
