<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Form;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFormFound
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $form_slug = $request->form_slug;
        $form = Form::firstWhere('slug', $form_slug);
        if (!$form) {
            return response([
                "message" => "Form not found"
            ], 404);
        }

        return $next($request);
    }
}
