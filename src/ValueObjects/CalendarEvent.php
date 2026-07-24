<?php

namespace Guava\Calendar\ValueObjects;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Filament\Support\Facades\FilamentTimezone;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

use function Guava\Calendar\calendar_event_signature;
use function Guava\Calendar\to_carbon;
use function Guava\Calendar\utc_to_user_local_time;

/**
 * @phpstan-consistent-constructor
 */
class CalendarEvent
{
    protected string | Htmlable $title;

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

    protected ?string $timezone = null;

    protected function __construct(?Model $model = null)
    {
        if ($model) {
            $this->key($model->getKey());
            $this->model($model::class);
        }
    }

    public function start(string | CarbonInterface $start): static
    {
        $this->start = to_carbon($start);

        return $this;
    }

    public function getStart(): Carbon
    {
        return $this->start;
    }

    public function end(string | CarbonInterface $end): static
    {
        $this->end = to_carbon($end);

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

    public function title(string | Htmlable $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string | Htmlable
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

    public function timezone(string $timezone): static
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
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
        // Carry the per-event timezone across the JS boundary so fromCalendarObject() can use
        // it as the inverse of the shift applied here; otherwise inbound conversion would fall
        // back to the Filament timezone and corrupt the instant for per-event-timezone events.
        $extendedProps = $this->getExtendedProps();
        if ($this->timezone !== null) {
            $extendedProps['timezone'] = $this->timezone;
        }

        // The global drag/resize flags are authoritative: a per-event value can only *restrict*
        // (lock) an event, never enable one the widget disabled globally. Distil the developer's
        // editable settings into two "locked" booleans and sign them together with model/key/action
        // so the server can enforce a per-event lock even against a tampered client, and so a client
        // cannot strip a lock or forge model/key/action (see InteractsWithEventRecord).
        $editable = $this->getEditable();
        $dragLocked = $this->getStartEditable() === false || ($this->getStartEditable() === null && $editable === false);
        $resizeLocked = $this->getDurationEditable() === false || ($this->getDurationEditable() === null && $editable === false);

        $model = $extendedProps['model'] ?? null;
        $key = $extendedProps['key'] ?? null;
        if ($model !== null && $key !== null) {
            $action = $extendedProps['action'] ?? '';
            $extendedProps['dragLocked'] = $dragLocked;
            $extendedProps['resizeLocked'] = $resizeLocked;
            $extendedProps['signature'] = calendar_event_signature(
                $model,
                $key,
                is_scalar($action) ? (string) $action : '',
                $dragLocked,
                $resizeLocked,
            );
        }

        $array = [
            'title' => $this->getTitle() instanceof Htmlable ? ['html' => $this->getTitle()->toHtml()] : $this->getTitle(),
            'start' => $useFilamentTimezone
                ? $this->getStart()->copy()->setTimezone($this->timezone ?? FilamentTimezone::get())->toIso8601String()
                : $this->getStart()->copy()->utcOffset($timezoneOffset)->toIso8601String(),
            'end' => $useFilamentTimezone
                ? $this->getEnd()->copy()->setTimezone($this->timezone ?? FilamentTimezone::get())->toIso8601String()
                : $this->getEnd()->copy()->utcOffset($timezoneOffset)->toIso8601String(),
            'allDay' => $this->getAllDay(),
            'backgroundColor' => $this->getBackgroundColor(),
            'textColor' => $this->getTextColor(),
            'styles' => $this->getStyles(),
            'classNames' => $this->getClassNames(),
            'resourceIds' => $this->getResourceIds(),
            'extendedProps' => $extendedProps,
        ];

        // Only forward the restricting (locked) state to EventCalendar. We never send a per-event
        // "true", so EventCalendar's global eventStartEditable/eventDurationEditable governs whether
        // an event can be dragged/resized at all — keeping the global flag authoritative and avoiding
        // the "draggable in the UI, then reverted by the server" flicker.
        if ($dragLocked) {
            $array['startEditable'] = false;
        }

        if ($resizeLocked) {
            $array['durationEditable'] = false;
        }

        if (($display = $this->getDisplay()) !== null) {
            $array['display'] = $display;
        }

        return $array;
    }

    public function fromCalendarObject(array $data, int $timezoneOffset, bool $useFilamentTimezone): static
    {
        $title = $data['title'];
        if (is_array($data['title']) && array_key_exists('html', $data['title'])) {
            $title = new HtmlString($data['title']['html']);
        }

        $timezone = data_get($data, 'extendedProps.timezone');

        $this
            ->title($title)
            ->start(utc_to_user_local_time($data['start'], $timezoneOffset, $useFilamentTimezone, $timezone))
            ->end(utc_to_user_local_time($data['end'], $timezoneOffset, $useFilamentTimezone, $timezone))
            ->allDay((bool) data_get($data, 'allDay', false))
            ->extendedProps((array) data_get($data, 'extendedProps', []))
            ->resourceIds((array) data_get($data, 'resourceIds', []))
        ;

        // styles/classNames are serialized as CSS strings by toCalendarObject(), so only re-apply
        // them when the payload actually carries the array shape the setters expect.
        if (is_array($styles = data_get($data, 'styles'))) {
            $this->styles($styles);
        }

        if (is_array($classNames = data_get($data, 'classNames'))) {
            $this->classNames($classNames);
        }

        if ($display = data_get($data, 'display')) {
            $this->display($display);
        }

        if ($timezone) {
            $this->timezone($timezone);
        }

        if ($backgroundColor = data_get($data, 'backgroundColor')) {
            $this->backgroundColor($backgroundColor);
        }

        return $this;
    }
}
