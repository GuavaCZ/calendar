<?php

namespace Guava\Calendar\Concerns;

use Filament\Notifications\Notification;
use Illuminate\Auth\Access\Response;

trait HasNotifications
{
    public function sendUnauthorizedNotification(Response $response): static
    {
        $notification = $this->getUnauthorizedNotification($response);

        if (filled($notification?->getTitle())) {
            $notification->send();
        }

        return $this;
    }

    protected function getUnauthorizedNotification(Response $response): ?Notification
    {
        return Notification::make()
            ->danger()
            ->title($this->getUnauthorizedNotificationTitle($response))
            ->persistent()
        ;
    }

    protected function getUnauthorizedNotificationTitle(Response $response): ?string
    {
        return $response->message();
    }
}
