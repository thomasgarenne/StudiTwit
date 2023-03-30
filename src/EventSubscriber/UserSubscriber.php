<?php

namespace App\EventSubscriber;

use App\Event\UserCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    public function onUserCreatedEvent(UserCreatedEvent $event): void
    {
        //dd($event->getEmail());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserCreatedEvent::class => 'onUserCreatedEvent',
        ];
    }
}
