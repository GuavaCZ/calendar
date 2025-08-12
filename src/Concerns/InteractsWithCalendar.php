<?php

namespace Guava\Calendar\Concerns;

use Filament\Support\Concerns\EvaluatesClosures;

trait InteractsWithCalendar
{
    use EvaluatesClosures;
    use HandlesEventClick;
    use HasAuthorization;
    use HasDefaultActions;
    use HasEvents;
    use HasFooterActions;
    use HasHeaderActions;
    use HasNotifications;
    use HasSchema;
    use InteractsWithEventRecord;
}
