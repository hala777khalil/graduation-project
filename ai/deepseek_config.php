<?php

define('DEEPSEEK_API_KEY', 'PUT_YOUR_DEEPSEEK_API_KEY_HERE');
define('DEEPSEEK_API_URL', 'https://api.deepseek.com/v1/chat/completions');

function callDeepSeekAPI($messages) {
    $data = [
        'model' => 'deepseek-chat',
        'messages' => $messages,
        'temperature' => 0.7,
        'max_tokens' => 500
    ];
    
    $ch = curl_init(DEEPSEEK_API_URL);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . DEEPSEEK_API_KEY
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    if ($httpCode === 200) {
        $result = json_decode($response, true);
        if (isset($result['choices'][0]['message']['content'])) {
            return $result['choices'][0]['message']['content'];
        }
    }
    
    return false;
}

function getRecyclingAdvice($question) {
    $messages = [
        [
            'role' => 'system',
            'content' => 'You are an expert environmental assistant specializing in recycling and sustainability. Provide helpful, accurate, and concise answers about recycling, waste management, and environmental conservation. Keep responses under 150 words.'
        ],
        [
            'role' => 'user',
            'content' => $question
        ]
    ];
    
    return callDeepSeekAPI($messages);
}

function getPersonalizedRecommendation($userStats) {
    $statsText = "User recycling statistics: ";
    foreach ($userStats as $material => $data) {
        $statsText .= "$material: {$data['quantity']} kg, ";
    }
    
    $messages = [
        [
            'role' => 'system',
            'content' => 'You are a personalized recycling coach. Based on user statistics, provide specific recommendations to improve their recycling habits and environmental impact. Be encouraging and specific.'
        ],
        [
            'role' => 'user',
            'content' => $statsText . " What should I focus on to improve my recycling impact?"
        ]
    ];
    
    return callDeepSeekAPI($messages);
}

?>

