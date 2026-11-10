<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Closure;
use Illuminate\Http\{RedirectResponse, Request};

class QuestionController extends Controller
{
    private function questionValidationRules(): array
    {
        return [
            'question' => ['required', 'min:10',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value[strlen($value) - 1] != '?') {
                        $fail('Are you sure that is a question? It is missing mark in the end.');
                    }
                }],
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $attributes = $request->validate($this->questionValidationRules());

        $attributes['user_id'] = $request->user()->id;

        Question::query()->create($attributes);

        return redirect()->route('dashboard');
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        if ($request->user()->id !== $question->user_id) {
            abort(403);
        }

        if ($question->answers()->exists()) {
            abort(403);
        }

        $attributes = $request->validate($this->questionValidationRules());

        $question->update($attributes);

        return redirect()->route('dashboard');
    }
}

