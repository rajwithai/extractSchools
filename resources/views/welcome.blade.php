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
            <p>Comprehensive Educational Institution Database with Dual Data Source Strategy</p>
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
                    <p><strong>2,004 Schools</strong></p>
                    <p>Complete coverage including international schools, public institutions, private colleges, and technical schools across all provinces.</p>
                </div>
                
                <div class="feature">
                    <h3>📍 Information Included</h3>
                    <p><strong>Comprehensive Details</strong></p>
                    <p>School names, exact coordinates, addresses, phone numbers, websites, education levels, accessibility information, and more.</p>
                </div>
            </div>

            <div style="text-align: center; margin: 40px 0;">
                <a href="/data-source-status" class="btn btn-success">🔍 Check Live Data Source Status</a>
                <a href="/about-school-data" class="btn">📖 Technical Documentation</a>
            </div>

            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 30px; font-size: 0.9em; color: #6c757d;">
                <p><strong>Last Updated:</strong> {{ date('F j, Y \a\t g:i A') }}</p>
                <p><strong>System Status:</strong> ✅ Operational | <strong>Data Freshness:</strong> Real-time updates available</p>
            </div>
        </div>
    </div>
</body>
</html> 