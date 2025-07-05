<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CahngeLang
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */

  public function handle($request, Closure $next)
  {
    $locale = $request->route('lang');

    if ($locale) {
      App::setLocale($locale);
      Session::put('locale', $locale);
    } elseif (Session::has('locale')) {
      App::setLocale(Session::get('locale'));
    }

    return $next($request);
  }
}
