<?php

//Will Optimize it
$api_id = 'f54f0db5';
$api_key = '94527f02437544cd169ac13168907cd8';

// Prepare the API endpoint URL with query parameters
$search_query = isset($_GET['q']) ? urlencode($_GET['q']) : 'chicken'; // Default search query is 'chicken'
$apiEndpoint = 'https://api.edamam.com/search?q=' . $search_query . '&app_id=' . $api_id . '&app_key=' . $api_key;

//Initialize Memcache
$cache = new MemCached();
$cache -> addServer('localhost',11211); // Memecache port

// Function to fetch API data with caching
function fetchDataFromApi($apiEndpoint, $requestParameters) {
    global $cache;

    // Create a unique cache key based on the API endpoint and request parameters
    $cacheKey = md5($apiEndpoint . serialize($requestParameters));
    echo "Cache Key: " . $cacheKey . "<br>";
    // Check if data exists in the cache
    $cachedData = $cache->get($cacheKey);

    if (!$cachedData) {
        // Data not found in the cache, make the API request
        // Assuming you have an API client function named makeApiRequest
        $apiResponse = makeApiRequest($apiEndpoint, $requestParameters);

        // Store the API response in the cache with an expiration time (e.g., 1 hour)
        // (Memcached Protocol)
        $cache->set($cacheKey, $apiResponse, 3600); // Expiration time of 1 hour
    } else {
        // Data found in the cache, use it directly
        $apiResponse = $cachedData;
    }

    return $apiResponse;
}

?>
