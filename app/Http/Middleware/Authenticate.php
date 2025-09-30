<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request)
    {
        if (!$request->expectsJson()) {
            return '/';
        }

        // $ip = \DB::table('portal_settings')->where('code', 'whitelistip')->first(['value']);

        // if($ip->value != "::1" && $ip->value != $request->ip()){
        //     abort(403);
        // }
    }
}
