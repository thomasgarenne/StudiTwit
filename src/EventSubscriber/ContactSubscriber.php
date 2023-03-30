<?php

namespace App\EventSubscriber;

use App\Event\ContactSentEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContactSubscriber implements EventSubscriberInterface
{
    public function onContactSentEvent(ContactSentEvent $event): void
    {
        dd($event);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactSentEvent::class => 'onContactSentEvent',
        ];
    }
}
