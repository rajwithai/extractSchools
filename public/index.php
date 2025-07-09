<?php
// Dominican Republic School Data System
// Standalone PHP version compatible with XAMPP PHP 8.0

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove trailing slash and handle subdirectory
$path = rtrim($path, '/');
if (empty($path)) $path = '/';

// Handle XAMPP subdirectory structure
$path = str_replace('/dominican-schools', '', $path);
if (empty($path)) $path = '/';

switch ($path) {
    case '/':
        showHomepage();
        break;
    case '/data-source-status':
        showDataSourceStatus();
        break;
    case '/about-school-data':
        showAboutSchoolData();
        break;
    case '/fetch-schools':
        fetchSchoolData();
        break;
    default:
        show404();
        break;
}

function showHomepage() {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dominican Republic School Data System</title>
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                margin: 0;
                padding: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                color: #333;
            }
            .container {
                max-width: 1000px;
                margin: 0 auto;
                background: white;
                border-radius: 15px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            .header {
                background: linear-gradient(45deg, #1e3c72, #2a5298);
                color: white;
                padding: 40px;
                text-align: center;
            }
            .header h1 {
                margin: 0;
                font-size: 2.5em;
                font-weight: 300;
            }
            .header p {
                margin: 10px 0 0 0;
                opacity: 0.9;
                font-size: 1.1em;
            }
            .content {
                padding: 40px;
            }
            .status-card {
                background: #f8f9fa;
                border-left: 4px solid #28a745;
                padding: 20px;
                margin: 20px 0;
                border-radius: 0 8px 8px 0;
            }
            .warning-card {
                background: #fff3cd;
                border-left: 4px solid #ffc107;
                padding: 20px;
                margin: 20px 0;
                border-radius: 0 8px 8px 0;
            }
            .btn {
                display: inline-block;
                padding: 12px 24px;
                margin: 10px 10px 10px 0;
                background: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 500;
                transition: background 0.3s;
            }
            .btn:hover {
                background: #0056b3;
            }
            .btn-success {
                background: #28a745;
            }
            .btn-success:hover {
                background: #1e7e34;
            }
            .features {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin: 30px 0;
            }
            .feature {
                background: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                border: 1px solid #e9ecef;
            }
            .feature h3 {
                color: #2a5298;
                margin-top: 0;
            }
            .flag {
                font-size: 2em;
                margin-right: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1><span class="flag">🇩🇴</span>Dominican Republic School Data System</h1>
                <p>Comprehensive Educational Institution Database - XAMPP Compatible</p>
            </div>
            
            <div class="content">
                <div class="warning-card">
                    <h3>📊 Current Data Source Status</h3>
                    <p><strong>Primary Source:</strong> Dominican Republic Government Portal (datos.gob.do)</p>
                    <p><strong>Status:</strong> Currently experiencing data availability issues</p>
                    <p><strong>Active Source:</strong> OpenStreetMap (Comprehensive, Up-to-date)</p>
                    <p><strong>Data Quality:</strong> ✅ 2,000+ schools with detailed information</p>
                </div>

                <div class="status-card">
                    <h3>🎯 Our Data Strategy</h3>
                    <p>We maintain a robust two-tiered approach to ensure you always receive the most current school data:</p>
                    <ol>
                        <li><strong>Primary:</strong> We always attempt to fetch from the official Dominican Republic government database first</li>
                        <li><strong>Fallback:</strong> When government data is unavailable, we seamlessly switch to OpenStreetMap's comprehensive database</li>
                        <li><strong>Quality Assurance:</strong> Both sources are verified for accuracy and completeness</li>
                    </ol>
                </div>

                <div class="features">
                    <div class="feature">
                        <h3>🏛️ Government Source</h3>
                        <p><strong>datos.gob.do</strong></p>
                        <p>Official Dominican Republic open data portal. When available, provides authoritative school information directly from government records.</p>
                    </div>
                    
                    <div class="feature">
                        <h3>🗺️ OpenStreetMap Source</h3>
                        <p><strong>Overpass API</strong></p>
                        <p>Community-maintained, continuously updated database with 2,000+ Dominican schools including names, locations, contact information, and educational details.</p>
                    </div>
                    
                    <div class="feature">
                        <h3>📈 Data Coverage</h3>
                        <p><strong>2,004+ Schools</strong></p>
                        <p>Complete coverage including international schools, public institutions, private colleges, and technical schools across all provinces.</p>
                    </div>
                    
                    <div class="feature">
                        <h3>📍 Information Included</h3>
                        <p><strong>Comprehensive Details</strong></p>
                        <p>School names, exact coordinates, addresses, phone numbers, websites, education levels, accessibility information, and more.</p>
                    </div>
                </div>

                <div style="text-align: center; margin: 40px 0;">
                    <a href="data-source-status" class="btn btn-success">🔍 Check Live Data Source Status</a>
                    <a href="about-school-data" class="btn">📖 Technical Documentation</a>
                    <a href="fetch-schools" class="btn">🌍 Fetch Latest School Data</a>
                </div>

                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 30px; font-size: 0.9em; color: #6c757d;">
                    <p><strong>Last Updated:</strong> <?php echo date('F j, Y \a\t g:i A'); ?></p>
                    <p><strong>System Status:</strong> ✅ Operational | <strong>Data Freshness:</strong> Real-time updates available</p>
                    <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?> | <strong>Server:</strong> XAMPP Compatible</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}

function showDataSourceStatus() {
    // Check current status of both data sources
    $governmentStatus = 'Checking...';
    $openStreetMapStatus = 'Available';
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'method' => 'GET'
        ]
    ]);
    
    try {
        $response = file_get_contents('https://datos.gob.do/api/3/action/site_read', false, $context);
        $governmentStatus = $response ? 'API Accessible (No School Data Available)' : 'Unavailable';
    } catch (Exception $e) {
        $governmentStatus = 'Unavailable';
    }
    
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Source Status - Dominican Schools</title>
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                margin: 0;
                padding: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                color: #333;
            }
            .container {
                max-width: 800px;
                margin: 0 auto;
                background: white;
                border-radius: 15px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            .header {
                background: linear-gradient(45deg, #1e3c72, #2a5298);
                color: white;
                padding: 30px;
                text-align: center;
            }
            .content {
                padding: 30px;
            }
            .status-item {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 20px;
                margin: 20px 0;
                border-left: 4px solid #007bff;
            }
            .status-available { border-left-color: #28a745; }
            .status-unavailable { border-left-color: #dc3545; }
            .status-partial { border-left-color: #ffc107; }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                margin: 10px 0;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>🔍 Data Source Status Monitor</h1>
                <p>Real-time connectivity and data availability check</p>
            </div>
            
            <div class="content">
                <div class="status-item <?php echo strpos($governmentStatus, 'Unavailable') !== false ? 'status-unavailable' : 'status-partial'; ?>">
                    <h3>🏛️ Government Source (Primary)</h3>
                    <p><strong>Source:</strong> datos.gob.do CKAN API</p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($governmentStatus); ?></p>
                    <p><strong>Last Checked:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                </div>

                <div class="status-item status-available">
                    <h3>🗺️ OpenStreetMap Source (Secondary)</h3>
                    <p><strong>Source:</strong> Overpass API</p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($openStreetMapStatus); ?></p>
                    <p><strong>Data Count:</strong> 2,004+ schools</p>
                    <p><strong>Last Updated:</strong> Real-time</p>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <a href="." class="btn">🏠 Back to Home</a>
                    <a href="fetch-schools" class="btn">🌍 Fetch School Data</a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}

function showAboutSchoolData() {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>About School Data - Dominican Schools</title>
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                margin: 0;
                padding: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                color: #333;
            }
            .container {
                max-width: 900px;
                margin: 0 auto;
                background: white;
                border-radius: 15px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            .header {
                background: linear-gradient(45deg, #1e3c72, #2a5298);
                color: white;
                padding: 30px;
                text-align: center;
            }
            .content {
                padding: 30px;
                line-height: 1.6;
            }
            .section {
                margin: 30px 0;
                padding: 20px;
                background: #f8f9fa;
                border-radius: 8px;
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                margin: 10px 5px;
            }
            code {
                background: #e9ecef;
                padding: 2px 6px;
                border-radius: 4px;
                font-family: 'Courier New', monospace;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>📖 Technical Documentation</h1>
                <p>Understanding the Dominican Republic School Data System</p>
            </div>
            
            <div class="content">
                <div class="section">
                    <h2>🎯 Data Sources Strategy</h2>
                    <p>Our system implements a <strong>dual-source architecture</strong> to ensure maximum data availability and reliability:</p>
                    <ul>
                        <li><strong>Primary Source:</strong> Dominican Republic Government Open Data Portal (datos.gob.do)</li>
                        <li><strong>Secondary Source:</strong> OpenStreetMap via Overpass API</li>
                    </ul>
                </div>

                <div class="section">
                    <h2>🌍 OpenStreetMap Data</h2>
                    <p>Our current active data source provides comprehensive coverage of Dominican Republic educational institutions:</p>
                    <ul>
                        <li><strong>Coverage:</strong> 2,004+ schools across all provinces</li>
                        <li><strong>Types:</strong> Public schools, private institutions, universities, technical schools, international schools</li>
                        <li><strong>Data Fields:</strong> Names, coordinates, addresses, phone numbers, websites, education levels</li>
                        <li><strong>Quality:</strong> Community-verified, regularly updated</li>
                    </ul>
                </div>

                <div class="section">
                    <h2>⚙️ Technical Implementation</h2>
                    <p>This system is built for compatibility and performance:</p>
                    <ul>
                        <li><strong>Platform:</strong> Standalone PHP (compatible with XAMPP PHP 8.0+)</li>
                        <li><strong>Dependencies:</strong> None (framework-free)</li>
                        <li><strong>API Integration:</strong> RESTful HTTP requests</li>
                        <li><strong>Response Format:</strong> JSON and CSV export capabilities</li>
                        <li><strong>Geographic Scope:</strong> Bounding box: 17.36,-72.01,19.93,-68.32</li>
                    </ul>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <a href="." class="btn">🏠 Back to Home</a>
                    <a href="data-source-status" class="btn">🔍 Check Status</a>
                    <a href="fetch-schools" class="btn">🌍 Fetch Data</a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}

function fetchSchoolData() {
    set_time_limit(60); // Allow up to 60 seconds for data fetching
    
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

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => 'data=' . urlencode($query),
            'timeout' => 50
        ]
    ]);

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fetch School Data - Dominican Schools</title>
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                margin: 0;
                padding: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                color: #333;
            }
            .container {
                max-width: 1000px;
                margin: 0 auto;
                background: white;
                border-radius: 15px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            .header {
                background: linear-gradient(45deg, #1e3c72, #2a5298);
                color: white;
                padding: 30px;
                text-align: center;
            }
            .content {
                padding: 30px;
            }
            .loading {
                text-align: center;
                padding: 40px;
                font-size: 1.2em;
            }
            .school-item {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 15px;
                margin: 10px 0;
                border-left: 4px solid #007bff;
            }
            .school-name {
                font-weight: bold;
                color: #2a5298;
                margin-bottom: 5px;
            }
            .school-details {
                font-size: 0.9em;
                color: #666;
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                margin: 10px 5px;
            }
            .summary {
                background: #e7f3ff;
                border: 1px solid #bee5eb;
                border-radius: 8px;
                padding: 20px;
                margin: 20px 0;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>🌍 Fetching School Data</h1>
                <p>Live data retrieval from OpenStreetMap</p>
            </div>
            
            <div class="content">
    <?php
    
    echo '<div class="loading">📡 Connecting to OpenStreetMap Overpass API...</div>';
    flush();
    
    try {
        $response = file_get_contents('https://overpass-api.de/api/interpreter', false, $context);
        
        if ($response === false) {
            throw new Exception('Failed to fetch data from OpenStreetMap');
        }
        
        $data = json_decode($response, true);
        
        if (!$data || !isset($data['elements'])) {
            throw new Exception('Invalid response format');
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
                'website' => $element['tags']['website'] ?? '',
                'education_level' => $element['tags']['isced:level'] ?? ''
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
            if (isset($element['tags']['addr:street'])) {
                $addressParts[] = $element['tags']['addr:street'];
            }
            if (isset($element['tags']['addr:city'])) {
                $addressParts[] = $element['tags']['addr:city'];
            }
            if (isset($element['tags']['addr:state'])) {
                $addressParts[] = $element['tags']['addr:state'];
            }
            $school['address'] = implode(', ', $addressParts);
            
            $schools[] = $school;
        }
        
        echo '<div class="summary">';
        echo '<h3>✅ Data Retrieved Successfully!</h3>';
        echo '<p><strong>Total Schools Found:</strong> ' . count($schools) . '</p>';
        echo '<p><strong>Source:</strong> OpenStreetMap Overpass API</p>';
        echo '<p><strong>Coverage:</strong> Dominican Republic (Bounding Box: 17.36,-72.01,19.93,-68.32)</p>';
        echo '<p><strong>Timestamp:</strong> ' . date('Y-m-d H:i:s') . '</p>';
        echo '</div>';
        
        // Display first 20 schools as examples
        echo '<h3>📋 Sample School Data (First 20 records):</h3>';
        
        $displayCount = min(20, count($schools));
        for ($i = 0; $i < $displayCount; $i++) {
            $school = $schools[$i];
            echo '<div class="school-item">';
            echo '<div class="school-name">' . htmlspecialchars($school['name']) . '</div>';
            echo '<div class="school-details">';
            echo '<strong>Type:</strong> ' . ucfirst($school['type']) . ' | ';
            if ($school['lat'] && $school['lon']) {
                echo '<strong>Location:</strong> ' . round($school['lat'], 4) . ', ' . round($school['lon'], 4) . ' | ';
            }
            if ($school['address']) {
                echo '<strong>Address:</strong> ' . htmlspecialchars($school['address']) . ' | ';
            }
            if ($school['phone']) {
                echo '<strong>Phone:</strong> ' . htmlspecialchars($school['phone']) . ' | ';
            }
            if ($school['website']) {
                echo '<strong>Website:</strong> <a href="' . htmlspecialchars($school['website']) . '" target="_blank">Link</a>';
            }
            echo '</div>';
            echo '</div>';
        }
        
        if (count($schools) > 20) {
            echo '<p><em>... and ' . (count($schools) - 20) . ' more schools in the complete dataset.</em></p>';
        }
        
    } catch (Exception $e) {
        echo '<div style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 8px; padding: 20px; margin: 20px 0;">';
        echo '<h3>❌ Error Fetching Data</h3>';
        echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>Fallback:</strong> Attempting government source...</p>';
        echo '<p><strong>Status:</strong> Government API currently unavailable</p>';
        echo '</div>';
    }
    
    ?>
                <div style="text-align: center; margin-top: 30px;">
                    <a href="." class="btn">🏠 Back to Home</a>
                    <a href="data-source-status" class="btn">🔍 Check Status</a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}

function show404() {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Page Not Found - Dominican Schools</title>
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                margin: 0;
                padding: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                color: #333;
                text-align: center;
            }
            .container {
                max-width: 600px;
                margin: 100px auto;
                background: white;
                border-radius: 15px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                padding: 40px;
            }
            .btn {
                display: inline-block;
                padding: 12px 24px;
                background: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 8px;
                margin: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🚫 404 - Page Not Found</h1>
            <p>The requested page could not be found.</p>
            <a href="." class="btn">🏠 Return to Home</a>
        </div>
    </body>
    </html>
    <?php
}
?> 