CREATE DATABASE IF NOT EXISTS recycling_system;
USE recycling_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('user', 'admin') DEFAULT 'user',
    points INT DEFAULT 0,
    rank INT DEFAULT 0,
    profile_image VARCHAR(255) DEFAULT 'default-avatar.png',
    verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE recycling_centers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    phone VARCHAR(20),
    email VARCHAR(100),
    working_hours VARCHAR(100),
    accepted_materials TEXT,
    rating DECIMAL(3,2) DEFAULT 0,
    total_reviews INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    material_type VARCHAR(50) NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit VARCHAR(20) NOT NULL,
    recycling_center_id INT,
    description TEXT,
    points_earned INT DEFAULT 0,
    status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    activity_date DATE NOT NULL,
    verified_by INT,
    verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (recycling_center_id) REFERENCES recycling_centers(id) ON DELETE SET NULL
);

CREATE TABLE rewards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reward_type VARCHAR(50) NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    points_required INT NOT NULL,
    status ENUM('claimed', 'redeemed', 'expired') DEFAULT 'claimed',
    claimed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    redeemed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) NOT NULL,
    read_status TINYINT(1) DEFAULT 0,
    link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE leaderboard_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    rank INT NOT NULL,
    points INT NOT NULL,
    period VARCHAR(20) NOT NULL,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE ai_interactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    category VARCHAR(50),
    helpful TINYINT(1),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO system_settings (setting_key, setting_value, description) VALUES
('points_per_kg_plastic', '10', 'Points awarded per kilogram of plastic'),
('points_per_kg_paper', '8', 'Points awarded per kilogram of paper'),
('points_per_kg_glass', '12', 'Points awarded per kilogram of glass'),
('points_per_kg_metal', '15', 'Points awarded per kilogram of metal'),
('points_per_kg_electronics', '20', 'Points awarded per kilogram of electronics'),
('points_per_kg_cardboard', '7', 'Points awarded per kilogram of cardboard'),
('points_per_kg_organic', '5', 'Points awarded per kilogram of organic waste'),
('site_name', 'EcoRecycle Hub', 'Website name'),
('site_email', 'info@ecorecycle.com', 'Contact email'),
('site_phone', '+966 50 123 4567', 'Contact phone number');

INSERT INTO users (username, email, password, full_name, phone, address, role, points, rank, verified, created_at) VALUES
('admin', 'admin@ecorecycle.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', '+966501234567', 'Riyadh, Saudi Arabia', 'admin', 0, 0, 1, NOW()),
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', '+966501234568', 'Riyadh, Saudi Arabia', 'user', 2450, 1, 1, DATE_SUB(NOW(), INTERVAL 30 DAY)),
('sarah_smith', 'sarah@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sarah Smith', '+966501234569', 'Jeddah, Saudi Arabia', 'user', 1890, 2, 1, DATE_SUB(NOW(), INTERVAL 25 DAY)),
('ahmed_ali', 'ahmed@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ahmed Ali', '+966501234570', 'Dammam, Saudi Arabia', 'user', 1650, 3, 1, DATE_SUB(NOW(), INTERVAL 20 DAY)),
('fatima_mohammed', 'fatima@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Fatima Mohammed', '+966501234571', 'Riyadh, Saudi Arabia', 'user', 1320, 4, 1, DATE_SUB(NOW(), INTERVAL 15 DAY)),
('mohamed_khan', 'mohamed@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mohamed Khan', '+966501234572', 'Jeddah, Saudi Arabia', 'user', 980, 5, 1, DATE_SUB(NOW(), INTERVAL 10 DAY));

INSERT INTO recycling_centers (name, address, latitude, longitude, phone, email, working_hours, accepted_materials, rating, total_reviews, status, created_at) VALUES
('Riyadh Green Center', 'King Fahd Road, Al Olaya District, Riyadh', 24.7136, 46.6753, '+966112345601', 'riyadh@greencenter.sa', 'Sun-Thu: 8:00 AM - 8:00 PM', 'plastic,paper,glass,metal,electronics', 4.5, 150, 'active', NOW()),
('Jeddah Eco Hub', 'Prince Mohammed Bin Abdulaziz St, Jeddah', 21.5433, 39.1728, '+966122345602', 'jeddah@ecohub.sa', 'Sun-Thu: 9:00 AM - 7:00 PM', 'plastic,paper,glass,cardboard', 4.2, 98, 'active', NOW()),
('Dammam Recycling Station', 'King Saud Road, Dammam', 26.4207, 50.0888, '+966132345603', 'dammam@recycle.sa', 'Sun-Thu: 7:00 AM - 6:00 PM', 'plastic,metal,electronics,glass', 4.8, 220, 'active', NOW()),
('Al Khobar Green Point', 'Dhahran Street, Al Khobar', 26.2885, 50.2080, '+966132345604', 'khobar@greenpoint.sa', 'Sun-Thu: 8:00 AM - 8:00 PM', 'plastic,paper,glass,metal,cardboard', 4.3, 87, 'active', NOW()),
('Mecca Environmental Center', 'Ibrahim Al Khalil Street, Mecca', 21.4225, 39.8262, '+966122345605', 'mecca@enviro.sa', 'Sun-Thu: 9:00 AM - 6:00 PM', 'plastic,paper,cardboard', 4.0, 65, 'active', NOW());

INSERT INTO activities (user_id, material_type, quantity, unit, recycling_center_id, description, points_earned, status, activity_date, verified_by, verified_at, created_at) VALUES
(2, 'plastic', 15.5, 'kg', 1, 'Collected plastic bottles from office', 155, 'verified', DATE_SUB(NOW(), INTERVAL 2 DAY), 1, DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 'paper', 22.0, 'kg', 1, 'Old newspapers and magazines', 176, 'verified', DATE_SUB(NOW(), INTERVAL 5 DAY), 1, DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(2, 'glass', 8.5, 'kg', 1, 'Glass bottles and jars', 102, 'verified', DATE_SUB(NOW(), INTERVAL 8 DAY), 1, DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),
(3, 'metal', 12.0, 'kg', 2, 'Aluminum cans collection', 180, 'verified', DATE_SUB(NOW(), INTERVAL 3 DAY), 1, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),
(3, 'plastic', 18.0, 'kg', 2, 'Plastic containers and packaging', 180, 'verified', DATE_SUB(NOW(), INTERVAL 6 DAY), 1, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY)),
(4, 'electronics', 5.5, 'kg', 3, 'Old computer parts and cables', 110, 'verified', DATE_SUB(NOW(), INTERVAL 4 DAY), 1, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY)),
(4, 'cardboard', 25.0, 'kg', 3, 'Cardboard boxes from moving', 175, 'verified', DATE_SUB(NOW(), INTERVAL 7 DAY), 1, DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY)),
(5, 'paper', 30.0, 'kg', 1, 'Office paper waste', 240, 'verified', DATE_SUB(NOW(), INTERVAL 5 DAY), 1, DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(5, 'plastic', 10.0, 'kg', 1, 'Household plastic waste', 100, 'verified', DATE_SUB(NOW(), INTERVAL 9 DAY), 1, DATE_SUB(NOW(), INTERVAL 8 DAY), DATE_SUB(NOW(), INTERVAL 9 DAY)),
(6, 'glass', 14.0, 'kg', 4, 'Glass bottles collection', 168, 'verified', DATE_SUB(NOW(), INTERVAL 6 DAY), 1, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY)),
(6, 'metal', 7.5, 'kg', 4, 'Metal cans and containers', 113, 'verified', DATE_SUB(NOW(), INTERVAL 10 DAY), 1, DATE_SUB(NOW(), INTERVAL 9 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
(2, 'cardboard', 20.0, 'kg', 1, 'Delivery boxes', 140, 'pending', NOW(), NULL, NULL, NOW()),
(3, 'plastic', 12.5, 'kg', 2, 'Plastic bottles and bags', 125, 'pending', NOW(), NULL, NULL, NOW());

INSERT INTO notifications (user_id, title, message, type, read_status, link, created_at) VALUES
(2, 'Activity Verified', 'Your plastic recycling activity has been verified. You earned 155 points!', 'success', 0, '/user/my_activities.php', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(2, 'New Challenge', 'Join this week recycling challenge and earn 2x points!', 'info', 0, '/pages/leaderboard.php', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(3, 'Rank Updated', 'Congratulations! You are now rank #2 on the leaderboard!', 'success', 1, '/pages/leaderboard.php', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(4, 'Activity Verified', 'Your electronics recycling activity has been verified. You earned 110 points!', 'success', 1, '/user/my_activities.php', DATE_SUB(NOW(), INTERVAL 3 DAY)),
(5, 'Welcome', 'Welcome to EcoRecycle Hub! Start logging your activities to earn points.', 'info', 1, '/user/add_activity.php', DATE_SUB(NOW(), INTERVAL 15 DAY));

INSERT INTO ai_interactions (user_id, question, answer, category, helpful, created_at) VALUES
(2, 'What materials can I recycle?', 'You can recycle plastic, paper, glass, metal, electronics, cardboard, and organic waste. Each material has different point values based on environmental impact.', 'materials', 1, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(3, 'How do I earn more points?', 'Points are earned per kilogram: Plastic (10pts), Paper (8pts), Glass (12pts), Metal (15pts), Electronics (20pts), Cardboard (7pts). Log your activities to start earning!', 'points', 1, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(4, 'Where is the nearest recycling center?', 'Use our interactive map to find recycling centers near you. Visit the Centers Map page from the main menu to see locations, working hours, and accepted materials.', 'centers', 1, DATE_SUB(NOW(), INTERVAL 7 DAY));

INSERT INTO leaderboard_history (user_id, rank, points, period, recorded_at) VALUES
(2, 1, 2450, 'weekly', NOW()),
(3, 2, 1890, 'weekly', NOW()),
(4, 3, 1650, 'weekly', NOW()),
(5, 4, 1320, 'weekly', NOW()),
(6, 5, 980, 'weekly', NOW());
