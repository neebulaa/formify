<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Form;
use App\Models\Response;
use Illuminate\Http\Request;

class EnsureFirstTimeSubmit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $form_slug = $request->form_slug;
        $form = Form::firstWhere('slug', $form_slug);
        $response_exists = Response::where('form_id', $form->id)->where('user_id', auth()->user()->id)->get()->first();
        if ($form->limit_one_response) {
            if ($response_exists) {
                return response([
                    "message" => "You can not submit form twice"
                ], 422);
            }
        }

        return $next($request);
    }
}
