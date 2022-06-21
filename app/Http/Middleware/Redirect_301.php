<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\SiteRedirect;
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

        /*redirect db*/
        $all = SiteRedirect::getAll();
        $arrOriginalUrl = array_column($all, 'original_url');
        $arrRedirectlUrl = array_column($all, 'redirect_url');
        $data = array_combine($arrOriginalUrl, $arrRedirectlUrl);
        if (isset($data[$uri])) {
            $url_redirect = url($data[$uri]);
            if (substr($data[$uri], -1) == '/') $url_redirect .= '/';
            return Redirect::to($url_redirect, 301);
        }

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
