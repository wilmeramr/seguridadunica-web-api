<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
       // \Log::error($request->all());
       // \Log::error($request->expectsJson());
        if (! $request->expectsJson()) {

            return route('login');
        }
    }

    protected function authenticate($request, array $guards)
    {
        parent::authenticate($request, $guards);
      //  \Log::error(auth()->user());
        // Got here? good! it means the user is session authenticated. now we should check if it authorize
        if (!auth()->user()->us_active) {

            auth()->logout();
            $this->unauthenticated($request, $guards);

        }
    }


}
