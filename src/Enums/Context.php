<?php

namespace Guava\Calendar\Enums;

enum Context: string
{
    case DateClick = 'dateClick';
    case DateSelect = 'dateSelect';
    case EventClick = 'eventClick';
    case NoEventsClick = 'noEventsClick';
}
