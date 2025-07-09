<?php

namespace App\Http\Controllers\Adminpanel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class ServerSelectionController extends Controller
{
    /**
     * Activate database connection for specific country
     *
     * @param string $countryCode
     * @return void
     */
    public function activate_database($countryCode)
    {
        // Country database mapping
        $databases = [
            'ar' => 'mysql_ar', // Argentina
            'cl' => 'mysql_cl', // Chile
            'co' => 'mysql_co', // Colombia
            'pe' => 'mysql_pe', // Peru
            'do' => 'mysql_do', // Dominican Republic
        ];

        $databaseName = $databases[$countryCode] ?? 'mysql';

        // For now, we'll use the default connection since we don't have 
        // multiple database configurations set up
        // In a full implementation, this would switch database connections
        
        // Set default database connection
        Config::set('database.default', $databaseName);
        
        // You can also set specific connection parameters here
        // This is a placeholder for the client's multi-database architecture
        DB::purge($databaseName);
        
        // Log the database activation
        \Log::info("Database activated for country: {$countryCode} -> {$databaseName}");
    }

    /**
     * Get current active database
     *
     * @return string
     */
    public function getCurrentDatabase()
    {
        return Config::get('database.default');
    }

    /**
     * Test database connection for country
     *
     * @param string $countryCode
     * @return bool
     */
    public function testConnection($countryCode)
    {
        try {
            $this->activate_database($countryCode);
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            \Log::error("Database connection failed for {$countryCode}: " . $e->getMessage());
            return false;
        }
    }
} 