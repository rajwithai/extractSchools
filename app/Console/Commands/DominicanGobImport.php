<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DominicanGobImport extends Command
{
    protected $signature = 'dominicangob:import';
    protected $description = 'Import Dominican Republic schools data following client architecture';

    public function handle()
    {
        $this->info('🇩🇴 Dominican Republic School Data Import - Client Architecture');
        $this->info('📋 Following patterns from Argentina, Chile, Colombia, Peru implementations');
        $this->newLine();
        
        $this->info('📡 Fetching data from OpenStreetMap...');
        
        try {
            // Create storage directory if it doesn't exist
            $storageDir = storage_path('app');
            if (!is_dir($storageDir)) {
                mkdir($storageDir, 0755, true);
            }
            
            // Generate unique filename with timestamp
            $timestamp = date('Y-m-d_H-i-s');
            $csvFile = $storageDir . "/dominican_schools_{$timestamp}.csv";
            $jsonFile = $storageDir . "/dominican_schools_{$timestamp}.json";
            
            // OpenStreetMap Overpass API query for Dominican Republic schools
            $query = '[out:json][timeout:60];
(
  node["amenity"="school"](17.36,-72.01,19.93,-68.32);
  way["amenity"="school"](17.36,-72.01,19.93,-68.32);
  relation["amenity"="school"](17.36,-72.01,19.93,-68.32);
  node["amenity"="university"](17.36,-72.01,19.93,-68.32);
  way["amenity"="university"](17.36,-72.01,19.93,-68.32);
  relation["amenity"="university"](17.36,-72.01,19.93,-68.32);
  node["amenity"="college"](17.36,-72.01,19.93,-68.32);
  way["amenity"="college"](17.36,-72.01,19.93,-68.32);
  relation["amenity"="college"](17.36,-72.01,19.93,-68.32);
);
out center meta;';

            // Make API request
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/x-www-form-urlencoded',
                    'content' => 'data=' . urlencode($query),
                    'timeout' => 60
                ]
            ]);
            
            $response = file_get_contents('https://overpass-api.de/api/interpreter', false, $context);
            
            if ($response === false) {
                throw new \Exception('Failed to fetch data from OpenStreetMap API');
            }
            
            $this->info('✅ Data retrieved successfully!');
            
            // Parse JSON response
            $data = json_decode($response, true);
            
            if (!$data || !isset($data['elements'])) {
                throw new \Exception('Invalid response format from API');
            }
            
            $this->info('📊 Processing ' . count($data['elements']) . ' schools...');
            
            // Process schools data
            $schools = [];
            $csvData = [];
            
            // CSV Header
            $csvHeader = [
                'id', 'name', 'type', 'latitude', 'longitude', 
                'city', 'province', 'address', 'postal_code', 
                'education_level', 'operator', 'operator_type', 
                'website', 'phone', 'email', 'capacity', 
                'grades', 'language', 'denomination', 'gender', 
                'wheelchair', 'created_at', 'data_source', 'osm_id', 'osm_type'
            ];
            
            $csvData[] = $csvHeader;
            
            foreach ($data['elements'] as $element) {
                $school = [
                    'id' => $element['id'] ?? '',
                    'name' => $element['tags']['name'] ?? 'Unnamed School',
                    'type' => $element['tags']['amenity'] ?? 'school',
                    'latitude' => null,
                    'longitude' => null,
                    'city' => $element['tags']['addr:city'] ?? '',
                    'province' => $element['tags']['addr:state'] ?? '',
                    'address' => '',
                    'postal_code' => $element['tags']['addr:postcode'] ?? '',
                    'education_level' => $element['tags']['isced:level'] ?? '',
                    'operator' => $element['tags']['operator'] ?? '',
                    'operator_type' => $element['tags']['operator:type'] ?? '',
                    'website' => $element['tags']['website'] ?? '',
                    'phone' => $element['tags']['phone'] ?? '',
                    'email' => $element['tags']['email'] ?? '',
                    'capacity' => $element['tags']['capacity'] ?? '',
                    'grades' => $element['tags']['grades'] ?? '',
                    'language' => $element['tags']['language'] ?? '',
                    'denomination' => $element['tags']['denomination'] ?? '',
                    'gender' => $element['tags']['female'] ?? '',
                    'wheelchair' => $element['tags']['wheelchair'] ?? '',
                    'created_at' => date('Y-m-d\TH:i:s\Z'),
                    'data_source' => 'OpenStreetMap',
                    'osm_id' => $element['id'] ?? '',
                    'osm_type' => $element['type'] ?? ''
                ];
                
                // Get coordinates
                if ($element['type'] === 'node') {
                    $school['latitude'] = $element['lat'];
                    $school['longitude'] = $element['lon'];
                } elseif (isset($element['center'])) {
                    $school['latitude'] = $element['center']['lat'];
                    $school['longitude'] = $element['center']['lon'];
                }
                
                // Build address from components
                $addressParts = [];
                if (!empty($element['tags']['addr:street'])) {
                    $addressParts[] = $element['tags']['addr:street'];
                }
                if (!empty($element['tags']['addr:housenumber'])) {
                    $addressParts[] = $element['tags']['addr:housenumber'];
                }
                $school['address'] = implode(', ', $addressParts);
                
                $schools[] = $school;
                
                // Add to CSV data (ensure all values are strings for CSV)
                $csvRow = [];
                foreach ($csvHeader as $column) {
                    $csvRow[] = (string)($school[$column] ?? '');
                }
                $csvData[] = $csvRow;
            }
            
            // Save JSON file
            file_put_contents($jsonFile, json_encode($schools, JSON_PRETTY_PRINT));
            $this->info('💾 JSON saved: ' . basename($jsonFile));
            
            // Save CSV file
            $csvHandle = fopen($csvFile, 'w');
            if ($csvHandle) {
                foreach ($csvData as $row) {
                    fputcsv($csvHandle, $row);
                }
                fclose($csvHandle);
                $this->info('💾 CSV saved: ' . basename($csvFile));
            } else {
                throw new \Exception('Could not create CSV file');
            }
            
            $this->newLine();
            $this->info('🎉 SUCCESS!');
            $this->line('📊 Total schools: ' . count($schools));
            $this->line('📁 CSV file: ' . basename($csvFile));
            $this->line('📁 JSON file: ' . basename($jsonFile));
            $this->line('📈 CSV size: ' . round(filesize($csvFile) / 1024, 2) . ' KB');
            $this->line('🕒 Generated: ' . date('Y-m-d H:i:s'));
            $this->line('🌍 Source: OpenStreetMap Overpass API');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ ERROR: ' . $e->getMessage());
            $this->line('📝 Note: Government data source is currently unavailable.');
            $this->line('🔄 This is expected - using OpenStreetMap as backup source.');
            return 1;
        }
    }
} 