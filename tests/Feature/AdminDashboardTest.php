<?php

namespace Tests\Feature;

use App\Livewire\Admin\Dashboard;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_page_contains_search_and_role_controls(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()
            ->assertSee('user-search')->assertSee('user-role-filter');
    }

    public function test_search_matches_names_and_emails_and_ignores_surrounding_spaces(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $nameMatch = User::factory()->create(['name' => 'Needle Person']);
        $emailMatch = User::factory()->create(['email' => 'needle@example.test']);

        Livewire::actingAs($admin)->test(Dashboard::class)
            ->set('search', ' needle ')
            ->assertViewHas('users', fn ($users) => $users->modelKeys() === [$nameMatch->id, $emailMatch->id]
                || $users->modelKeys() === [$emailMatch->id, $nameMatch->id])
            ->assertViewHas('totalUsers', 3)
            ->set('search', '')
            ->assertViewHas('users', fn ($users) => $users->count() === 3);
    }

    #[DataProvider('roles')]
    public function test_role_filter_combines_with_name_and_email_search(string $role): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $match = User::factory()->create(['role' => $role, 'name' => 'Matching Name']);
        $emailMatch = User::factory()->create(['role' => $role, 'email' => 'matching@example.test']);
        User::factory()->create(['role' => $role, 'name' => 'Other Person', 'email' => 'other@example.test']);
        User::factory()->create(['role' => 'admin', 'name' => 'Matching Admin', 'email' => 'matching-admin@example.test']);

        Livewire::actingAs($admin)->test(Dashboard::class)
            ->set('roleFilter', $role)
            ->assertViewHas('users', fn ($users) => $users->count() === 3)
            ->set('search', 'matching')
            ->assertViewHas('users', fn ($users) => $users->count() === 2
                && $users->contains('id', $match->id) && $users->contains('id', $emailMatch->id))
            ->assertViewHas('totalUsers', 5)
            ->assertViewHas('totalParents', $role === 'parent' ? 3 : 0)
            ->assertViewHas('totalStudents', $role === 'student' ? 3 : 0);
    }

    public function test_no_matches_displays_guidance_and_treats_query_as_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Livewire::actingAs($admin)->test(Dashboard::class)
            ->set('search', "' OR 1=1 --")
            ->assertViewHas('users', fn ($users) => $users->isEmpty())
            ->assertSee('Tidak ada pengguna yang cocok.');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    #[DataProvider('roles')]
    public function test_non_admin_cannot_access_user_search(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public static function roles(): array
    {
        return ['parent' => ['parent'], 'student' => ['student'], 'teacher' => ['teacher']];
    }
}
