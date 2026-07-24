<?php

namespace Guava\Calendar\Contracts;

use Filament\Schemas\Schema;
use Guava\Calendar\Concerns\InteractsWithCalendar;
use Illuminate\Database\Eloquent\Model;

/**
 * Implemented by the calendar widget. The members below come from the
 * {@see InteractsWithCalendar} trait and are relied on by the calendar
 * actions, which type-hint this contract so Filament injects the livewire component.
 *
 * @method ?Model getEventRecord()
 * @method static setEventRecord(?Model $record)
 * @method ?string getEventModel()
 * @method static refreshRecords()
 * @method ?ContextualInfo getCalendarContextInfo()
 * @method Schema getFormSchemaForModel(Schema $schema, ?string $model = null)
 * @method Schema getInfolistSchemaForModel(Schema $schema, ?string $model = null)
 */
interface HasCalendar {}
