<?php

namespace Rezak\SMSNotification\Services\SMSService;

use Rezak\SMSNotification\Contracts\SMSServiceInterface;
use Illuminate\Support\Facades\Log;

class MockSMSService implements SMSServiceInterface
{
    protected string $phone;
    protected string $templateName;
    protected array $params = [];

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
        $this->params = array_values($params);
        return $this;
    }

    public function sendTemplatedSMS(): bool
    {
        Log::info("Sending templated SMS", [
            'recipients' => is_array($this->phones) ? implode(', ', $this->phones) : $this->phones,
            'template'   => $this->templateName,
            'params'     => $this->params,
            'timestamp'  => now()->toDateTimeString()
        ]);
        return true;
    }

    public function sendRawSMS(string $body): bool
    {
        Log::info("Sending raw SMS", [
            'recipients' => implode(', ', $this->phones),
            'message'    => $body,
            'timestamp'  => now()->toDateTimeString()
        ]);
        return true;
    }

}
