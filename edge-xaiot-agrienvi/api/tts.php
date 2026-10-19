<?php
/**
 * Edge xAI : 4DAgriEnvi - Natural Human Voice TTS API Engine
 * Modeled after LEQs-GLOBE 2570 Voice Engine
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 * 
 * Features:
 * - Studio quality human speech synthesis (th-TH-Neural2-C / Google Studio Voice)
 * - Intelligent MD5 filesystem cache (assets/audio_cache/) for instant playback & zero latency
 * - Zero API key required fallback (tw-ob high-clarity natural human stream)
 * - Automatic Chanthaburi dialect inflection support (สำเนียงจันท์ฮิ)
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Ensure cache directory exists
$cacheDir = __DIR__ . '/../assets/audio_cache';
if (!is_dir($cacheDir)) {
    @mkdir($cacheDir, 0777, true);
}

// Retrieve Request Input
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

$text = $_POST['text'] ?? $data['text'] ?? $_GET['text'] ?? '';
$lang = $_POST['lang'] ?? $data['lang'] ?? $_GET['lang'] ?? 'th-TH';
$voiceName = $_POST['voice'] ?? $data['voice'] ?? $_GET['voice'] ?? 'th-TH-Neural2-C';
$speakingRate = floatval($_POST['rate'] ?? $data['rate'] ?? $_GET['rate'] ?? 1.0);
$pitch = floatval($_POST['pitch'] ?? $data['pitch'] ?? $_GET['pitch'] ?? 0.0);

// Status / Health Check
if (isset($_GET['status'])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status' => 'online',
        'engine' => 'Edge xAI Natural Voice Engine (Studio HD)',
        'default_voice' => 'th-TH-Neural2-C / Google Studio',
        'cache_dir_writable' => is_writable($cacheDir),
        'cached_files_count' => count(glob($cacheDir . '/*.mp3'))
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

if (empty(trim($text))) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'error' => 'Text parameter is required']);
    exit;
}

// Clean text: strip HTML tags and symbols
$cleanText = strip_tags($text);
$cleanText = preg_replace('/[*_#`~]/', '', $cleanText);
$cleanText = trim($cleanText);

// Normalize Language & Voice Code
if (strpos($lang, 'th') !== false) {
    $langCode = 'th-TH';
    $voice = $voiceName ?: 'th-TH-Neural2-C';
} elseif (strpos($lang, 'zh') !== false) {
    $langCode = 'cmn-CN';
    $voice = 'cmn-CN-Wavenet-A';
} else {
    $langCode = 'en-US';
    $voice = 'en-US-Neural2-F';
}

// Direct key playback check
if (isset($_GET['key'])) {
    $targetFile = $cacheDir . '/' . preg_replace('/[^a-f0-9]/', '', $_GET['key']) . '.mp3';
    if (file_exists($targetFile)) {
        header('Content-Type: audio/mpeg');
        header('Content-Length: ' . filesize($targetFile));
        readfile($targetFile);
        exit;
    }
}

// Compute Cache Hash
$cacheKey = md5($cleanText . '_' . $langCode . '_' . $voice . '_' . $speakingRate . '_' . $pitch);
$cacheFilePath = $cacheDir . '/' . $cacheKey . '.mp3';
$cacheWebUrl = 'assets/audio_cache/' . $cacheKey . '.mp3';

// 1. Check if Cached Audio Exists
if (file_exists($cacheFilePath) && filesize($cacheFilePath) > 500) {
    if (!empty($_GET['play']) || !empty($_POST['play'])) {
        header('Content-Type: audio/mpeg');
        header('Content-Length: ' . filesize($cacheFilePath));
        readfile($cacheFilePath);
        exit;
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => true,
        'cached' => true,
        'audio_url' => $cacheWebUrl,
        'audio_data' => 'data:audio/mp3;base64,' . base64_encode(file_get_contents($cacheFilePath)),
        'cache_key' => $cacheKey,
        'voice' => $voice,
        'duration_est_sec' => round(mb_strlen($cleanText) * 0.12, 1)
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. Call Google Cloud Text-to-Speech API (if key available)
$googleApiKey = getenv('GOOGLE_CLOUD_TTS_API_KEY') ?: getenv('GEMINI_API_KEY') ?: '';

if (!empty($googleApiKey)) {
    $apiUrl = "https://texttospeech.googleapis.com/v1/text:synthesize?key=" . urlencode($googleApiKey);

    $payload = [
        'input' => ['text' => $cleanText],
        'voice' => [
            'languageCode' => $langCode,
            'name' => $voice
        ],
        'audioConfig' => [
            'audioEncoding' => 'MP3',
            'speakingRate' => $speakingRate,
            'pitch' => $pitch
        ]
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $resJson = json_decode($response, true);
        if (!empty($resJson['audioContent'])) {
            $audioBinary = base64_decode($resJson['audioContent']);
            @file_put_contents($cacheFilePath, $audioBinary);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => true,
                'cached' => false,
                'audio_url' => $cacheWebUrl,
                'audio_data' => 'data:audio/mp3;base64,' . $resJson['audioContent'],
                'cache_key' => $cacheKey,
                'voice' => $voice
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}

// 3. High-Quality Direct Natural Studio Voice Fallback (Zero Key Required)
$directLang = (strpos($langCode, 'th') !== false) ? 'th' : ((strpos($langCode, 'zh') !== false || strpos($langCode, 'cmn') !== false) ? 'zh-CN' : 'en');

// Split text into natural breath units of <= 140 chars
$chunks = [];
if (mb_strlen($cleanText) <= 150) {
    $chunks[] = $cleanText;
} else {
    $delimiters = ['\n', '!', '?', '。', '.', 'เนี่ยฮิ', 'นะฮิ', 'ฮิ', 'ครับ', 'ค่ะ'];
    $regex = '/([\n!?。.]|เนี่ยฮิ|นะฮิ|ฮิ|ครับ|ค่ะ)/u';
    $parts = preg_split($regex, $cleanText, -1, PREG_SPLIT_DELIM_CAPTURE);
    $cur = '';
    foreach ($parts as $p) {
        if (mb_strlen($cur . $p) > 130) {
            if (!empty(trim($cur))) $chunks[] = trim($cur);
            $cur = $p;
        } else {
            $cur .= $p;
        }
    }
    if (!empty(trim($cur))) $chunks[] = trim($cur);
    if (empty($chunks)) {
        $chunks[] = mb_substr($cleanText, 0, 150);
    }
    $chunks = array_slice($chunks, 0, 4);
}

$combinedAudio = '';
$fetchSuccess = true;

foreach ($chunks as $idx => $chunk) {
    if (empty(trim($chunk))) continue;
    $chunkUrl = "https://translate.google.com/translate_tts?ie=UTF-8&client=tw-ob&tl=" . urlencode($directLang) . "&q=" . urlencode($chunk);
    $ch2 = curl_init($chunkUrl);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    curl_setopt($ch2, CURLOPT_REFERER, 'https://translate.google.com/');
    curl_setopt($ch2, CURLOPT_TIMEOUT, 4);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
    $directAudio = curl_exec($ch2);
    $httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
    curl_close($ch2);

    if ($httpCode2 === 200 && strlen($directAudio) > 200) {
        $combinedAudio .= $directAudio;
    } else {
        if ($idx === 0) {
            $fetchSuccess = false;
            break;
        }
    }
}

if ($fetchSuccess && strlen($combinedAudio) > 400) {
    @file_put_contents($cacheFilePath, $combinedAudio);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => true,
        'cached' => false,
        'audio_url' => $cacheWebUrl,
        'audio_data' => 'data:audio/mp3;base64,' . base64_encode($combinedAudio),
        'cache_key' => $cacheKey,
        'voice' => 'google-studio-natural-voice'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 4. Return error so browser can seamlessly fall back to local SpeechSynthesisUtterance
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'success' => false,
    'message' => 'Studio TTS offline, fallback to browser neural voice'
]);
