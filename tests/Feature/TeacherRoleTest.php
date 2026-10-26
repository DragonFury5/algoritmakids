<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class TeacherRoleTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_teacher_accounts_can_be_created_after_migrations(): void
    {
        $teacher = User::factory()->create(['role' => 'teacher']);

        $this->assertTrue($teacher->isTeacher());
        $this->assertDatabaseHas('users', ['id' => $teacher->id, 'role' => 'teacher']);
    }
}
