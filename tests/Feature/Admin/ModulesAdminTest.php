<?php

namespace Tests\Feature\Admin;

use App\Models\Const;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ModulesAdminTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_modules_page_for_admin(): void
    {
        // Arrange
        $admin = User::factory()->admin()->create();

        // Act
        $response = $this->actingAs($admin)->get(route('admin.modules'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.modules');
    }

    #[Test]
    public function it_denies_access_to_non_admin_users(): void
    {
        // Arrange
        $user = User::factory()->create(['admin' => 0]);

        // Act
        $response = $this->actingAs($user)->get(route('admin.modules'));

        // Assert
        $response->assertStatus(403);
    }

    #[Test]
    public function it_resets_all_modules(): void
    {
        // Arrange
        $admin = User::factory()->admin()->create();
        Const::create(['name' => 'TEST_MODULE_CONFIG', 'value' => '1', 'entity' => 1]);
        Const::create(['name' => 'ANOTHER_MODULE_SETTING', 'value' => '1', 'entity' => 1]);
        Const::create(['name' => 'NOT_A_MODULE', 'value' => '1', 'entity' => 1]);

        // Act
        $response = $this->actingAs($admin)->get(route('admin.modules', [
            'action' => 'reset',
            'confirm' => 'yes'
        ]));

        // Assert
        $response->assertRedirect(route('admin.modules'));
        $this->assertDatabaseMissing('llx_const', ['name' => 'TEST_MODULE_CONFIG']);
        $this->assertDatabaseMissing('llx_const', ['name' => 'ANOTHER_MODULE_SETTING']);
        $this->assertDatabaseHas('llx_const', ['name' => 'NOT_A_MODULE']);
    }

    #[Test]
    public function it_does_not_reset_modules_without_confirmation(): void
    {
        // Arrange
        $admin = User::factory()->admin()->create();
        Const::create(['name' => 'TEST_MODULE_CONFIG', 'value' => '1', 'entity' => 1]);

        // Act
        $response = $this->actingAs($admin)->get(route('admin.modules', [
            'action' => 'reset',
            'confirm' => 'no'
        ]));

        // Assert
        $response->assertRedirect(route('admin.modules'));
        $this->assertDatabaseHas('llx_const', ['name' => 'TEST_MODULE_CONFIG']);
    }

    #[Test]
    public function it_activates_a_module(): void
    {
        // Arrange
        $admin = User::factory()->admin()->create();

        // Act
        $response = $this->actingAs($admin)->get(route('admin.modules', [
            'action' => 'set',
            'module' => 'testmodule',
            'value' => '1'
        ]));

        // Assert
        $response->assertRedirect(route('admin.modules'));
        $this->assertDatabaseHas('llx_const', [
            'name' => 'TESTMODULE_MODULE',
            'value' => '1',
        ]);
    }

    #[Test]
    public function it_deactivates_a_module(): void
    {
        // Arrange
        $admin = User::factory()->admin()->create();
        Const::create(['name' => 'TESTMODULE_MODULE', 'value' => '1', 'entity' => 1]);

        // Act
        $response = $this->actingAs($admin)->get(route('admin.modules', [
            'action' => 'set',
            'module' => 'testmodule',
            'value' => '0'
        ]));

        // Assert
        $response->assertRedirect(route('admin.modules'));
        $this->assertDatabaseMissing('llx_const', ['name' => 'TESTMODULE_MODULE']);
    }

    #[Test]
    public function it_requires_admin_permission_to_reset_modules(): void
    {
        // Arrange
        $user = User::factory()->create(['admin' => 0]);
        Const::create(['name' => 'TEST_MODULE_CONFIG', 'value' => '1', 'entity' => 1]);

        // Act
        $response = $this->actingAs($user)->get(route('admin.modules', [
            'action' => 'reset',
            'confirm' => 'yes'
        ]));

        // Assert
        $response->assertStatus(403);
        $this->assertDatabaseHas('llx_const', ['name' => 'TEST_MODULE_CONFIG']);
    }

    #[Test]
    public function it_requires_admin_permission_to_set_module(): void
    {
        // Arrange
        $user = User::factory()->create(['admin' => 0]);

        // Act
        $response = $this->actingAs($user)->get(route('admin.modules', [
            'action' => 'set',
            'module' => 'testmodule',
            'value' => '1'
        ]));

        // Assert
        $response->assertStatus(403);
        $this->assertDatabaseMissing('llx_const', ['name' => 'TESTMODULE_MODULE']);
    }
}
