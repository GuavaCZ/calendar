<?php

namespace Guava\Calendar\Concerns;

use Closure;
use Illuminate\Support\HtmlString;

trait HasHeading
{
    protected string | Closure | HtmlString | null $heading = null;

    public function heading(string | Closure | HtmlString $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function getHeading(): string | HtmlString
    {
        return $this->evaluate($this->heading) ?? __('guava-calendar::translations.heading');
    }
}
