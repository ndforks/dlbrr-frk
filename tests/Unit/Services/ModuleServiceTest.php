<?php

namespace Tests\Unit\Services;

use App\Models\Const;
use App\Services\ModuleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ModuleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ModuleService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ModuleService();
    }

    #[Test]
    public function it_resets_all_module_configurations(): void
    {
        // Arrange
        Const::create(['name' => 'TEST_MODULE_CONFIG', 'value' => '1', 'entity' => 1]);
        Const::create(['name' => 'ANOTHER_MODULE_SETTING', 'value' => '1', 'entity' => 1]);
        Const::create(['name' => 'NOT_A_MODULE', 'value' => '1', 'entity' => 1]);

        // Act
        $deletedCount = $this->service->resetAllModules();

        // Assert
        $this->assertEquals(2, $deletedCount);
        $this->assertDatabaseMissing('llx_const', ['name' => 'TEST_MODULE_CONFIG']);
        $this->assertDatabaseMissing('llx_const', ['name' => 'ANOTHER_MODULE_SETTING']);
        $this->assertDatabaseHas('llx_const', ['name' => 'NOT_A_MODULE']);
    }

    #[Test]
    public function it_activates_a_module(): void
    {
        // Arrange
        $moduleName = 'testmodule';

        // Act
        $result = $this->service->setModuleStatus($moduleName, true);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('llx_const', [
            'name' => 'TESTMODULE_MODULE',
            'value' => '1',
        ]);
    }

    #[Test]
    public function it_deactivates_a_module(): void
    {
        // Arrange
        $moduleName = 'testmodule';
        Const::create(['name' => 'TESTMODULE_MODULE', 'value' => '1', 'entity' => 1]);

        // Act
        $result = $this->service->setModuleStatus($moduleName, false);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('llx_const', ['name' => 'TESTMODULE_MODULE']);
    }

    #[Test]
    public function it_checks_if_module_is_enabled(): void
    {
        // Arrange
        Const::create(['name' => 'ENABLED_MODULE', 'value' => '1', 'entity' => 1]);
        Const::create(['name' => 'DISABLED_MODULE', 'value' => '0', 'entity' => 1]);

        // Act & Assert
        $this->assertTrue($this->service->isModuleEnabled('enabled'));
        $this->assertFalse($this->service->isModuleEnabled('disabled'));
        $this->assertFalse($this->service->isModuleEnabled('nonexistent'));
    }

    #[Test]
    public function it_gets_all_enabled_modules(): void
    {
        // Arrange
        Const::create(['name' => 'MODULE1_MODULE', 'value' => '1', 'entity' => 1]);
        Const::create(['name' => 'MODULE2_MODULE', 'value' => '1', 'entity' => 1]);
        Const::create(['name' => 'MODULE3_MODULE', 'value' => '0', 'entity' => 1]);
        Const::create(['name' => 'NOT_A_MODULE', 'value' => '1', 'entity' => 1]);

        // Act
        $enabledModules = $this->service->getEnabledModules();

        // Assert
        $this->assertCount(2, $enabledModules);
        $this->assertTrue($enabledModules->contains('MODULE1'));
        $this->assertTrue($enabledModules->contains('MODULE2'));
        $this->assertFalse($enabledModules->contains('MODULE3'));
    }

    #[Test]
    public function it_gets_a_configuration_value(): void
    {
        // Arrange
        Const::create(['name' => 'TEST_CONFIG', 'value' => 'test_value', 'entity' => 1]);

        // Act
        $value = $this->service->getConfig('TEST_CONFIG');

        // Assert
        $this->assertEquals('test_value', $value);
    }

    #[Test]
    public function it_returns_default_when_config_not_found(): void
    {
        // Act
        $value = $this->service->getConfig('NONEXISTENT_CONFIG', 'default_value');

        // Assert
        $this->assertEquals('default_value', $value);
    }

    #[Test]
    public function it_sets_a_configuration_value(): void
    {
        // Act
        $result = $this->service->setConfig('NEW_CONFIG', 'new_value');

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('llx_const', [
            'name' => 'NEW_CONFIG',
            'value' => 'new_value',
            'entity' => 1,
        ]);
    }

    #[Test]
    public function it_updates_an_existing_configuration_value(): void
    {
        // Arrange
        Const::create(['name' => 'EXISTING_CONFIG', 'value' => 'old_value', 'entity' => 1]);

        // Act
        $result = $this->service->setConfig('EXISTING_CONFIG', 'new_value');

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('llx_const', [
            'name' => 'EXISTING_CONFIG',
            'value' => 'new_value',
        ]);
        $this->assertDatabaseMissing('llx_const', [
            'name' => 'EXISTING_CONFIG',
            'value' => 'old_value',
        ]);
    }

    #[Test]
    public function it_deletes_a_configuration(): void
    {
        // Arrange
        Const::create(['name' => 'TO_DELETE', 'value' => 'some_value', 'entity' => 1]);

        // Act
        $result = $this->service->deleteConfig('TO_DELETE');

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('llx_const', ['name' => 'TO_DELETE']);
    }

    #[Test]
    public function it_returns_false_when_deleting_nonexistent_config(): void
    {
        // Act
        $result = $this->service->deleteConfig('NONEXISTENT');

        // Assert
        $this->assertFalse($result);
    }
}
