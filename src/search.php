<?php

require __DIR__ . '/../src/bootstrap.php';

$access_token_insta = "EACERUwWjFC0BOyndWoZAZAtLcUw6NYTFNMZAecMYQwJUzBmrVcDCRLqRfwoj1tGZAzQZBehfhZCbvwWd6iku8cXisrjsVF6KXbl2n6ZC2ljTM1R1S2jKn46gwC7qxQhyUAUGBMZBBZCjZAZBv3R6R88TpBLILPRrVLRiorLbNJDGk5YRAJosDo0MHPcnrD2";
$access_token_unsplash = "dCyE89I_yNPCcEeyRLzpPDjEMCPloS0APYDjvbOEeKU";
$access_token_pexel = "sG8Vx1BxLDaTWAKRn9nEnB9etqpwHvOGlVEYjyKemfi9DBE8vg6YwilA";
$access_token_openverse = "sSDjBXiwT2DmsflPlB7UJfyV13SKcn";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hashtag'])) {
    $hashtag = trim($_POST['hashtag']);
    
    if (empty($hashtag)) {
        echo json_encode(["error" => "Hashtag is required"]);
        exit;
    }

    $filtered_images = [];

    // Instagram API
    $api_url_insta = "https://graph.facebook.com/v17.0/17841472061617315/media?fields=id,media_type,media_url,caption,timestamp&access_token=$access_token_insta";
    $response = file_get_contents($api_url_insta);
    $data = json_decode($response, true);

    if (isset($data['data'])) {
        foreach ($data['data'] as $media) {
            if (isset($media['caption']) && stripos($media['caption'], "#$hashtag") !== false) {
                $filtered_images[] = [
                    "media_url" => $media['media_url'],
                    "source" => "Instagram"
                ];
            }
        }
    }

    // Unsplash API
    $api_url_unsplash = "https://api.unsplash.com/search/photos?page=1&query=$hashtag&client_id=$access_token_unsplash";
    $response = file_get_contents($api_url_unsplash);
    $data = json_decode($response, true);
    if (isset($data['results'])) {
        foreach ($data['results'] as $media) {
                $filtered_images[] = [
                    "media_url" => $media['urls']['small'],
                    "source" => "Unsplash"
                ];  
        }
    }
    
    // pexel API
    $api_url_pexel = "https://api.pexels.com/v1/search?query=$hashtag";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url_pexel);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Authorization: $access_token_pexel"
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    if (isset($data['photos'])) {
        foreach ($data['photos'] as $media) {
                $filtered_images[] = [
                    "media_url" => $media['src']['medium'],
                    "source" => "pexels"
                ];  
        }
    }

    // OpenVerse API
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


    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = db()->prepare("INSERT INTO search_history (user_id, hashtag) VALUES (?, ?)");
        $stmt->execute([$user_id, $hashtag]);
    }

    echo json_encode($filtered_images);
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
