<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing, post};

it('should be able to like a question', function () {
    // arrange
    $user     = User::factory()->create();
    $question = Question::factory()->create();

    actingAs($user);

    // act
    post(route('question.like', $question))
        ->assertRedirect(); // Valida o status Code Http

    // assert
    assertDatabaseHas('votes', [
        'question_id' => $question->id,
        'like'        => 1,
        'unlike'      => 0,
        'user_id'     => $user->id,
    ]);

});

it('should not be able to like more than one time', function () {
    // arrange
    $user     = User::factory()->create();
    $question = Question::factory()->create();

    actingAs($user);

    // act
    post(route('question.like', $question));
    post(route('question.like', $question));
    post(route('question.like', $question));
    post(route('question.like', $question));

    // assert
    expect($user->votes()->where('question_id', $question->id)->get())
        ->toHaveCount(1);
});

/* Unlike */
it('should be able to unlike a question', function () {
    // arrange
    $user     = User::factory()->create();
    $question = Question::factory()->create();

    actingAs($user);

    // act
    post(route('question.unlike', $question))
        ->assertRedirect(); // Valida o status Code Http

    // assert
    assertDatabaseHas('votes', [
        'question_id' => $question->id,
        'like'        => 0,
        'unlike'      => 1,
        'user_id'     => $user->id,
    ]);

});

it('should not be able to unlike more than one time', function () {
    // arrange
    $user     = User::factory()->create();
    $question = Question::factory()->create();

    actingAs($user);

    // act
    post(route('question.unlike', $question));
    post(route('question.unlike', $question));
    post(route('question.unlike', $question));
    post(route('question.unlike', $question));

    // assert
    expect($user->votes()->where('question_id', $question->id)->get())
        ->toHaveCount(1);
});
