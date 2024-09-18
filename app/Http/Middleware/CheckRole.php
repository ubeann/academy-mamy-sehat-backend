<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        $userRole = $user->role;
     
        $jumlahrole = count($roles);
   
        if ($user && $jumlahrole == 1 && $userRole == 'user'  || $userRole == 'admin')  {
            return $next($request);
        }
        if ($user && $jumlahrole == 2 && $userRole == 'admin')  {
            return $next($request);
        }
  
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
