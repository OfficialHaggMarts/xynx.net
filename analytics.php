<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: /login");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Impulse | Analytics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            background: #0a0f1a;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background: rgba(255,255,255,0.05);
            border-right: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            margin: 0 0 20px 0;
            font-size: 22px;
            text-align: center;
        }

        .nav-item {
            padding: 12px 15px;
            margin: 6px 0;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.15);
        }

        .active {
            background: rgba(255,255,255,0.2);
        }

        .topbar {
            height: 60px;
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
        }

        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .content {
            padding: 25px;
            overflow-y: auto;
        }

        #map {
            width: 100%;
            height: 450px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            flex: 1;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .feed-item {
            background: rgba(255,255,255,0.05);
            padding: 12px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 10px;
        }

        .ip-link {
            color: #4cc3ff;
            cursor: pointer;
        }
        .ip-link:hover {
            text-decoration: underline;
        }
        
        .leaflet-control-zoom a {
    background: rgba(255,255,255,0.1) !important;
    color: white !important;
    border: none !important;
}

.leaflet-control-zoom a:hover {
    background: rgba(255,255,255,0.2) !important;
}


        /* FIX: Modal above map */
        #historyModal {
            z-index: 999999;
        }
        #map {
    box-shadow: 0 0 25px rgba(255,0,0,0.25);
    border: 1px solid rgba(255,255,255,0.1);
}
.fade-in {
    animation: fadeIn .4s ease forwards;
    opacity: 0;
}
.card:hover, .stat-card:hover {
    box-shadow: 0 0 15px rgba(255,255,255,0.15);
    transform: translateY(-3px);
}
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.15);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.25);
}

#historyModal > div {
    backdrop-filter: blur(12px);
    background: rgba(20,20,20,0.6) !important;
}
#historyModal {
    animation: fadeModal .25s ease;
}
.active {
    background: rgba(255,255,255,0.25) !important;
    box-shadow: 0 0 10px rgba(255,255,255,0.15);
}
.topbar {
    box-shadow: 0 2px 10px rgba(0,0,0,0.4);
}


@keyframes fadeModal {
    from { opacity: 0; }
    to { opacity: 1; }
}


@keyframes fadeIn {
    to { opacity: 1; }
}


    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2><i class="fa-solid fa-user-secret"></i> Impulse Panel</h2>

        <div class="nav-item" onclick="window.location='dashboard.php'">
            <i class="fa-solid fa-laptop-code"></i> Dashboard
        </div>

        <div class="nav-item active" onclick="window.location='analytics.php'">
            <i class="fa-solid fa-chart-line"></i> Analytics
        </div>

        <div class="nav-item" onclick="window.location='users.php'">
            <i class="fa-solid fa-users"></i> Users
        </div>

        <div class="nav-item" onclick="window.location='settings.php'">
            <i class="fa-solid fa-gear"></i> Settings
        </div>

        <div style="margin-top:auto;">
            <a href="/logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="topbar">
            <h2>Analytics</h2>

            <div class="user">
                <i class="fa-solid fa-circle-user" style="font-size:24px;"></i>
                <span><?php echo $_SESSION['user']; ?></span>
            </div>
        </div>

        <div class="content">

            <div class="stats">
                <div class="stat-card" id="totalHits">Loading...</div>
                <div class="stat-card" id="uniqueIPs">Loading...</div>
                <div class="stat-card" id="countries">Loading...</div>
            </div>
            <div class="stats fade-in">
            <div id="map" class="fade-in">
            <div id="feed" class="fade-in">


            <div id="map"></div>

            <h3>Recent Activity</h3>
            <div id="feed">Loading...</div>

        </div>

        <!-- FIXED MODAL -->
        <div id="historyModal" style="
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.7);
            display: none;
            justify-content: center;
            align-items: center;
        ">
            <div style="
                background: #111;
                padding: 20px;
                width: 500px;
                max-height: 80vh;
                overflow-y: auto;
                border-radius: 10px;
                border: 1px solid #333;
            ">
                <h2 id="historyTitle">IP History</h2>
                <div id="historyContent">Loading...</div>
                <button onclick="closeHistory()" style="
                    margin-top: 15px;
                    padding: 10px 20px;
                    background: #ff4444;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                    color: white;
                ">Close</button>
            </div>
        </div>

<script>
function loadStats() {
    fetch("https://xynx.net/api/analytics/get-stats.php")
        .then(r => r.json())
        .then(data => {
            document.getElementById("totalHits").innerHTML = "<h2>" + data.total + "</h2>Total Hits";
            document.getElementById("uniqueIPs").innerHTML = "<h2>" + data.unique_ips + "</h2>Unique IPs";
            document.getElementById("countries").innerHTML = "<h2>" + data.countries + "</h2>Countries";
        });
}

var map = L.map('map').setView([20, 0], 2);
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; CartoDB'
}).addTo(map);


function loadMap() {
    fetch("https://xynx.net/api/analytics/get-locations.php")
        .then(r => r.json())
        .then(locations => {
            locations.forEach(loc => {
L.circleMarker([loc.lat, loc.lng], {
    radius: 3,
    color: "#ff3b3b",
    fillColor: "#ff3b3b",
    fillOpacity: 0.9,
    weight: 2
}).addTo(map);

            });
        });
}

function loadFeed() {
    fetch("https://xynx.net/api/analytics/get-latest.php")
        .then(r => r.json())
        .then(rows => {
            let feed = document.getElementById("feed");
            feed.innerHTML = "";
            rows.forEach(r => {
                feed.innerHTML += `
                    <div class="feed-item">
                        <b class="ip-link" onclick="showHistory('${r.ip}')">${r.ip}</b><br>
                        ${r.city}, ${r.country}<br>
                        <span style="color:#888">${r.timestamp}</span>
                    </div>
                `;
            });
        });
}

loadStats();
loadMap();
loadFeed();

setInterval(() => {
    loadStats();
    loadFeed();
}, 5000);

function showHistory(ip) {
    document.getElementById("historyModal").style.display = "flex";
    document.getElementById("historyTitle").innerText = "IP History (" + ip + ")";
    document.getElementById("historyContent").innerHTML = "Loading...";

    fetch("https://xynx.net/api/analytics/get-history.php?ip=" + ip)
        .then(r => r.json())
        .then(rows => {
            let html = "";
            rows.forEach(r => {
                html += `
                    <div style="padding:10px; border-bottom:1px solid #333;">
                        <b>Endpoint:</b> ${r.endpoint}<br>
                        <b>Location:</b> ${r.city}, ${r.country}<br>
                        <b>Time:</b> ${r.timestamp}
                    </div>
                `;
            });
            document.getElementById("historyContent").innerHTML = html;
        });
}

function closeHistory() {
    document.getElementById("historyModal").style.display = "none";
}
map.zoomControl.setPosition('bottomright');
</script>

</body>
</html>
