<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Form;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsCreator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $form = Form::firstWhere('slug', $request->form_slug);
        $user = auth()->user();

        if ($form->creator_id != $user->id) {
            return response([
                "message" => "Forbidden Access"
            ], 403);
        }

        return $next($request);
    }
}
