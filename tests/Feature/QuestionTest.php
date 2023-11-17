<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;
    public function test_store_method_creates_question_and_returns_json_response(): void
    {
        // Arrange
        $user = User::factory()->create();
        $value = 'Some Question';

        // Act
        $response = $this->actingAs($user)->postJson('api/v1/question/store-question', [
            'user_id' => $user->id,
            'value' => $value
        ]);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Success'
            ]);
        $this->assertDatabaseHas('questions', [
            'user_id' => $user->id,
            'value' => $value,
        ]);
    }

    public function test_show_question_with_invalid_id()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->getJson("api/v1/question/show-question/999");

        // Assert
        $response->assertStatus(404)
            ->assertJson([
                'status' => 404,
                'message' => 'not found question ..',
            ]);
    }

    public function test_show_question_with_valid_id()
    {
        // Arrange
        $user = User::factory()->create();
        $question = Question::create([
            'user_id' => $user->id,
            'value' => "Some Question"
        ]);

        // Act
        $response = $this->actingAs($user)->getJson("api/v1/question/show-question/$question->id");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'question' => $question->toArray(),
            ]);
    }

    public function test_destroy_question_with_valid_id()
    {
        // Arrange
        $user = User::factory()->create();
        $question = Question::create([
            'user_id' => $user->id,
            'value' => "Some Question"
        ]);

        // Act
        $response = $this->actingAs($user)->deleteJson("api/v1/question/destroy-question/$question->id");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Successfully deleted',
            ]);
        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    public function test_destroy_question_with_invalid_id()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->deleteJson("api/v1/question/destroy-question/999");

        // Assert
        $response->assertStatus(404)
            ->assertJson([
                'status' => 404,
                'message' => 'Error deleted',
            ]);
    }
}
