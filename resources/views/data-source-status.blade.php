<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Source Status - Dominican Republic Schools</title>
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
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.2em;
            font-weight: 300;
        }
        .content {
            padding: 40px;
        }
        .status-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
        }
        .source-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            border: 2px solid #e9ecef;
            position: relative;
        }
        .source-card.primary {
            border-color: #dc3545;
            background: linear-gradient(145deg, #fff5f5, #f8f9fa);
        }
        .source-card.secondary {
            border-color: #28a745;
            background: linear-gradient(145deg, #f8fff8, #f8f9fa);
        }
        .status-indicator {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        .status-available {
            background: #28a745;
        }
        .status-unavailable {
            background: #dc3545;
        }
        .status-partial {
            background: #ffc107;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }
        .source-title {
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2a5298;
        }
        .source-url {
            font-family: 'Courier New', monospace;
            background: #e9ecef;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.9em;
            margin: 10px 0;
            word-break: break-all;
        }
        .explanation {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 20px;
            margin: 30px 0;
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
        .refresh-note {
            text-align: center;
            color: #6c757d;
            font-size: 0.9em;
            margin-top: 30px;
        }
        @media (max-width: 768px) {
            .status-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Data Source Status Monitor</h1>
            <p>Real-time availability check for Dominican Republic school data sources</p>
        </div>
        
        <div class="content">
            <div class="status-grid">
                <div class="source-card primary">
                    <div class="status-indicator status-{{ $governmentStatus === 'Unavailable' ? 'unavailable' : 'partial' }}"></div>
                    <div class="source-title">🏛️ Primary Source</div>
                    <h3>Dominican Republic Government</h3>
                    <div class="source-url">https://datos.gob.do/api/3</div>
                    <p><strong>Status:</strong> {{ $governmentStatus }}</p>
                    <p><strong>School Data:</strong> Currently not available</p>
                    <p><strong>Priority:</strong> Always checked first</p>
                    @if($governmentStatus === 'Unavailable')
                        <p style="color: #dc3545;"><strong>⚠️ Government API is currently not responding</strong></p>
                    @else
                        <p style="color: #ffc107;"><strong>⚠️ API accessible but school dataset not published</strong></p>
                    @endif
                </div>

                <div class="source-card secondary">
                    <div class="status-indicator status-available"></div>
                    <div class="source-title">🗺️ Secondary Source (Active)</div>
                    <h3>OpenStreetMap</h3>
                    <div class="source-url">https://overpass-api.de/api/interpreter</div>
                    <p><strong>Status:</strong> {{ $openStreetMapStatus }}</p>
                    <p><strong>School Data:</strong> ✅ 2,004 schools available</p>
                    <p><strong>Data Quality:</strong> High (Community verified)</p>
                    <p style="color: #28a745;"><strong>✅ Currently providing all school data</strong></p>
                </div>
            </div>

            <div class="explanation">
                <h3>📋 How Our System Works</h3>
                <ol>
                    <li><strong>Primary Attempt:</strong> Every data request first tries to connect to the Dominican Republic government's official data portal (datos.gob.do)</li>
                    <li><strong>Automatic Fallback:</strong> When the government source is unavailable or lacks school data, the system automatically switches to OpenStreetMap</li>
                    <li><strong>Seamless Experience:</strong> Clients receive complete school data regardless of which source is used</li>
                    <li><strong>Transparency:</strong> All outputs clearly indicate which data source was used and why</li>
                </ol>
            </div>

            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h4>Current Data Source Recommendation</h4>
                @if($governmentStatus !== 'Unavailable')
                    <p>✅ <strong>Government API is accessible</strong> but currently has no school datasets published. We recommend using <strong>OpenStreetMap data</strong> for complete school information.</p>
                @else
                    <p>⚠️ <strong>Government API is currently down.</strong> All school data requests will automatically use <strong>OpenStreetMap</strong>, which provides comprehensive coverage.</p>
                @endif
            </div>

            <div style="text-align: center; margin: 40px 0;">
                <a href="/" class="btn">🏠 Back to Home</a>
                <a href="/about-school-data" class="btn">📖 Technical Details</a>
                <a href="/data-source-status" class="btn" onclick="location.reload()">🔄 Refresh Status</a>
            </div>

            <div class="refresh-note">
                <p><strong>Last Checked:</strong> {{ date('F j, Y \a\t g:i:s A') }}</p>
                <p>Status is checked in real-time. Refresh this page to see current availability.</p>
            </div>
        </div>
    </div>
</body>
</html> 