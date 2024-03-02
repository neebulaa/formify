<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\Form;
use App\Models\Answer;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResponseController extends Controller
{
    public function store($form_slug, Request $request)
    {
        $form = Form::firstWhere('slug', $form_slug);
        $questions = $form->questions;
        $rules = [
            "answers" => "required|array"
        ];

        $questions->each(function ($question, $i) use (&$rules) {
            $rules["answers.$i.question_id"] = 'required|in:' . $question->id;
            $rules["answers.$i.value"] = '';
            if ($question->is_required) {
                $rules["answers.$i.value"] = 'required';
            }
        });

        $validator = Validator::make($request->all(), $rules, [
            'answers.*.value' => "This question is required"
        ]);

        if ($validator->fails()) {
            return response([
                "message" => "Invalid fields",
                "errors" => $validator->errors()
            ], 422);
        }

        $vd = $validator->validated();

        $response = Response::create([
            "form_id" => $form->id,
            "user_id" => auth()->user()->id,
            "date" => date('Y-m-d H:i:s')
        ]);

        foreach (array_slice($vd['answers'], 0, $questions->count()) as $answer) {
            Answer::create([
                "response_id" => $response->id,
                "question_id" => $answer['question_id'],
                "value" => $answer['value']
            ]);
        }

        return response([
            "message" => "Submit response success"
        ]);
    }

    public function index($form_slug)
    {
        $form = Form::firstWhere('slug', $form_slug);
        return response([
            "message" => "Get responses success",
            "responses" => ResponseResource::collection($form->responses)
        ]);
    }
}
