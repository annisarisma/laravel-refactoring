<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_store_voice_and_returns_json_response(): void
    {
        // Arrange
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $question = Question::create([
            'user_id' => $user2->id,
            'value' => "Some Value" 
        ]);
        $value = true;

        // Act
        $response = $this->actingAs($user)->postJson('api/v1/voice/store-voice', [
            'user_id' => $user->id,
            'question_id' => $question->id,
            'value' => $value
        ]);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status'=>200,
                'message'=>'Voting completed successfully'
            ]);
        $this->assertDatabaseHas('voices', [
            'user_id' => $user->id,
            'question_id' => $question->id,
            'value' => $value,
        ]);
    }
}
