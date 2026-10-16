<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockPausedUsers
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isPaused()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', app()->getLocale() === 'id'
                    ? 'Akun ini sedang dijeda oleh orang tua.'
                    : 'This account is paused by the parent.');
        }

        return $next($request);
    }
}