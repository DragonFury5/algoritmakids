<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class HeartbeatTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_receives_401(): void
    {
        $this->postJson(route('heartbeat'), ['seconds' => 10])->assertUnauthorized();
    }

    public function test_valid_seconds_are_added_to_the_authenticated_user_only(): void
    {
        $user = User::factory()->create(['total_time_spent_seconds' => 20]);
        $otherUser = User::factory()->create(['total_time_spent_seconds' => 5]);

        $this->actingAs($user)->postJson(route('heartbeat'), ['seconds' => 10])
            ->assertOk()->assertExactJson(['status' => 'ok']);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'total_time_spent_seconds' => 30]);
        $this->assertDatabaseHas('users', ['id' => $otherUser->id, 'total_time_spent_seconds' => 5]);
    }

    public function test_missing_seconds_defaults_to_ten(): void
    {
        $user = User::factory()->create(['total_time_spent_seconds' => 20]);

        $this->actingAs($user)->postJson(route('heartbeat'))
            ->assertOk()->assertExactJson(['status' => 'ok']);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'total_time_spent_seconds' => 30]);
    }

    #[DataProvider('invalidSeconds')]
    public function test_invalid_seconds_return_422_without_changing_time(mixed $seconds): void
    {
        $user = User::factory()->create(['total_time_spent_seconds' => 20]);

        $this->actingAs($user)->postJson(route('heartbeat'), ['seconds' => $seconds])
            ->assertUnprocessable()->assertJsonValidationErrors('seconds');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'total_time_spent_seconds' => 20]);
    }

    public static function invalidSeconds(): array
    {
        return [
            'negative' => [-1],
            'zero' => [0],
            'exceeds_heartbeat_interval' => [11],
            'fraction' => [1.5],
            'text' => ['invalid'],
            'array' => [[10]],
            'null' => [null],
        ];
    }
}
