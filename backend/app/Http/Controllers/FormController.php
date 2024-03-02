<?php

namespace App\Http\Controllers;

use App\Http\Resources\FormResource;
use App\Models\Form;
use Illuminate\Http\Request;
use App\Models\AllowedDomain;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "slug" => "required|unique:forms,slug|regex:/^[a-z0-9-.]+$/i",
            "allowed_domains" => "array",
            "description" => "",
            "limit_one_response" =>  ""
        ]);

        if ($validator->fails()) {
            return response([
                "message" => "Invalid fields",
                "errors" => $validator->errors()
            ], 422);
        }

        $vd = $validator->validated();
        $vd['creator_id'] = auth()->user()->id;
        $form = Form::create($vd);

        if (isset($vd['allowed_domains'])) {
            foreach ($vd['allowed_domains'] as $domain) {
                AllowedDomain::create([
                    "form_id" => $form->id,
                    "domain" => trim($domain)
                ]);
            }
        }

        return response([
            "message" => "Create form success",
            "form" => $form
        ]);
    }

    public function index()
    {
        $forms = Form::where('creator_id', auth()->user()->id)->get();
        return response([
            "message" => "Get all forms success",
            "forms" => $forms
        ]);
    }

    public function show($form_slug)
    {
        $form = Form::firstWhere('slug', $form_slug);
        return response([
            "message" => "Get form success",
            "form" => new FormResource($form)
        ]);
    }
}
