<?php

namespace Guava\Calendar\ValueObjects;

use Carbon\Carbon;
use Filament\Support\Facades\FilamentTimezone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use function Guava\Calendar\utc_to_user_local_time;

class CalendarEvent
{
    protected string $title;

    protected Carbon $start;

    protected Carbon $end;

    protected bool $allDay = false;

    protected ?string $backgroundColor = null;

    protected ?string $textColor = null;

    protected ?string $display = null;

    protected ?bool $editable = null;

    protected ?bool $startEditable = null;

    protected ?bool $durationEditable = null;

    protected array $resourceIds = [];

    protected array $extendedProps = [];

    protected array $styles = [];

    protected array $classNames = [];

    private function __construct(?Model $model = null)
    {
        if ($model) {
            $this->key($model->getKey());
            $this->model($model::class);
        }
    }

    public function start(string | Carbon $start): static
    {
        $start = is_string($start)
            ? Carbon::make($start)
            : $start;

        $this->start = $start; // ->setTimezone(FilamentTimezone::get());
        //        $this->start = $start->setTimezone(FilamentTimezone::get());

        return $this;
    }

    public function getStart(): Carbon
    {
        return $this->start;
    }

    public function end(string | Carbon $end): static
    {
        $end = is_string($end)
            ? Carbon::make($end)
            : $end;

        $this->end = $end; // ->setTimezone(FilamentTimezone::get());
        //        $this->end = $end->setTimezone(FilamentTimezone::get());

        return $this;
    }

    public function getEnd(): Carbon
    {
        return $this->end;
    }

    public function allDay(bool $allDay = true): static
    {
        $this->allDay = $allDay;

        return $this;
    }

    public function getAllDay(): bool
    {
        return $this->allDay;
    }

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    // TODO: Support arrays (such as Color::Rose from Filament) and shade selection (default 400 or 600)
    // TODO: also support filament color names, such as 'primary' or 'danger'
    public function backgroundColor(string $color): static
    {
        $this->backgroundColor = $color;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function textColor(string $color): static
    {
        $this->textColor = $color;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function classes(array $classes): static
    {
        return $this->classNames($classes);
    }

    public function classNames(array $classNames): static
    {
        $this->classNames = $classNames;

        return $this;
    }

    public function getClassNames(): string
    {
        return Arr::toCssClasses($this->classNames);
    }

    public function styles(array $styles): static
    {
        $this->styles = $styles;

        return $this;
    }

    public function getStyles(): string
    {
        $styles = [];

        foreach ($this->styles as $key => $value) {
            if (is_int($key)) {
                $styles[] = Str::finish($value, ';');
            } elseif (! is_bool($value)) {
                $styles[] = Str::finish("$key: $value", ';');
            } elseif ($value) {
                $styles[] = Str::finish($key, ';');
            }
        }

        return implode(' ', $styles);
    }

    public function display(string $display): static
    {
        $this->display = $display;

        return $this;
    }

    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function displayAuto(): static
    {
        return $this->display('auto');
    }

    public function displayBackground(): static
    {
        return $this->display('background');
    }

    public function editable(bool $editable = true): static
    {
        $this->editable = $editable;

        return $this;
    }

    public function getEditable(): ?bool
    {
        return $this->editable;
    }

    public function startEditable(bool $startEditable = true): static
    {
        $this->startEditable = $startEditable;

        return $this;
    }

    public function getStartEditable(): ?bool
    {
        return $this->startEditable;
    }

    public function durationEditable(bool $durationEditable = true): static
    {
        $this->durationEditable = $durationEditable;

        return $this;
    }

    public function getDurationEditable(): ?bool
    {
        return $this->durationEditable;
    }

    public function resourceId(int | string | CalendarResource $resource): static
    {
        $this->resourceIds([$resource]);

        return $this;
    }

    public function resourceIds(array $resourceIds): static
    {
        $this->resourceIds = [
            ...$this->resourceIds,
            ...$resourceIds,
        ];

        return $this;
    }

    public function getResourceIds(): array
    {
        return $this->resourceIds;
    }

    public function url(string $url, string $target = '_blank'): static
    {
        $this->extendedProp('url', $url);
        $this->extendedProp('url_target', $target);

        return $this;
    }

    public function key(string $key): static
    {
        $this->extendedProp('key', $key);

        return $this;
    }

    public function model(string $model): static
    {
        $this->extendedProp('model', $model);

        return $this;
    }

    public function action(string $action): static
    {
        $this->extendedProp('action', $action);

        return $this;
    }

    public function extendedProp(string $key, mixed $value): static
    {
        data_set($this->extendedProps, $key, $value);

        return $this;
    }

    public function extendedProps(array $props): static
    {
        $this->extendedProps = [
            ...$this->extendedProps,
            ...$props,
        ];

        return $this;
    }

    public function getExtendedProps(): array
    {
        return $this->extendedProps;
    }

    public static function make(?Model $model = null): static
    {
        return new static($model);
    }

    public function toCalendarObject(int $timezoneOffset, bool $useFilamentTimezone): array
    {
        $array = [
            'title' => $this->getTitle(),
            'start' => $useFilamentTimezone
                ? $this->getStart()->setTimezone(FilamentTimezone::get())->toIso8601String()
                : $this->getStart()->utcOffset($timezoneOffset)->toIso8601String(),
            'end' => $useFilamentTimezone
                ? $this->getEnd()->setTimezone(FilamentTimezone::get())->toIso8601String()
                : $this->getEnd()->utcOffset($timezoneOffset)->toIso8601String(),
            'allDay' => $this->getAllDay(),
            'backgroundColor' => $this->getBackgroundColor(),
            'textColor' => $this->getTextColor(),
            'styles' => $this->getStyles(),
            'classNames' => $this->getClassNames(),
            'resourceIds' => $this->getResourceIds(),
            'extendedProps' => $this->getExtendedProps(),
        ];

        if (($editable = $this->getEditable()) !== null) {
            $array['editable'] = $editable;
        }

        if (($startEditable = $this->getStartEditable()) !== null) {
            $array['startEditable'] = $startEditable;
        }

        if (($durationEditable = $this->getDurationEditable()) !== null) {
            $array['durationEditable'] = $durationEditable;
        }

        if (($display = $this->getDisplay()) !== null) {
            $array['display'] = $display;
        }

        return $array;
    }

    public function fromCalendarObject(array $data, int $timezoneOffset, bool $useFilamentTimezone): static
    {
        $this
            ->title($data['title'])
            ->start(utc_to_user_local_time($data['start'], $timezoneOffset, $useFilamentTimezone))
            ->end(utc_to_user_local_time($data['end'], $timezoneOffset, $useFilamentTimezone))
            ->allDay($data['allDay'])
            ->styles($data['styles'])
            ->classNames($data['classNames'])
            ->extendedProps($data['extendedProps'])
            ->display($data['display'])
            ->resourceIds($data['resourceIds'])
        ;

        if ($backgroundColor = data_get($data, 'backgroundColor')) {
            $this->backgroundColor($backgroundColor);
        }

        return $this;
    }
}
