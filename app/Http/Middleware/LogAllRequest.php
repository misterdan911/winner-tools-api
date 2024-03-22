<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogAllRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (env('LOG_ALL_REQUEST') == true)
        {
            $arrPath = explode('/', $request->getPathInfo());
            $lastElement = $arrPath[count($arrPath) - 1];

            Log::info('Req. Path :' . $request->getPathInfo());
            // Log::info('Req. Header ' . $lastElement . ':' . json_encode($request->header()));
            Log::info('Req. Body ' . $lastElement . ':' . json_encode($request->all()));
        }

        return $next($request);
    }
}
