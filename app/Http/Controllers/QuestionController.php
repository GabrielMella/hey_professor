<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Closure;
use Illuminate\Http\{RedirectResponse};
use Illuminate\View\View;

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

        $attributes['user_id'] = auth()->id();

        Question::query()->create($attributes);

        return redirect()->route('dashboard');
    }

    public function history(): View
    {
        $questions = Question::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('questions-history', compact('questions'));
    }
}

