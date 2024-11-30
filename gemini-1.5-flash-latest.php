<?php
header('Content-Type: application/json');

// Load environment variables from .env file
function loadEnv($path) {
    if(!file_exists($path)) {
        throw new Exception('.env file not found');
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos(trim($line), '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            if (!array_key_exists($key, $_ENV)) {
                putenv(sprintf('%s=%s', $key, $value));
                $_ENV[$key] = $value;
            }
        }
    }
}

// Load .env file
try {
    loadEnv(__DIR__ . '/.env');
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Environment configuration error']);
    exit;
}

// Get API key from environment variable
$apiKey = getenv('GEMINI_API_KEY');
if (!$apiKey) {
    http_response_code(500);
    echo json_encode(['error' => 'API key not configured']);
    exit;
}

$prompt = "
<summary_title>
UI/UX Image Analysis
</summary_title>

<image_analysis>

1. Overall Design:

Layout: Describe the overall layout (e.g., grid-based, fluid, fixed width).
Color Palette: Identify the primary, secondary, and accent colors.
Typography: Analyze the font families, sizes, and weights used.
Imagery: Assess the use of images, icons, and other visual elements.
2. Header:

Navigation Bar: Describe the navigation elements (e.g., logo, menu items, search bar).
Header Elements: Identify any additional elements like social media icons, notifications, or user profiles.
3. Main Content Area:

Hero Section: Analyze the main visual and textual content.
Content Sections: Describe the organization of content into sections (e.g., features, benefits, testimonials).
Call to Action: Identify any prominent calls to action (e.g., buttons, forms).
4. Footer:

Footer Elements: Describe the elements in the footer (e.g., copyright information, contact details, social media links).
5. Interactive Elements:

Buttons: Analyze the style, size, and placement of buttons.
Forms: Describe the form elements (e.g., input fields, text areas, select boxes).
Modals and Popups: Identify any modal windows or popups.
6. User Experience:

User Flow: Describe the user's journey through the interface.
Information Hierarchy: Assess the organization of information and visual hierarchy.
Accessibility: Evaluate the design's adherence to accessibility guidelines (e.g., color contrast, keyboard navigation).
7. Potential HTML/CSS Tags (General):

Header: header, nav, ul, li, a, input, button
Main Content: section, article, h1, h2, p, img, div, span, form, input, textarea, select, button
Footer: footer, p, a";

// Check if file was uploaded
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'No image uploaded or upload error']);
    exit;
}

// Get uploaded file information
$tmpName = $_FILES['image']['tmp_name'];
$mimeType = mime_content_type($tmpName);

// Verify it's an image
if (!str_starts_with($mimeType, 'image/')) {
    http_response_code(400);
    echo json_encode(['error' => 'Uploaded file is not an image']);
    exit;
}

// Convert image to base64
$imageData = base64_encode(file_get_contents($tmpName));

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=" . $apiKey;

$data = [
    "contents" => [
        [
            "parts" => [
                [
                    "text" => $prompt
                ],
                [
                    "inline_data" => [
                        "mime_type" => $mimeType,
                        "data" => $imageData
                    ]
                ]
            ]
        ]
    ]
];

$options = [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($data)
];

$curl = curl_init();
curl_setopt_array($curl, $options);
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($httpCode !== 200) {
    http_response_code($httpCode);
    echo json_encode(['error' => 'API request failed', 'details' => $response]);
    exit;
}

// Decode the response
$responseData = json_decode($response, true);

// Extract only the text content and maintain the original format
$generatedText = $responseData['candidates'][0]['content']['parts'][0]['text'];

// Return response in the expected format
echo json_encode([
    "candidates" => [
        [
            "content" => [
                "parts" => [
                    [
                        "text" => $generatedText
                    ]
                ],
                "role" => "model"
            ],
            "finishReason" => "STOP",
            "avgLogprobs" => -0.43862825685793216
        ]
    ],
    "usageMetadata" => [
        "promptTokenCount" => 668,
        "candidatesTokenCount" => 888,
        "totalTokenCount" => 1556
    ],
    "modelVersion" => "gemini-1.5-flash-latest"
]);
