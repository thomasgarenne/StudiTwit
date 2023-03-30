<?php

namespace App\EventSubscriber;

use App\Event\ContactAdminEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactAdminSubscriber implements EventSubscriberInterface
{

    public function onContactAdminEvent(ContactAdminEvent $event, MailerInterface $mailer): void
    {
        $contactEmail = $event->getEmail();
        $content = $event->getContent();

        $adminMail = 'thomas.garenne@outlook.com';

        $email = (new Email())
            ->from($contactEmail)
            ->to($adminMail)
            ->text($content);

        dd($email);
        $mailer->send($email);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactAdminEvent::class => 'onContactAdminEvent',
        ];
    }
}
