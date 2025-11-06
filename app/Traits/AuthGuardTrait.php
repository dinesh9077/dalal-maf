<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait AuthGuardTrait
{
    use ApiResponseTrait;

    /**
     * Centralized map of all auth types.
     *
     * You can extend this easily — just add new keys (e.g. 'admin').
     */
    protected function authGuardMap(): array
    {
        return [
            'user' => ['guard' => 'api', 'column' => 'user_id', 'inquiry' => 'inquiry_by_user'],
            'vendor' => ['guard' => 'vendor_api', 'column' => 'vendor_id', 'inquiry' => 'inquiry_by_vendor'],
            'agent' => ['guard' => 'agent_api', 'column' => 'agent_id', 'inquiry' => 'inquiry_by_agent'],
        ];
    }

    /**
     * Resolve guard, column, and inquiry dynamically.
     *
     * @param  string|null  $authType
     * @param  bool  $returnResponse  If true, returns JSON response instead of null on invalid input
     * @return array{0:string,1:string,2:string}|JsonResponse|null
     */
    protected function resolveAuthGuard(?string $authType, bool $returnResponse = true)
    {
        $authType = $authType ? strtolower(trim($authType)) : null;

        // Missing auth type
        if (!$authType) {
            return $returnResponse
                ? $this->errorResponse('auth type is required.', 400)
                : null;
        }

        $map = $this->authGuardMap();

        // Invalid auth type
        if (!isset($map[$authType])) {
            return $returnResponse
                ? $this->errorResponse("Invalid auth type: {$authType}", 400)
                : null;
        }

        $entry = $map[$authType];
        return [$entry['guard'], $entry['column'], $entry['inquiry']];
    }

    /**
     * Get only the guard name (shortcut)
     */
    protected function getGuard(?string $authType): ?string
    {
        $resolved = $this->resolveAuthGuard($authType, false);
        return $resolved[0] ?? null;
    }

    /**
     * Get only the column name (shortcut)
     */
    protected function getAuthColumn(?string $authType): ?string
    {
        $resolved = $this->resolveAuthGuard($authType, false);
        return $resolved[1] ?? null;
    }

    /**
     * Get only the inquiry key (shortcut)
     */
    protected function getAuthInquiry(?string $authType): ?string
    {
        $resolved = $this->resolveAuthGuard($authType, false);
        return $resolved[2] ?? null;
    }
}
