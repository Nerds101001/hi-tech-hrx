<?php

namespace App\Http\Middleware;

use App\Enums\UserAccountStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnboardingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user) {
            $userStatus = $user->status instanceof \UnitEnum ? $user->status->value : $user->status;

            // If user is in the middle of onboarding, force them to the form
            if ($userStatus === UserAccountStatus::ONBOARDING->value || $userStatus === UserAccountStatus::ONBOARDING_REQUESTED->value) {
                if (!$request->is('onboarding*') && !$request->is('logout')) {
                    return redirect()->route('onboarding.form');
                }
            }
            
            // If user has submitted, they can see the dashboard but it will be in a "Locked/Under Review" state
            // We handle the "Locked" state in the views/layouts by checking the status.
            // But we should prevent them from accessing the onboarding form again unless requested.
            if ($userStatus === UserAccountStatus::ONBOARDING_SUBMITTED->value) {
                if ($request->is('onboarding*') && !$request->is('onboarding/status')) {
                     return redirect()->route('tenant.dashboard');
                }
            }
        }

        return $next($request);
    }
}
