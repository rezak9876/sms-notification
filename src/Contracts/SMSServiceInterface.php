<?php

namespace Rezak\SMSNotification\Contracts;

interface SMSServiceInterface
{
    public function setPhones(string|array $phone): static;

    public function setTemplateName(string $templateName): static;

    public function setTemplateParams(array $params): static;

    public function sendTemplatedSMS(): bool;

    public function sendRawSMS(string $body): bool;
}
