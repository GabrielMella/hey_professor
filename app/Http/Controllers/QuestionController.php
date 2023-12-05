<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\{RedirectResponse, Request};

class QuestionController extends Controller
{
    public function store(): RedirectResponse
    {
        $attributes = request()->validate([
            'question' => ['required', 'min:10', 'ends_with:?'],
        ]);

        Question::query()->create($attributes);

        return redirect()->route('dashboard');
    }
}
