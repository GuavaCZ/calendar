<?php

namespace Guava\Calendar\Commands;

use Illuminate\Console\Command;

class CalendarCommand extends Command
{
    public $signature = 'calendar';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
