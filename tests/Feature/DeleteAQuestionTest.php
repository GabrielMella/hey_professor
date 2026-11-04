<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, delete};

it('should allow the author to delete their own question', function () {
    // Arrange
    $user = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);
    actingAs($user);

    // Act
    $request = delete(route('question.destroy', $question));

    // Assert
    $request->assertRedirect(route('dashboard'));
    assertDatabaseCount('questions', 0);
});

it('should prevent a user from deleting another user\'s question', function () {
    // Arrange
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $owner->id]);
    actingAs($other);

    // Act
    $request = delete(route('question.destroy', $question));

    // Assert
    $request->assertForbidden();
    assertDatabaseCount('questions', 1);
    assertDatabaseHas('questions', ['id' => $question->id]);
});

it('should return 404 when trying to delete a non-existent question', function () {
    // Arrange
    $user = User::factory()->create();
    actingAs($user);

    // Act
    $request = delete(route('question.destroy', 999999));

    // Assert
    $request->assertNotFound();
});