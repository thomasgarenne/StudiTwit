<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ContactAdminEvent extends Event
{
    protected string $email;
    protected string $content;

    public function __construct(string $email, string $content)
    {
        $this->email = $email;
        $this->content = $content;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
