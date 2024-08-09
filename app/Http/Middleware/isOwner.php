<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        $owner = Role::where('name', 'owner')->first();

        if ($user->role_id != $owner->id) {
            return response()->json(['message' => 'Only owner can access || Hanya pemilik yang bisa mengakses'], 401);
        }

        return $next($request);
    }
}
