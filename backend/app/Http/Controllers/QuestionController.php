<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function store(Request $request, $form_slug)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "choice_type" => "required|in:short answer,paragraph,date,multiple choice,dropdown,checkboxes",
            "choices" => "required_if:choice_type,multiple choice,dropdown,checkboxes|array|exclude_if:choice_type,short answer,paragraph,date",
            "is_required" => ""
        ], [
            "choice_type.in" => "Choice type can only be: short answer, paragraph, date, multiple choice, dropdown, or checkboxes"
        ]);

        if ($validator->fails()) {
            return response([
                "message" => "Invalid fields",
                "errors" => $validator->errors()
            ], 422);
        }


        $form = Form::firstWhere('slug', $form_slug);
        $vd = $validator->validated();
        $vd['form_id'] = $form->id;
        if (isset($vd['choices'])) {
            $vd['choices'] = implode(',', $vd['choices']);
        }
        $question = Question::create($vd);

        return response([
            "message" => "Add question success",
            "question" => $question
        ]);
    }

    public function destroy($form_slug, $question_id)
    {
        $form = Form::firstWhere('slug', $form_slug);
        $question = Question::where('form_id', $form->id)->where('id', $question_id)->get()->first();
        if (!$question) {
            return response([
                "message" => "Question not found"
            ], 404);
        }

        Question::destroy($question_id);
        return response([
            "message" => "Remove question success"
        ]);
    }
}
