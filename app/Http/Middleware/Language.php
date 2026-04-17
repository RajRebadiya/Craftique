<?php

namespace App\Http\Middleware;

use App;
use Config;
use Closure;
use Session;
use Carbon\Carbon;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = Session::get('locale', env('DEFAULT_LANGUAGE', 'en'));
        if (! \App\Models\Language::where('code', $locale)->exists()) {
            $locale = \App\Models\Language::where('code', 'en')->value('code') ?? 'en';
        }

        App::setLocale($locale);
        $request->session()->put('locale', $locale);

        $langcode = Session::has('langcode') ? Session::get('langcode') : 'en';
        Carbon::setLocale($langcode);

        return $next($request);
    }
}
