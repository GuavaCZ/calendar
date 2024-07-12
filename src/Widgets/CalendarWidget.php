<?php

namespace Guava\Calendar\Widgets;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Exceptions\Cancel;
use Filament\Support\Exceptions\Halt;
use Filament\Widgets\Widget;
use Guava\Calendar\Actions\EditAction;
use Guava\Calendar\Concerns\Events;
use Guava\Calendar\Concerns\HasCustomActions;
use Guava\Calendar\Concerns\HasEventContent;
use Guava\Calendar\Concerns\HasEvents;
use Guava\Calendar\Concerns\HasFooterActions;
use Guava\Calendar\Concerns\HasHeaderActions;
use Guava\Calendar\Concerns\HasMoreLinkContent;
use Guava\Calendar\Concerns\HasOptions;
use Guava\Calendar\Concerns\HasSchema;

class CalendarWidget extends Widget implements HasActions, HasForms
{
    use EvaluatesClosures;
    use Events\EditOnClick;
    use HasCustomActions;
    use HasEventContent;
    use HasEvents;
    use HasFooterActions;
    use HasHeaderActions;
    use HasMoreLinkContent;
    use HasOptions;
    use InteractsWithActions;
    use InteractsWithForms;
    use HasSchema;

    protected static string $view = 'guava-calendar::widgets.calendar';

    protected int | string | array $columnSpan = 'full';

    public function onEventClick($info)
    {
        return $this->mountAction('edit', [
            'event' => data_get($info, 'event', []),
        ]);
    }

    public function getCustomActions()
    {
        return [
            EditAction::make(),
            //            Action::make('testing')
            //                ->mountUsing(function ($arguments, $action) {
            //                    if ($model = data_get($arguments, 'model')) {
            //                        if ($model === 'App\Models\Lesson') {
            //                            return $action->form([TextInput::make('testing'),
            //                                TextInput::make('dfaf')]);
            //                        }
            //
            //                        return $action->form([TextInput::make('testing')]);
            //                    }
            //                })
            ////                ->form(fn($arguments) [TextInput::make('testing')])
            //                ->after(fn (CalendarWidget $livewire) => $livewire->refreshRecords()),
        ];
    }

    public function refreshRecords(): void
    {
        $this->dispatch('calendar--refresh');
    }

    public function mountAction(string $name, array $arguments = []): mixed
    {
        $this->mountedActions[] = $name;
        $this->mountedActionsArguments[] = $arguments;
        $this->mountedActionsData[] = [];

        $action = $this->getMountedAction();

        if (! $action) {
            $this->unmountAction();

            return null;
        }

        if ($action->isDisabled()) {
            $this->unmountAction();

            return null;
        }

        $model = data_get($arguments, 'event.extendedProps.model');
        $key = data_get($arguments, 'event.extendedProps.key');
        if ($model && $key) {
            $record = $model::query()->find($key);
            $action->model($model);
            $action->record($record);
//            dd($model, $this->getSchema($model));
            $action->form(fn() => $this->getSchemaForModel($model));
//            if ($schema = $this->getSchema($model)) {
//                $action->form(fn () => $schema);
//            }

        }
        $this->cacheMountedActionForm(mountedAction: $action);

        try {
            $hasForm = $this->mountedActionHasForm(mountedAction: $action);

            if ($hasForm) {
                $action->callBeforeFormFilled();
            }

            $action->mount([
                'form' => $this->getMountedActionForm(mountedAction: $action),
            ]);

            if ($hasForm) {
                $action->callAfterFormFilled();
            }
        } catch (Halt $exception) {
            return null;
        } catch (Cancel $exception) {
            $this->unmountAction(shouldCancelParentActions: false);

            return null;
        }

        if (! $this->mountedActionShouldOpenModal(mountedAction: $action)) {
            return $this->callMountedAction();
        }

        $this->resetErrorBag();

        $this->openActionModal();

        return null;
    }
}
