<?php

namespace Guava\Calendar\Concerns;

use Illuminate\Support\HtmlString;

trait HasHeading
{
    protected string | HtmlString | null | bool $heading = true;

    public function getHeading(): null | string | HtmlString
    {
        if ($this->heading === false || is_null($this->heading)) {
            return null;
        }

        if ($this->heading === true) {
            return __('guava-calendar::translations.heading');
        }

        return $this->heading;
    }
}
