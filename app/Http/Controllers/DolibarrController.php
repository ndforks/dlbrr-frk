<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

abstract class DolibarrController extends Controller
{
    /**
     * Execute a Dolibarr PHP file and return its output
     */
    protected function executeDolibarrFile(string $filePath): Response
    {
        $fullPath = base_path('app/Modules/' . $filePath);
        
        if (!File::exists($fullPath)) {
            abort(404, "Dolibarr file not found: {$filePath}");
        }

        // Start output buffering to capture Dolibarr's output
        ob_start();
        
        // Change to the directory containing the file (Dolibarr expects relative paths)
        $originalDir = getcwd();
        chdir(dirname($fullPath));
        
        try {
            // Include the Dolibarr file
            include $fullPath;
            
            // Get the captured output
            $content = ob_get_clean();
            
            // Restore original directory
            chdir($originalDir);
            
            return response($content);
        } catch (\Throwable $e) {
            ob_end_clean();
            chdir($originalDir);
            
            throw $e;
        }
    }
}
