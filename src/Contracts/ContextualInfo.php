<?php

namespace Guava\Calendar\Contracts;

use Guava\Calendar\Enums\Context;

interface ContextualInfo {

    public function getContext(): Context;

    public function getOriginalData(): array;

}
