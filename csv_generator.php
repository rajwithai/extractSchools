<?php
// Web-based Dominican Schools CSV Generator
set_time_limit(120);

if ($_GET['action'] ?? '' === 'generate') {
    header('Content-Type: text/plain');
    
    echo "🇩🇴 Dominican Republic Schools CSV Generator\n";
    echo "=============================================\n\n";
    
    // Create storage directory if it doesn't exist
    $storageDir = 'storage/app';
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0755, true);
        echo "📁 Created storage directory: {$storageDir}\n";
    }
    
    // Generate unique filename with timestamp
    $timestamp = date('Y-m-d_H-i-s');
    $csvFile = $storageDir . "/dominican_schools_{$timestamp}.csv";
    $jsonFile = $storageDir . "/dominican_schools_{$timestamp}.json";
    
    echo "📁 Will generate: " . basename($csvFile) . "\n\n";
    
    echo "📡 Fetching data from OpenStreetMap...\n";
    
    // OpenStreetMap query
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

    try {
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
            throw new Exception('Failed to fetch data from OpenStreetMap API');
        }
        
        echo "✅ Data retrieved! (" . strlen($response) . " bytes)\n";
        
        $data = json_decode($response, true);
        
        if (!$data || !isset($data['elements'])) {
            throw new Exception('Invalid response format');
        }
        
        echo "📊 Processing " . count($data['elements']) . " schools...\n";
        
        // Process data
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
            
            // Build address
            $addressParts = [];
            if (!empty($element['tags']['addr:street'])) {
                $addressParts[] = $element['tags']['addr:street'];
            }
            if (!empty($element['tags']['addr:housenumber'])) {
                $addressParts[] = $element['tags']['addr:housenumber'];
            }
            $school['address'] = implode(', ', $addressParts);
            
            $schools[] = $school;
            
            // Add to CSV
            $csvRow = [];
            foreach ($csvHeader as $column) {
                $csvRow[] = (string)($school[$column] ?? '');
            }
            $csvData[] = $csvRow;
        }
        
        // Save JSON
        file_put_contents($jsonFile, json_encode($schools, JSON_PRETTY_PRINT));
        echo "💾 JSON saved: " . basename($jsonFile) . "\n";
        
        // Save CSV
        $csvHandle = fopen($csvFile, 'w');
        if ($csvHandle) {
            foreach ($csvData as $row) {
                fputcsv($csvHandle, $row);
            }
            fclose($csvHandle);
            echo "💾 CSV saved: " . basename($csvFile) . "\n";
        }
        
        echo "\n🎉 SUCCESS!\n";
        echo "📊 Total schools: " . count($schools) . "\n";
        echo "📁 Files saved in storage/app/\n";
        echo "📈 CSV size: " . round(filesize($csvFile) / 1024, 2) . " KB\n";
        echo "🕒 Generated: " . date('Y-m-d H:i:s') . "\n";
        
    } catch (Exception $e) {
        echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    }
    
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dominican Schools CSV Generator</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .btn { display: inline-block; padding: 15px 30px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 16px; }
        .btn:hover { background: #0056b3; }
        .info { background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🇩🇴 Dominican Schools CSV Generator</h1>
            <p>Generate fresh CSV data for Dominican Republic schools</p>
        </div>
        
        <div class="info">
            <h3>📊 What this generates:</h3>
            <ul>
                <li>2,000+ Dominican Republic schools</li>
                <li>Complete data: names, coordinates, addresses, phones</li>
                <li>CSV format compatible with client requirements</li>
                <li>Saved to storage/app/ directory</li>
            </ul>
        </div>
        
        <div style="text-align: center;">
            <a href="?action=generate" class="btn">🚀 Generate New CSV Data</a>
        </div>
        
        <div style="margin-top: 30px; font-size: 0.9em; color: #666;">
            <p><strong>Note:</strong> This will fetch live data from OpenStreetMap and create a new timestamped CSV file.</p>
            <p><strong>Source:</strong> OpenStreetMap Overpass API</p>
        </div>
    </div>
</body>
</html> 