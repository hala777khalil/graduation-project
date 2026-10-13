<?php
require_once '../config/db_connect.php';
require_once '../includes/functions.php';
require_once 'deepseek_config.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

function generateResponse($question) {
    $aiResponse = getRecyclingAdvice($question);
    
    if ($aiResponse !== false) {
        return $aiResponse;
    }
    
    $lowerQuestion = strtolower($question);
    
    $responses = [
        'materials' => [
            'keywords' => ['material', 'recycle', 'what can', 'types'],
            'answer' => 'You can recycle plastic, paper, glass, metal, electronics, cardboard, and organic waste. Each material has different point values based on environmental impact.'
        ],
        'points' => [
            'keywords' => ['point', 'earn', 'how much', 'score'],
            'answer' => 'Points are earned per kilogram: Plastic (10pts), Paper (8pts), Glass (12pts), Metal (15pts), Electronics (20pts), Cardboard (7pts). Log your activities to start earning!'
        ],
        'centers' => [
            'keywords' => ['center', 'location', 'where', 'find', 'near'],
            'answer' => 'Use our interactive map to find recycling centers near you. Visit the "Centers Map" page from the main menu to see locations, working hours, and accepted materials.'
        ],
        'account' => [
            'keywords' => ['account', 'profile', 'register', 'login'],
            'answer' => 'Create a free account to start tracking your recycling activities, earning points, and competing on the leaderboard. Click "Register" in the top menu to get started!'
        ],
        'rewards' => [
            'keywords' => ['reward', 'redeem', 'prize', 'badge'],
            'answer' => 'You can redeem your points for certificates, badges, and exclusive rewards. Visit the "My Points" section in your dashboard to see available rewards.'
        ]
    ];
    
    foreach ($responses as $category => $data) {
        foreach ($data['keywords'] as $keyword) {
            if (strpos($lowerQuestion, $keyword) !== false) {
                return $data['answer'];
            }
        }
    }
    
    return 'Thank you for your question! For detailed information, please check our FAQ page or contact our support team at info@ecorecycle.com';
}

if ($action === 'chat') {
    $question = $_POST['question'] ?? '';
    
    if (empty($question)) {
        echo json_encode(['error' => 'Question is required']);
        exit;
    }
    
    $answer = generateResponse($question);
    
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO ai_interactions (user_id, question, answer) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $question, $answer);
        $stmt->execute();
    }
    
    echo json_encode([
        'success' => true,
        'answer' => $answer,
        'timestamp' => date('H:i')
    ]);
    
} elseif ($action === 'recommendations') {
    
    if (!isLoggedIn()) {
        echo json_encode(['error' => 'Authentication required']);
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    
    $recommendations = [];
    
    echo json_encode([
        'success' => true,
        'recommendations' => $recommendations
    ]);
    
} else {
    echo json_encode(['error' => 'Invalid action']);
}
?>

