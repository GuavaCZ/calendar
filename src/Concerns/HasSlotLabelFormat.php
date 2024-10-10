<?php

namespace Guava\Calendar\Concerns;

use Livewire\Attributes\Js;

trait HasSlotLabelFormat
{
    #[Js]
    public function getSlotLabelFormatJs(): ?string
    {
        return <<<'JS'
null
JS;
    }
}
