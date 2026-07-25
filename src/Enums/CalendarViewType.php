<?php

namespace Guava\Calendar\Enums;

enum CalendarViewType: string
{
    case DayGridDay = 'dayGridDay';
    case DayGridWeek = 'dayGridWeek';
    case DayGridMonth = 'dayGridMonth';
    case ListDay = 'listDay';
    case ListWeek = 'listWeek';
    case ListMonth = 'listMonth';
    case ListYear = 'listYear';
    case ResourceTimeGridDay = 'resourceTimeGridDay';
    case ResourceTimeGridWeek = 'resourceTimeGridWeek';
    case ResourceTimelineDay = 'resourceTimelineDay';
    case ResourceTimelineWeek = 'resourceTimelineWeek';
    case ResourceTimelineMonth = 'resourceTimelineMonth';
    case ResourceTimelineYear = 'resourceTimelineYear';
    case TimeGridDay = 'timeGridDay';
    case TimeGridWeek = 'timeGridWeek';
}
