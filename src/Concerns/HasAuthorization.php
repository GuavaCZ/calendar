<?php

namespace Guava\Calendar\Concerns;

use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

use function Filament\get_authorization_response;

trait HasAuthorization
{
    protected bool $shouldSkipAuthorization = false;

    public function shouldSkipAuthorization(): bool
    {
        return $this->shouldSkipAuthorization;
    }

    public function getAuthorizationResponse(string $action, null | Model | string $recordOrModel = null): Response
    {
        $action = $this->transformActionName($action);

        if ($this->shouldSkipAuthorization()) {
            return Response::allow();
        }

        return get_authorization_response($action, $recordOrModel);
        //        return get_authorization_response($action, $this->getEventRecord(), static::shouldCheckPolicyExistence());
    }

    /**
     * Filament uses 'edit' while laravel uses 'update'.
     * This transforms the action name so laravel's default policy names work.
     */
    protected function transformActionName(string $action): string
    {
        return match ($action) {
            'edit' => 'update',
            default => $action,
        };
    }
}
