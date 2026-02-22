<?php

namespace App\Services;

use App\Models\Const;
use Illuminate\Support\Facades\DB;

/**
 * Service class for managing Dolibarr modules and configuration
 */
class ModuleService
{
    /**
     * Reset all module configuration constants
     * 
     * Deletes all configuration entries with names containing '_MODULE_'
     * This effectively resets all module activations and settings
     * 
     * @return int Number of rows deleted
     */
    public function resetAllModules(): int
    {
        return Const::where('name', 'like', '%_MODULE_%')->delete();
    }

    /**
     * Activate or deactivate a module
     * 
     * @param string $moduleName Name of the module
     * @param bool $activate True to activate, false to deactivate
     * @return bool Success status
     */
    public function setModuleStatus(string $moduleName, bool $activate): bool
    {
        $constName = strtoupper($moduleName) . '_MODULE';
        
        if ($activate) {
            return Const::updateOrCreate(
                ['name' => $constName],
                [
                    'value' => '1',
                    'entity' => 1,
                    'type' => 'chaine',
                    'visible' => 0,
                ]
            ) !== null;
        } else {
            return Const::where('name', $constName)->delete() > 0;
        }
    }

    /**
     * Check if a module is enabled
     * 
     * @param string $moduleName Name of the module
     * @return bool True if module is enabled
     */
    public function isModuleEnabled(string $moduleName): bool
    {
        $constName = strtoupper($moduleName) . '_MODULE';
        
        $const = Const::where('name', $constName)
            ->where('value', '1')
            ->first();
            
        return $const !== null;
    }

    /**
     * Get all enabled modules
     * 
     * @return \Illuminate\Support\Collection Collection of module names
     */
    public function getEnabledModules()
    {
        return Const::where('name', 'like', '%_MODULE')
            ->where('value', '1')
            ->pluck('name')
            ->map(function ($name) {
                return str_replace('_MODULE', '', $name);
            });
    }

    /**
     * Get a configuration value
     * 
     * @param string $constName Configuration constant name
     * @param mixed $default Default value if not found
     * @return mixed Configuration value or default
     */
    public function getConfig(string $constName, $default = null)
    {
        $const = Const::where('name', $constName)->first();
        
        return $const ? $const->value : $default;
    }

    /**
     * Set a configuration value
     * 
     * @param string $constName Configuration constant name
     * @param mixed $value Value to set
     * @param int $entity Entity ID (default: 1)
     * @return bool Success status
     */
    public function setConfig(string $constName, $value, int $entity = 1): bool
    {
        return Const::updateOrCreate(
            ['name' => $constName],
            [
                'value' => (string) $value,
                'entity' => $entity,
                'type' => 'chaine',
                'visible' => 0,
            ]
        ) !== null;
    }

    /**
     * Delete a configuration constant
     * 
     * @param string $constName Configuration constant name
     * @return bool Success status (true if deleted, false if not found)
     */
    public function deleteConfig(string $constName): bool
    {
        return Const::where('name', $constName)->delete() > 0;
    }
}
