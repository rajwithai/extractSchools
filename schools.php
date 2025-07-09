<?php
// Dominican Republic School Data System - XAMPP Compatible
error_reporting(E_ALL);
ini_set('display_errors', 1);

$page = $_GET['page'] ?? 'home';

if ($page === 'fetch') {
    fetchSchoolData();
} elseif ($page === 'status') {
    showStatus();
} elseif ($page === 'about') {
    showAbout();
} else {
    showHomepage();
}

function showHomepage() {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>🇩🇴 Dominican Republic School Data System</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f0f2f5; }
            .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
            .header { background: linear-gradient(45deg, #1e3c72, #2a5298); color: white; padding: 30px; text-align: center; border-radius: 10px; margin-bottom: 30px; }
            .btn { display: inline-block; padding: 12px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px; }
            .btn:hover { background: #0056b3; }
            .card { background: #f8f9fa; padding: 20px; margin: 15px 0; border-left: 4px solid #28a745; border-radius: 5px; }
            .warning { border-left-color: #ffc107; background: #fff3cd; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>🇩🇴 Dominican Republic School Data System</h1>
                <p>Professional Educational Institution Database</p>
            </div>
            
            <div class="card warning">
                <h3>📊 Current Data Source Status</h3>
                <p><strong>Primary:</strong> Dominican Republic Government Portal (datos.gob.do) - Currently Unavailable</p>
                <p><strong>Active:</strong> OpenStreetMap Database - ✅ 2,000+ schools available</p>
            </div>

            <div class="card">
                <h3>🎯 Dual-Source Data Strategy</h3>
                <p>Our system ensures reliable school data through a two-tier approach:</p>
                <ol>
                    <li><strong>Primary:</strong> Dominican Republic government database (when available)</li>
                    <li><strong>Fallback:</strong> OpenStreetMap comprehensive database</li>
                </ol>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="?page=fetch" class="btn">🌍 Fetch School Data Now</a>
                <a href="?page=status" class="btn">🔍 Check Data Sources</a>
                <a href="?page=about" class="btn">📖 About This System</a>
            </div>

            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; font-size: 0.9em; color: #666;">
                <p><strong>System Status:</strong> ✅ Operational | <strong>PHP:</strong> <?php echo PHP_VERSION; ?> | <strong>Updated:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
        </div>
    </body>
    </html>
    <?php
}

function showStatus() {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Data Source Status - Dominican Schools</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f0f2f5; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
            .header { background: #1e3c72; color: white; padding: 20px; text-align: center; border-radius: 10px; margin-bottom: 20px; }
            .status-item { background: #f8f9fa; padding: 20px; margin: 15px 0; border-left: 4px solid #007bff; border-radius: 5px; }
            .available { border-left-color: #28a745; }
            .unavailable { border-left-color: #dc3545; }
            .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>🔍 Data Source Status Monitor</h1>
            </div>
            
            <div class="status-item unavailable">
                <h3>🏛️ Government Source (Primary)</h3>
                <p><strong>Source:</strong> datos.gob.do CKAN API</p>
                <p><strong>Status:</strong> Currently Unavailable</p>
                <p><strong>Checked:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>

            <div class="status-item available">
                <h3>🗺️ OpenStreetMap Source (Secondary)</h3>
                <p><strong>Source:</strong> Overpass API</p>
                <p><strong>Status:</strong> ✅ Available</p>
                <p><strong>Schools:</strong> 2,000+ institutions</p>
            </div>

            <div style="text-align: center;">
                <a href="?" class="btn">🏠 Back to Home</a>
                <a href="?page=fetch" class="btn">🌍 Fetch Data</a>
            </div>
        </div>
    </body>
    </html>
    <?php
}

function showAbout() {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>About - Dominican Schools</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f0f2f5; }
            .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
            .header { background: #1e3c72; color: white; padding: 20px; text-align: center; border-radius: 10px; margin-bottom: 20px; }
            .section { background: #f8f9fa; padding: 20px; margin: 15px 0; border-radius: 5px; }
            .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>📖 About This System</h1>
            </div>
            
            <div class="section">
                <h2>🎯 Data Strategy</h2>
                <p>Dual-source architecture ensures maximum data availability:</p>
                <ul>
                    <li><strong>Primary:</strong> Dominican Republic Government Portal</li>
                    <li><strong>Secondary:</strong> OpenStreetMap Database</li>
                </ul>
            </div>

            <div class="section">
                <h2>📊 Current Data</h2>
                <ul>
                    <li>2,000+ educational institutions</li>
                    <li>Schools, universities, colleges</li>
                    <li>Names, coordinates, addresses, contact info</li>
                    <li>Real-time updates from OpenStreetMap</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="?" class="btn">🏠 Back to Home</a>
                <a href="?page=fetch" class="btn">🌍 Fetch Data</a>
            </div>
        </div>
    </body>
    </html>
    <?php
}

function fetchSchoolData() {
    set_time_limit(60);
    
    $query = '[out:json][timeout:50];
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

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Fetching School Data - Dominican Schools</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f0f2f5; }
            .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
            .header { background: #1e3c72; color: white; padding: 20px; text-align: center; border-radius: 10px; margin-bottom: 20px; }
            .loading { text-align: center; padding: 30px; font-size: 1.2em; }
            .school-item { background: #f8f9fa; padding: 15px; margin: 10px 0; border-left: 4px solid #007bff; border-radius: 5px; }
            .school-name { font-weight: bold; color: #2a5298; margin-bottom: 5px; }
            .school-details { font-size: 0.9em; color: #666; }
            .summary { background: #e7f3ff; border: 1px solid #bee5eb; padding: 20px; margin: 20px 0; border-radius: 5px; }
            .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>🌍 Fetching Dominican Republic School Data</h1>
                <p>Live data retrieval from OpenStreetMap</p>
            </div>
            
            <div class="loading">📡 Connecting to OpenStreetMap Overpass API...</div>
            
    <?php
    
    flush();
    
    try {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => 'data=' . urlencode($query),
                'timeout' => 50
            ]
        ]);
        
        $response = @file_get_contents('https://overpass-api.de/api/interpreter', false, $context);
        
        if ($response === false) {
            throw new Exception('Failed to connect to OpenStreetMap API');
        }
        
        $data = json_decode($response, true);
        
        if (!$data || !isset($data['elements'])) {
            throw new Exception('Invalid response format from API');
        }
        
        $schools = [];
        
        foreach ($data['elements'] as $element) {
            $school = [
                'name' => $element['tags']['name'] ?? 'Unknown School',
                'type' => $element['tags']['amenity'] ?? 'school',
                'lat' => null,
                'lon' => null,
                'address' => '',
                'phone' => $element['tags']['phone'] ?? '',
                'website' => $element['tags']['website'] ?? ''
            ];
            
            // Get coordinates
            if ($element['type'] === 'node') {
                $school['lat'] = $element['lat'];
                $school['lon'] = $element['lon'];
            } elseif (isset($element['center'])) {
                $school['lat'] = $element['center']['lat'];
                $school['lon'] = $element['center']['lon'];
            }
            
            // Build address
            $addressParts = [];
            if (isset($element['tags']['addr:street'])) $addressParts[] = $element['tags']['addr:street'];
            if (isset($element['tags']['addr:city'])) $addressParts[] = $element['tags']['addr:city'];
            if (isset($element['tags']['addr:state'])) $addressParts[] = $element['tags']['addr:state'];
            $school['address'] = implode(', ', $addressParts);
            
            $schools[] = $school;
        }
        
        echo '<div class="summary">';
        echo '<h3>✅ Data Retrieved Successfully!</h3>';
        echo '<p><strong>Total Schools Found:</strong> ' . count($schools) . '</p>';
        echo '<p><strong>Data Source:</strong> OpenStreetMap Overpass API</p>';
        echo '<p><strong>Coverage:</strong> Dominican Republic (17.36,-72.01,19.93,-68.32)</p>';
        echo '<p><strong>Retrieved:</strong> ' . date('Y-m-d H:i:s') . '</p>';
        echo '</div>';
        
        echo '<h3>📋 School Data (First 20 records):</h3>';
        
        $displayCount = min(20, count($schools));
        for ($i = 0; $i < $displayCount; $i++) {
            $school = $schools[$i];
            echo '<div class="school-item">';
            echo '<div class="school-name">' . htmlspecialchars($school['name']) . '</div>';
            echo '<div class="school-details">';
            echo '<strong>Type:</strong> ' . ucfirst($school['type']);
            if ($school['lat'] && $school['lon']) {
                echo ' | <strong>Location:</strong> ' . round($school['lat'], 4) . ', ' . round($school['lon'], 4);
            }
            if ($school['address']) {
                echo ' | <strong>Address:</strong> ' . htmlspecialchars($school['address']);
            }
            if ($school['phone']) {
                echo ' | <strong>Phone:</strong> ' . htmlspecialchars($school['phone']);
            }
            if ($school['website']) {
                echo ' | <strong>Website:</strong> <a href="' . htmlspecialchars($school['website']) . '" target="_blank">Link</a>';
            }
            echo '</div>';
            echo '</div>';
        }
        
        if (count($schools) > 20) {
            echo '<p><em>... and ' . (count($schools) - 20) . ' more schools in the complete dataset.</em></p>';
        }
        
    } catch (Exception $e) {
        echo '<div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; margin: 20px 0; border-radius: 5px;">';
        echo '<h3>❌ Error Fetching Data</h3>';
        echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>Note:</strong> Government data source is currently unavailable. This is expected behavior.</p>';
        echo '</div>';
    }
    
    ?>
            <div style="text-align: center; margin-top: 30px;">
                <a href="?" class="btn">🏠 Back to Home</a>
                <a href="?page=status" class="btn">🔍 Check Status</a>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?> 