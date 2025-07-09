<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Documentation - Dominican Republic School Data</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
            line-height: 1.6;
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
        .content {
            padding: 40px;
        }
        .section {
            margin: 40px 0;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 4px solid #2a5298;
        }
        .code-block {
            background: #2d3748;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            margin: 15px 0;
        }
        .command {
            background: #1a202c;
            color: #68d391;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #e9ecef;
            padding: 12px;
            text-align: left;
        }
        .data-table th {
            background: #f1f3f4;
            font-weight: 600;
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
        .highlight {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
        .api-endpoint {
            background: #e8f4fd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2196f3;
            margin: 15px 0;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📖 Technical Documentation</h1>
            <p>Dominican Republic School Data System - Implementation Details</p>
        </div>
        
        <div class="content">
            <div class="section">
                <h2>🎯 System Overview</h2>
                <p>Our Dominican Republic School Data System implements a <strong>dual-source architecture</strong> to ensure maximum data availability and reliability.</p>
                
                <h3>Data Source Hierarchy:</h3>
                <ol>
                    <li><strong>Primary:</strong> Dominican Republic Government CKAN API</li>
                    <li><strong>Secondary:</strong> OpenStreetMap Overpass API</li>
                </ol>
            </div>

            <div class="section">
                <h2>🗺️ Current Active Source</h2>
                <h3>OpenStreetMap Overpass API</h3>
                <p><strong>Status:</strong> Currently providing all school data (2,004 schools)</p>
                <p><strong>Data Quality:</strong> High (community-verified and continuously updated)</p>
                
                <div class="highlight">
                    <strong>🔄 Automatic Failover:</strong> Government source attempted first, but OpenStreetMap is currently active due to government data availability issues.
                </div>
            </div>

            <div style="text-align: center; margin: 40px 0;">
                <a href="/" class="btn">🏠 Back to Home</a>
                <a href="/data-source-status" class="btn">🔍 Check Current Status</a>
            </div>
        </div>
    </div>
</body>
</html> 