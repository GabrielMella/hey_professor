<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /*
        Método do ciclo de vida da classe no php, chamado toda vez que uma classe é
        chamada/invocada.
        Usamos pois esse controlador terá apenas uma única função, que é exibir a view
    */
    public function __invoke(): View
    {
        return view('dashboard', [
            'questions' => Question::withSum('votes', 'like')
                ->withSum('votes', 'unlike')
                ->get(),
        ]);
    }
}
