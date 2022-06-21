<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ConfigSlug
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
        if ($request->getHost() == env('DOMAIN') || $request->getHost() == env('DOMAIN_BETA')){
            if (empty($request->get('page'))) {
                if (!preg_match('/.+\/$/', $request->getRequestUri())) {
                    return Redirect::to('//' . $request->getHttpHost() . $request->getRequestUri() . '/', 301);
                }
            }
        }
        return $next($request);
    }
}
