<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

//creation de l'événement à l'aide d'une classe
class UserCreatedEvent extends Event
{
    protected ?string $email = null;

    public function __construct(?string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
