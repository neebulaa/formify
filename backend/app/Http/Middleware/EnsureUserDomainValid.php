<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Form;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserDomainValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // user
        $user = auth()->user();
        $email = $user->email;
        $email_parts = explode('@', $email);
        $domain = end($email_parts);

        // form
        $form = Form::firstWhere('slug', $request->form_slug);
        $allowed_domains = $form->allowed_domains->map(fn ($domain) => $domain->domain);

        if ($allowed_domains->count() > 0 && !$allowed_domains->contains($domain)) {
            return response([
                "message" => "Forbidden access"
            ], 403);
        }

        return $next($request);
    }
}
