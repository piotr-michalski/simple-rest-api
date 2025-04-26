<?php

declare(strict_types=1);

namespace App\Notification;

use App\Notification\Interface\NotifierInterface;
use Psr\Log\LoggerInterface;

readonly class LoggerNotifier implements NotifierInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function notify(string $message): void
    {
        $this->logger->info($message);
    }
}
