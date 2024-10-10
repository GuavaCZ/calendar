<?php

namespace Guava\Calendar\Concerns;

use Livewire\Attributes\Js;

trait HasDayHeaderFormat
{
    #[Js]
    public function getDayHeaderFormatJs(): ?string
    {
        return <<<'JS'
null
JS;
    }
}
