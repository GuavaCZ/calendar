<?php

namespace Guava\Calendar\Concerns\Events;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

trait EditOnClick
{
    public function OnEventClick() {
        $this->mountAction('edit', [
            'event' => data_get($info, 'event', []),
        ]);
    }
}
