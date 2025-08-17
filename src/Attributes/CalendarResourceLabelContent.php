<?php

namespace Guava\Calendar\Attributes;

use Illuminate\Database\Eloquent\Model;

#[\Attribute(\Attribute::TARGET_METHOD)]
class CalendarResourceLabelContent
{
    /**
     * @param  class-string<Model>  $model
     */
    public function __construct(public string $model) {}
}
