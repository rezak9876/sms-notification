<?php

namespace Rezak\SMSNotification\Messages;

use InvalidArgumentException;

class SMSMessage
{
    protected string $template;
    protected array $data;
    public ?string $content;

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

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getContent(): ?string
    {
        if (!isset($this->content)) {
            throw new InvalidArgumentException("Content has not been set.");
        }
        return $this->content;
    }

    public function isTemplated(): bool
    {
        return !empty($this->template);
    }
}
