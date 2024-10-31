<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Session;
use App;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        else {
			$langs = array('zh-TW', 'en');
			$lang = 'en';
			if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
			{
				$lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];
				if(!in_array($lang, $langs))
				{
					$lang = 'en';
				}
			}

			Session::put('locale', $lang);
			App::setLocale($lang);
        }

        return $next($request);
    }
}
