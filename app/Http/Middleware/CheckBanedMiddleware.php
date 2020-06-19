<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\Helper;

class CheckBanedMiddleware
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
        if ($request->is('api/*')) {
            // api request
            $user = $request->user();
            if ($user) 
                if ($user->ban == 1) {
                    return Helper::sendResponse(false,'Account be banned' ,403,"Account be banned");
                }
        }
        return $next($request);
    }
}
