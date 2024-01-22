<?php
$api_id = 'f54f0db5';
$api_key = '94527f02437544cd169ac13168907cd8';

// Prepare the API endpoint URL with query parameters
$search_query = isset($_GET['q']) ? urlencode($_GET['q']) : 'chicken'; // Default search query is 'chicken'
$api_url = 'https://api.edamam.com/search?q=' . $search_query . '&app_id=' . $api_id . '&app_key=' . $api_key;

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL session and get the response
$response = curl_exec($ch);

// Check if the request was successful
if ($response === false) {
    http_response_code(500); // Internal Server Error
    exit();
}

// Convert JSON response to an associative array
$recipes_data = json_decode($response, true);

// Check if the JSON decoding was successful
if ($recipes_data === null) {
    http_response_code(500); // Internal Server Error
    exit();
}

// Return the recipe data as a JSON response
header('Content-Type: application/json');
echo json_encode($recipes_data);
?>
