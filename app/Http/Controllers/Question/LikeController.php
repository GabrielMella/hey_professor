<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\{Question, Vote};
use Illuminate\Http\{RedirectResponse};

class LikeController extends Controller
{
    public function __invoke(Question $question): RedirectResponse
    {
        // Dessa forma: Question $question , implementamos o findOrFail() diretamente.
        auth()->user()->like($question);

        return back();
    }
}
