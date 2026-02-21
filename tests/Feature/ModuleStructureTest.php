<?php

namespace Tests\Feature;

use Tests\TestCase;

class ModuleStructureTest extends TestCase
{
    public function test_app_modules_directory_exists(): void
    {
        $this->assertDirectoryExists(app_path('Modules'));
    }

    public function test_key_modules_exist(): void
    {
        $modules = [
            'core',
            'user',
            'product',
            'societe',
            'commande',
            'compta',
            'projet',
            'ticket',
            'admin',
            'api',
            'adherents',
            'contact',
            'fourn',
            'expedition',
            'contrat',
        ];

        foreach ($modules as $module) {
            $this->assertDirectoryExists(app_path("Modules/{$module}"), "Module {$module} should exist in app/Modules");
        }
    }

    public function test_resources_views_directory_exists(): void
    {
        $this->assertDirectoryExists(resource_path('views'));
    }

    public function test_template_files_exist_in_views(): void
    {
        $this->assertDirectoryExists(resource_path('views'));
        
        // Check that template files were moved
        $templateFiles = glob(resource_path('views') . '/**/*.tpl.php');
        $this->assertGreaterThan(0, count($templateFiles), 'Template files should exist in resources/views');
    }

    public function test_public_htdocs_exists_for_backward_compatibility(): void
    {
        $this->assertDirectoryExists(public_path('htdocs'));
    }

    public function test_core_module_has_class_directory(): void
    {
        $this->assertDirectoryExists(app_path('Modules/core/class'));
    }

    public function test_modules_have_consistent_structure(): void
    {
        $modulesWithClasses = ['user', 'product', 'societe', 'commande'];
        
        foreach ($modulesWithClasses as $module) {
            $classDir = app_path("Modules/{$module}/class");
            if (is_dir($classDir)) {
                $this->assertDirectoryExists($classDir, "Module {$module} should have a class directory");
            }
        }
    }
}
