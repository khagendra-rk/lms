<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BookingMiddleware
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
        // dd(auth()->user(), auth()->user()->teacher, auth()->user()->student);

        if (!auth()->user()->teacher && !auth()->user()->student) {
            return response()->json([
                'error' => ['You need to be either teacher or student to place booking!'],
            ], 403);
        }

        return $next($request);
    }
}
