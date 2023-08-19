<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class Sanctum
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
        $bearer = $request->Authorization;
  
        [$id, $token] = explode('|', $bearer , 2);

        if ($instance = DB::table('personal_access_tokens')->find($id)) {
            if(hash_equals($instance->token, hash('sha256', $token))
            ) {

                if ($user = User::find($instance->tokenable_id))
                {
                    Auth::login($user);
                    return $next($request);
                }

            }
        }

        return response()->json([
            'success' => false,
            'error' =>  'Access denied.',
        ]);
    }
}
