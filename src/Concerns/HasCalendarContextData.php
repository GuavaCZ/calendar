<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Contracts\ContextualInfo;
use Guava\Calendar\Enums\Context;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Guava\Calendar\ValueObjects\DateSelectInfo;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Guava\Calendar\ValueObjects\EventDropInfo;
use Guava\Calendar\ValueObjects\EventResizeInfo;
use Guava\Calendar\ValueObjects\NoEventsClickInfo;

trait HasCalendarContextData
{
    protected ?array $rawCalendarContextData = null;

    protected function setRawCalendarContextData(Context $context, array $data): void
    {
        $this->rawCalendarContextData = [
            'context' => $context,
            'data' => $data,
            'useFilamentTimezone' => $this->shouldUseFilamentTimezone(),
        ];

        if ($context->interactsWithRecord()) {
            $this->resolveEventRecord();
        }
    }

    public function getRawCalendarContextData(?string $key = null): array | string | null
    {
        if ($key) {
            return data_get($this->rawCalendarContextData, "data.$key");
        }

        if (empty($data = $this->rawCalendarContextData)) {
            return null;
        }

        return $data;
    }

    public function getCalendarContextInfo(): ?ContextualInfo
    {
        $rawContextData = $this->getRawCalendarContextData() ?? $this->getMountedAction()?->getArguments();

        // No contextual data available
        if (! $rawContextData) {
            return null;
        }

        $context = data_get($rawContextData, 'context');
        $data = data_get($rawContextData, 'data');
        $useFilamentTimezone = data_get($rawContextData, 'useFilamentTimezone');

        if (is_string($context)) {
            $context = Context::from($context);
        }

        return match ($context) {
            Context::DateClick => new DateClickInfo($data, $useFilamentTimezone),
            Context::DateSelect => new DateSelectInfo($data, $useFilamentTimezone),
            Context::EventClick => new EventClickInfo($data, $this->getEventRecord(), $useFilamentTimezone),
            Context::NoEventsClick => new NoEventsClickInfo($data, $useFilamentTimezone),
            Context::EventResize => new EventResizeInfo($data, $this->getEventRecord(), $useFilamentTimezone),
            Context::EventDragAndDrop => new EventDropInfo($data, $this->getEventRecord(), $useFilamentTimezone),
        };
    }
}
