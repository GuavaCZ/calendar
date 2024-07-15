<?php

namespace Guava\Calendar\Form;

use Filament\Forms\Components\Field;
use Guava\Calendar\Concerns\HasEvents;

class Calendar extends Field
{
    use HasEvents;

    public function onSelect($info) {
        dd('ąsd');
        $this->dispatchEvent('ec-add-event', $info);
    }

    protected string $view = 'guava-calendar::forms.calendar';
}
