<?php

namespace Guava\Calendar\Exceptions;

use Exception;
use Throwable;

class EventContentNotFoundException extends Exception {
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = 'No event content found for the given model';
    }
}
