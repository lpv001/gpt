<?php

namespace App\Http\Middleware;

use Closure;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected $languages = ['en', 'km'];

    public function handle($request, Closure $next)
    {
      if (!session()->has('locale')) {
        session()->put('locale', app()->config->get('app.locale'));
        //session()->put('locale', $request->getPreferredLanguage($this->languages));
      }
      
      app()->setLocale(session()->get('locale'));
      return $next($request);
    }
}
