<?php

namespace Rezak\SmsNotification;

class SMSMessage
{
    protected string $template;
    protected array $data;

    public function setTemplate(string $template): static
    {
        $this->template = $template;
        return $this;
    }

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function isTemplated(): bool
    {
        return !is_null($this->template);
    }
}
