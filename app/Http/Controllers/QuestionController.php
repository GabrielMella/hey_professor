<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Closure;
use Illuminate\Http\{RedirectResponse, Request};

class QuestionController extends Controller
{
    public function store(): RedirectResponse
    {
        $attributes = request()->validate([
            'question' => ['required', 'min:10',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value[strlen($value) - 1] != '?') {
                        $fail('Are you sure that is a question? It is missing mark in the end.');
                    }
                }],
        ]);

        Question::query()->create($attributes);

        return redirect()->route('dashboard');
    }
}
