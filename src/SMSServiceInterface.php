<?php

namespace Rezak\SmsNotification;

interface SmsServiceInterface
{
    public function setTemplateName(string $templateName): static;
    public function setPhone(string $phone): static;
    public function setToken(string $key, string $value): static;
    public function sendTemplatedSMS(): bool;
}
