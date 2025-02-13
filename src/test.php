<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$access_token_openverse = "sSDjBXiwT2DmsflPlB7UJfyV13SKcn";
$filtered_images = [];
$hashtag = "bat";
$api_url_openverse = "https://api.openverse.org/v1/images?q=$hashtag";
$options = [
    "http" => [
        "header" => "Authorization: Bearer $access_token_openverse",
        "method" => "GET"
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($api_url_openverse, false, $context);
    $data = json_decode($response, true);
    if (isset($data['results'])) {
        foreach ($data['results'] as $media) {
                $filtered_images[] = [
                    "media_url" => $media['url'],
                    "source" => "OpenVerse"
                ];  
        }
    }
echo '<pre>';
print_r($filtered_images);
echo '</pre>';