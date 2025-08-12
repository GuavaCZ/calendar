<?php

namespace Guava\Calendar\Concerns;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;
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
    use InteractsWithSchemas;
    use InteractsWithActions;
    use HasEventContent;

    public function getDefaultActionSchemaResolver(Action $action): ?Closure
    {
        return match (true) {
            $action instanceof CreateAction => fn (Schema $schema): Schema => $this->getFormSchemaForModel($schema, $this->getEventModel()),
            $action instanceof EditAction => fn (Schema $schema): Schema => $this->getFormSchemaForModel($schema, $this->getEventModel())
                ->record($this->getEventRecord()),
            $action instanceof ViewAction => fn (Schema $schema): Schema => $this->getInfolistSchemaForModel($schema, $this->getEventModel())
                ->record($this->getEventRecord()),
            default => null,
        };
    }
}
