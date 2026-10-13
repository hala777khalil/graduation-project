<?php
$page_title = 'AI Recommendations';
$additional_css = ['chatbot.css'];
require_once '../includes/header.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];

$user_stats = $conn->query("SELECT 
    material_type,
    SUM(quantity) as total_quantity,
    SUM(points_earned) as total_points,
    COUNT(*) as activity_count
    FROM activities 
    WHERE user_id = $user_id AND status = 'verified'
    GROUP BY material_type")->fetch_all(MYSQLI_ASSOC);

$recommendations = [];

if (count($user_stats) > 0) {
    $materials_recycled = array_column($user_stats, 'material_type');
    $all_materials = ['plastic', 'paper', 'glass', 'metal', 'electronics', 'cardboard'];
    $missing_materials = array_diff($all_materials, $materials_recycled);
    
    if (count($missing_materials) > 0) {
        $material = reset($missing_materials);
        $recommendations[] = [
            'icon' => 'recycle',
            'color' => 'green',
            'title' => 'Try Recycling ' . ucfirst($material),
            'description' => "You haven't recycled any $material yet. Diversify your recycling habits and earn more points!",
            'action' => 'Add Activity',
            'link' => '../user/add_activity.php'
        ];
    }
}

$recommendations[] = [
    'icon' => 'users',
    'color' => 'blue',
    'title' => 'Invite Friends',
    'description' => 'Share EcoRecycle Hub with friends and family. Together we can make a bigger impact!',
    'action' => 'Share Now',
    'link' => '#'
];

$recommendations[] = [
    'icon' => 'trophy',
    'color' => 'gold',
    'title' => 'Weekly Challenge',
    'description' => 'Join this week\'s recycling challenge and compete for exclusive rewards and bonus points.',
    'action' => 'Join Challenge',
    'link' => '../pages/leaderboard.php'
];

$recommendations[] = [
    'icon' => 'map-marked-alt',
    'color' => 'purple',
    'title' => 'Explore New Centers',
    'description' => 'Discover recycling centers near you. Find the most convenient location for your next drop-off.',
    'action' => 'View Map',
    'link' => '../pages/map.php'
];
?>

<div class="chatbot-container" style="max-width: 1200px;">
    <div class="chatbot-header">
        <div class="chatbot-icon-large">
            <i class="fas fa-lightbulb"></i>
        </div>
        <h1>AI-Powered Recommendations</h1>
        <p>Personalized suggestions to maximize your environmental impact</p>
    </div>
    
    <div style="background: var(--white); border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px var(--shadow); margin-top: 30px;">
        <h2 style="font-size: 28px; font-weight: 700; color: var(--text-dark); margin-bottom: 30px;">
            <i class="fas fa-chart-bar"></i> Your Recycling Profile
        </h2>
        
        <?php if (count($user_stats) > 0): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
                <?php foreach ($user_stats as $stat): ?>
                    <div style="padding: 20px; background: var(--light-bg); border-radius: 12px; text-align: center;">
                        <div style="font-size: 32px; margin-bottom: 10px;">
                            <?php echo getMaterialIcon($stat['material_type']); ?>
                        </div>
                        <div style="font-size: 18px; font-weight: 700; color: var(--text-dark); margin-bottom: 5px;">
                            <?php echo ucfirst($stat['material_type']); ?>
                        </div>
                        <div style="color: var(--text-light); font-size: 14px;">
                            <?php echo $stat['total_quantity']; ?> kg recycled
                        </div>
                        <div style="color: var(--primary-gold); font-weight: 700; margin-top: 5px;">
                            <?php echo $stat['total_points']; ?> points
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; background: var(--light-bg); border-radius: 12px; margin-bottom: 40px;">
                <i class="fas fa-chart-line" style="font-size: 64px; color: var(--primary-blue); margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 10px;">No Recycling Data Yet</h3>
                <p style="color: var(--text-light); margin-bottom: 20px;">Start logging your activities to get personalized recommendations!</p>
                <a href="../user/add_activity.php" class="btn btn-primary">Log Your First Activity</a>
            </div>
        <?php endif; ?>
        
        <h3 style="font-size: 24px; font-weight: 700; color: var(--text-dark); margin-bottom: 25px;">
            Recommended Actions
        </h3>
        
        <div class="recommendations-grid">
            <?php foreach ($recommendations as $rec): ?>
                <div class="recommendation-card">
                    <div class="recommendation-icon icon-<?php echo $rec['color']; ?>">
                        <i class="fas fa-<?php echo $rec['icon']; ?>"></i>
                    </div>
                    <div class="recommendation-title"><?php echo $rec['title']; ?></div>
                    <p class="recommendation-text"><?php echo $rec['description']; ?></p>
                    <a href="<?php echo $rec['link']; ?>" class="btn btn-<?php echo $rec['color'] == 'gold' ? 'gold' : 'primary'; ?>">
                        <?php echo $rec['action']; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

