<?php
header('Content-Type: application/json');

function generateRandomEmail() {
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $randomString = '';
    for ($i = 0; $i < 10; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString . '@example.com';
}

try {
    // Get JSON data from the request
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['image'])) {
        throw new Exception('Invalid request data');
    }

    $base64Image = $data['image'];
    $email = generateRandomEmail();

    // API URL
    $url = "https://copycoder.ai/api/analyze-image-test2";

    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        "image" => $base64Image,
        "email" => $email
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36",
        "sec-ch-ua: \"Google Chrome\";v=\"131\", \"Chromium\";v=\"131\", \"Not_A Brand\";v=\"24\"",
        "sec-ch-ua-mobile: ?0",
        "sec-ch-ua-platform: \"Windows\"",
        "DNT: 1",
        "Accept: */*",
        "Origin: https://copycoder.ai",
        "Sec-Fetch-Site: same-origin",
        "Sec-Fetch-Mode: cors",
        "Sec-Fetch-Dest: empty",
        "Referer: https://copycoder.ai/",
        "Accept-Language: en-US,en;q=0.9",
        "Priority: u=1, i"
    ]);

    // Execute the request
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }

    // Close cURL session
    curl_close($ch);

    // Return the API response
    echo $response;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
