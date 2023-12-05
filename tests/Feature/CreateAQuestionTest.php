<?php

use App\Models\User;

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, post};

it('should be able to create a new bigger than 255 characters', function () {
    // Arrange -> Preparar o ambiente para exeutar o teste
    // Nesse caso precisamos logar um usuário
    $user = User::factory()->create();
    actingAs($user);

    // Act -> Agir
    $request = post(route('question.store'), [
        'question' => str_repeat('*', 260) . '?',
    ]);

    //Assert -> Verificar
    $request->assertRedirect(route('dashboard'));
    assertDatabaseCount('questions', 1);
    assertDatabaseHas('questions', [
        'question' => str_repeat('*', 260) . '?',
    ]);
});

it('should check if ends with a question mark', function () {

});

it('should have at least 10 characters', function () {

});
