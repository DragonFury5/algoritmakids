<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: session > logged-in user's setting > session default > app default
        $locale = Session::get('locale');

        if (! $locale && Auth::check()) {
            $locale = Auth::user()->language;
        }

        if (! $locale) {
            $locale = 'id';
        }

        if (! in_array($locale, ['id', 'en'])) {
            $locale = 'id';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}