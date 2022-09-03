<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Userinfo;

class EnsureUserHasVerifiedLocation
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
        $userinfo = Userinfo::where('user_id', auth()->user()->endorsers_id)->first();

        if(!$userinfo){
            return redirect(route('verify.location'));
        }

        return $next($request);
    }
}
