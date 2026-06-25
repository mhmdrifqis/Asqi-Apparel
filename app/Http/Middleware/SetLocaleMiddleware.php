<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if language is passed via URL parameter (?lang=id)
        if ($request->has('lang') && in_array($request->query('lang'), ['en', 'id'])) {
            $locale = $request->query('lang');
            Session::put('locale', $locale);
            
            // If user is authenticated, save preference to database
            if (auth()->check()) {
                auth()->user()->update(['locale' => $locale]);
            }
        } 
        // 2. Check if user is authenticated and has a preference in database
        elseif (auth()->check() && auth()->user()->locale) {
            $locale = auth()->user()->locale;
            Session::put('locale', $locale);
        }
        // 3. Check if locale is in session
        elseif (Session::has('locale')) {
            $locale = Session::get('locale');
        } 
        // 4. Default to app config locale
        else {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
