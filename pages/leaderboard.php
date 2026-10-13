<?php
$page_title = 'Leaderboard';
$additional_css = ['leaderboard.css'];
require_once '../includes/header.php';

$period = isset($_GET['period']) ? sanitizeInput($_GET['period']) : 'all';

$top_three = $conn->query("SELECT id, username, full_name, points, rank FROM users WHERE rank > 0 ORDER BY rank ASC LIMIT 3");

$leaderboard = $conn->query("SELECT id, username, full_name, points, rank FROM users WHERE rank > 3 ORDER BY rank ASC LIMIT 50");

$current_user_id = $_SESSION['user_id'] ?? 0;
?>

<div class="leaderboard-container">
    <div class="leaderboard-hero">
        <h1>🏆 Global Leaderboard 🏆</h1>
        <p>Compete with eco-warriors worldwide and climb to the top!</p>
    </div>
    
    <div class="leaderboard-filters">
        <button class="filter-btn active">All Time</button>
        <button class="filter-btn">This Month</button>
        <button class="filter-btn">This Week</button>
        <button class="filter-btn">Today</button>
    </div>
    
    <?php if ($top_three->num_rows >= 3): ?>
        <div class="top-three">
            <?php 
            $winners = [];
            while ($user = $top_three->fetch_assoc()) {
                $winners[] = $user;
            }
            
            foreach ([1, 0, 2] as $index): 
                $user = $winners[$index];
                $position_class = ['first', 'second', 'third'][$index];
            ?>
                <div class="podium-card <?php echo $position_class; ?>">
                    <?php if ($index === 0): ?>
                        <div class="crown-icon">👑</div>
                    <?php endif; ?>
                    
                    <div class="podium-rank rank-<?php echo $user['rank']; ?>">
                        <?php echo $user['rank']; ?>
                    </div>
                    
                    <div class="podium-avatar" style="background: var(--gradient-<?php echo ['gold', 'blue', 'purple'][$index]; ?>);">
                        <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                    </div>
                    
                    <div class="podium-name"><?php echo htmlspecialchars($user['full_name']); ?></div>
                    <div class="podium-username">@<?php echo htmlspecialchars($user['username']); ?></div>
                    <div class="podium-points"><?php echo number_format($user['points']); ?> pts</div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="leaderboard-table-section">
        <h2>Top Recyclers</h2>
        
        <?php if ($leaderboard->num_rows > 0): ?>
            <?php while($user = $leaderboard->fetch_assoc()): ?>
                <div class="leaderboard-row <?php echo $user['id'] == $current_user_id ? 'current-user' : ''; ?>">
                    <div class="rank-number"><?php echo $user['rank']; ?></div>
                    
                    <div class="user-avatar-lb">
                        <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                    </div>
                    
                    <div class="user-details">
                        <div class="user-name-lb">
                            <?php echo htmlspecialchars($user['full_name']); ?>
                            <?php if ($user['id'] == $current_user_id): ?>
                                <span class="badge badge-gold">You</span>
                            <?php endif; ?>
                        </div>
                        <div class="user-meta">
                            @<?php echo htmlspecialchars($user['username']); ?>
                        </div>
                    </div>
                    
                    <div class="user-points-lb"><?php echo number_format($user['points']); ?></div>
                    <div class="user-badge"><?php echo getRankBadge($user['rank']); ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; color: var(--text-light); padding: 40px 0;">
                No users found
            </p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

