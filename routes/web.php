<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/data-source-status', function () {
    // Check current status of both data sources
    $governmentStatus = 'Checking...';
    $openStreetMapStatus = 'Available';
    
    try {
        $response = Http::timeout(10)->withoutVerifying()->get('https://datos.gob.do/api/3/action/site_read');
        $governmentStatus = $response->successful() ? 'API Accessible (No School Data Available)' : 'Unavailable';
    } catch (Exception $e) {
        $governmentStatus = 'Unavailable';
    }
    
    return view('data-source-status', compact('governmentStatus', 'openStreetMapStatus'));
});

Route::get('/about-school-data', function () {
    return view('about-school-data');
}); 