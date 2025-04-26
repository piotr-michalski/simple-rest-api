<?php

declare(strict_types=1);

namespace App\Notification;

use App\Notification\Interface\NotifierInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

readonly class EmailNotifier implements NotifierInterface
{
    public function __construct(
        private MailerInterface $mailer,
        private string $adminEmail
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function notify(string $message): void
    {
        $email = new Email()
            ->from('noreply@example.com')
            ->to($this->adminEmail)
            ->subject('New product added')
            ->text($message);

        $this->mailer->send($email);
    }
}
