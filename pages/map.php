<?php
$page_title = 'Recycling Centers Map';
$additional_css = ['map.css'];
$additional_js = [];
require_once '../includes/header.php';

$centers = $conn->query("SELECT * FROM recycling_centers WHERE status = 'active' ORDER BY name");
?>

<div class="map-container">
    <div class="map-header">
        <h1>Find Recycling Centers Near You</h1>
        <p>Locate the nearest recycling center and start making a difference today</p>
    </div>
    
    <div class="map-layout">
        <aside class="centers-sidebar">
            <h2>Recycling Centers</h2>
            
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search by name or location..." id="searchCenters">
            </div>
            
            <div id="centersList">
                <?php if ($centers->num_rows > 0): ?>
                    <?php while($center = $centers->fetch_assoc()): ?>
                        <div class="center-card" data-lat="<?php echo $center['latitude']; ?>" data-lng="<?php echo $center['longitude']; ?>">
                            <div class="center-header">
                                <div class="center-icon">
                                    <i class="fas fa-recycle"></i>
                                </div>
                                <div class="center-name"><?php echo htmlspecialchars($center['name']); ?></div>
                            </div>
                            
                            <div class="center-info">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($center['address']); ?></span>
                            </div>
                            
                            <?php if ($center['phone']): ?>
                                <div class="center-info">
                                    <i class="fas fa-phone"></i>
                                    <span><?php echo htmlspecialchars($center['phone']); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($center['working_hours']): ?>
                                <div class="center-info">
                                    <i class="fas fa-clock"></i>
                                    <span><?php echo htmlspecialchars($center['working_hours']); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($center['accepted_materials']): ?>
                                <div class="center-materials">
                                    <?php 
                                    $materials = explode(',', $center['accepted_materials']);
                                    foreach ($materials as $material): 
                                    ?>
                                        <span class="material-tag"><?php echo trim($material); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; color: var(--text-light); padding: 40px 0;">
                        No recycling centers found
                    </p>
                <?php endif; ?>
            </div>
        </aside>
        
        <div class="map-view">
            <div id="map" style="width: 100%; height: 100%; border-radius: 12px;"></div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const map = L.map('map').setView([24.7136, 46.6753], 6);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

const goldIcon = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-gold.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});

const markers = [];

document.querySelectorAll('.center-card').forEach(card => {
    const lat = parseFloat(card.dataset.lat);
    const lng = parseFloat(card.dataset.lng);
    const name = card.querySelector('.center-name').textContent;
    const address = card.querySelector('.center-info').textContent;
    
    if (lat && lng) {
        const marker = L.marker([lat, lng], {icon: goldIcon}).addTo(map);
        marker.bindPopup(`<b>${name}</b><br>${address}`);
        
        markers.push({card: card, marker: marker, lat: lat, lng: lng});
        
        card.addEventListener('click', function() {
            document.querySelectorAll('.center-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            map.setView([lat, lng], 13);
            marker.openPopup();
        });
        
        marker.on('click', function() {
            document.querySelectorAll('.center-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    }
});

document.getElementById('searchCenters').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const centerCards = document.querySelectorAll('.center-card');
    
    centerCards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchTerm) ? 'block' : 'none';
    });
});
</script>

</body>
</html>

