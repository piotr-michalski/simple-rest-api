<?php

declare(strict_types=1);

namespace App\Service;

use App\Notification\Interface\NotifierInterface;

readonly class Notification
{
    /**
     * @param NotifierInterface[] $notifiers
     */
    public function __construct(private iterable $notifiers)
    {
    }

    public function send(string $message): void
    {
        foreach ($this->notifiers as $notifier) {
            $notifier->notify($message);
        }
    }
}
