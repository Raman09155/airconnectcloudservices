<?php
require_once __DIR__ . '/vendor/autoload.php'; // Dotenv autoloader

use Dotenv\Dotenv;

header('Content-Type: application/json');

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get credentials from .env
$apiKey = $_ENV['GODADDY_API_KEY'];
$apiSecret = $_ENV['GODADDY_API_SECRET'];

// Get data from JS
$data = json_decode(file_get_contents('php://input'), true);
$domainName = isset($data['domain']) ? $data['domain'] : '';
$extensions = isset($data['extensions']) ? $data['extensions'] : [];

if (empty($domainName) || empty($extensions)) {
    echo json_encode(['error' => 'Missing domain or extensions']);
    exit;
}

// API URL
$baseUrl = "https://api.godaddy.com/v1/domains/available";
$results = [];

foreach ($extensions as $ext) {
    $fullDomain = $domainName . $ext;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . "?domain=" . urlencode($fullDomain));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: sso-key {$apiKey}:{$apiSecret}",
        "Accept: application/json"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $resData = json_decode($response, true);
    $available = isset($resData['available']) ? $resData['available'] : false;

    $results[] = [
        'domain' => $fullDomain,
        'available' => $available
    ];
}

echo json_encode(['results' => $results]);
