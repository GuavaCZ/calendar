<?php

namespace Guava\Calendar\Concerns;

use Closure;
use Illuminate\View\View;

trait HasMoreLinkContent
{
    protected null | string $moreLinkContent = null;

    /**
     * vkurko/calendar doesn't support async method calls in moreLinkContent,
     * that's why we need to pass all views to the client side.
     *
     * @return string|array|null null to use default, string to use single view
     */
    public function getMoreLinkContent(): null | string | array
    {
        return $this->moreLinkContent;
    }

    public function getMoreLinkContentJs(): ?string
    {
        return $this->getMoreLinkContent();
    }
}
