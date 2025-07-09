<?php
// Standalone Dominican Schools Data Fetcher - CSV Generator
// Compatible with XAMPP PHP 8.0+

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(120);

echo "Dominican Republic School Data Fetcher - CSV Generator\n";
echo "=====================================================\n\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Script started at: " . date('Y-m-d H:i:s') . "\n\n";

// Create storage directory if it doesn't exist
$storageDir = 'storage/app';
if (!is_dir($storageDir)) {
    echo "Creating storage directory: {$storageDir}\n";
    mkdir($storageDir, 0755, true);
} else {
    echo "Storage directory exists: {$storageDir}\n";
}

// Generate unique filename with timestamp
$timestamp = date('Y-m-d_H-i-s');
$csvFile = $storageDir . "/dominican_schools_{$timestamp}.csv";
$jsonFile = $storageDir . "/dominican_schools_{$timestamp}.json";

echo "CSV file will be: {$csvFile}\n";
echo "JSON file will be: {$jsonFile}\n\n";

echo "📡 Connecting to OpenStreetMap Overpass API...\n";

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

echo "Query length: " . strlen($query) . " characters\n";

try {
    echo "Creating HTTP context...\n";
    
    // Make API request
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => 'data=' . urlencode($query),
            'timeout' => 60
        ]
    ]);
    
    echo "Making request to Overpass API...\n";
    
    $response = file_get_contents('https://overpass-api.de/api/interpreter', false, $context);
    
    if ($response === false) {
        throw new Exception('Failed to fetch data from OpenStreetMap API');
    }
    
    echo "✅ Data retrieved successfully! Response length: " . strlen($response) . " bytes\n";
    
    // Parse JSON response
    $data = json_decode($response, true);
    
    if (!$data) {
        throw new Exception('Failed to parse JSON response');
    }
    
    if (!isset($data['elements'])) {
        throw new Exception('No elements found in response');
    }
    
    echo "📊 Processing " . count($data['elements']) . " schools...\n";
    
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
    
    $processedCount = 0;
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
        
        $processedCount++;
        if ($processedCount % 100 == 0) {
            echo "Processed {$processedCount} schools...\n";
        }
    }
    
    echo "Processing complete. Saving files...\n";
    
    // Save JSON file
    $jsonContent = json_encode($schools, JSON_PRETTY_PRINT);
    if (file_put_contents($jsonFile, $jsonContent) !== false) {
        echo "💾 JSON saved: {$jsonFile}\n";
    } else {
        echo "❌ Failed to save JSON file\n";
    }
    
    // Save CSV file
    $csvHandle = fopen($csvFile, 'w');
    if ($csvHandle) {
        $rowCount = 0;
        foreach ($csvData as $row) {
            if (fputcsv($csvHandle, $row) === false) {
                echo "❌ Failed to write CSV row {$rowCount}\n";
                break;
            }
            $rowCount++;
        }
        fclose($csvHandle);
        echo "💾 CSV saved: {$csvFile} ({$rowCount} rows)\n";
    } else {
        throw new Exception('Could not create CSV file');
    }
    
    echo "\n🎉 SUCCESS!\n";
    echo "=============================\n";
    echo "📊 Total schools: " . count($schools) . "\n";
    echo "📁 CSV file: {$csvFile}\n";
    echo "📁 JSON file: {$jsonFile}\n";
    echo "📈 CSV size: " . round(filesize($csvFile) / 1024, 2) . " KB\n";
    echo "📈 JSON size: " . round(filesize($jsonFile) / 1024, 2) . " KB\n";
    echo "🕒 Generated: " . date('Y-m-d H:i:s') . "\n";
    echo "🌍 Source: OpenStreetMap Overpass API\n";
    echo "=============================\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "📝 Note: Government data source is currently unavailable.\n";
    echo "🔄 This is expected - using OpenStreetMap as backup source.\n";
    exit(1);
}
?> 