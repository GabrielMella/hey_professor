<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, get};

it('should list all the questions', function () {
    // Arrange - Criar perguntas
    $user      = User::factory()->create();
    $questions = Question::factory()->count(5)->create();

    // Logando o usuário
    actingAs($user);

    // Act - Acessar a rota /questions
    $response = get(route('dashboard'));

    // Assert - Verificar se as perguntas estão sendo exibidas

    /** @var Question $q */
    foreach ($questions as $q) {
        $response->assertSee($q->question);
    }
});
