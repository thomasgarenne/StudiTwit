<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ContactSentEvent extends Event
{
    protected string $email;
    protected string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getpassword(): string
    {
        return $this->password;
    }
}
