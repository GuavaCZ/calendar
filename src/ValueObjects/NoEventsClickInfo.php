<?php

namespace Guava\Calendar\ValueObjects;

use Guava\Calendar\Contracts\ContextualInfo;
use Guava\Calendar\Enums\Context;

readonly class NoEventsClickInfo implements ContextualInfo
{
    public CalendarView $view;

    protected array $originalData;

    public function __construct(array $data, bool $useFilamentTimezone)
    {
        $this->originalData = $data;

        $this->view = new CalendarView(
            data_get($data, 'view'),
            data_get($data, 'tzOffset'),
            $useFilamentTimezone
        );
    }

    public function getContext(): Context
    {
        return Context::NoEventsClick;
    }
}
