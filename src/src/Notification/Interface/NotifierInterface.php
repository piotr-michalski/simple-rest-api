<?php

declare(strict_types=1);

namespace App\Notification\Interface;

interface NotifierInterface
{
    public function notify(string $message): void;
}
