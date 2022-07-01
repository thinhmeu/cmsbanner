<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class Redirect_301
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $uri = $request->getRequestUri();

        if (preg_match('/(.*?)(page\/[0-9]*)*\/%7B%7B(.*?)(%7D%7D)*/', $uri, $match)) {
            $uri = $match[1];
            return Redirect::to(url($uri).'/', 301);
        }

        if (preg_match('/(.*?)\/(embed|feed|\?filter_by=featured|\?filter_by=popular7)/', $uri, $match)) {
            $uri = $match[1];
            return Redirect::to(url($uri).'/', 301);
        }

        if (preg_match('/(.*?)(page\/[0-9]*)\/(.*?)/', $uri, $match)) {
            $uri = $match[1];
            return Redirect::to(url($uri).'/', 301);
        }

        return $next($request);
    }
}
