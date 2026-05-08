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
    <title>Panel | Dashboard</title>
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

        /* SIDEBAR */
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
            border-color: 255,255,255,0.13;
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

        /* TOP BAR */
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

        .topbar .user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* MAIN CONTENT */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .content {
            padding: 25px;
            overflow-y: auto;
        }

        /* DASHBOARD CARDS */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    padding: 20px;
    border-radius: 10px;
    transition: 0.25s ease;

        .card:hover {
                transform: translateY(-4px) scale(1.02);
                box-shadow: 0 0 20px rgba(255,255,255,0.18);
        }

        .card h3 {
            margin: 0 0 10px 0;
        }

        .logout-btn {
            color: #ff6b6b;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }

        .logout-btn:hover {
            color: #ff8787;
        }
        
        .sidebar {
    background: rgba(255,255,255,0.04);
    border-right: 1px solid rgba(255,255,255,0.12);
    backdrop-filter: blur(18px);
        }
        
.topbar {
    box-shadow: 0 2px 10px rgba(0,0,0,0.4);
}

.content {
    animation: fadePage .35s ease;
}

@keyframes fadePage {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.stat-card {
    transition: 0.25s ease;
}

.stat-card:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 0 20px rgba(255,255,255,0.18);
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

.active {
    background: rgba(255,255,255,0.25) !important;
    box-shadow: 0 0 10px rgba(255,255,255,0.15);
    border-left: 3px solid #4cc3ff;
    padding-left: 12px;
}

.stats {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
}


    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2><i class="fa-solid fa-user-secret"></i> Impulse Panel</h2>

        <div class="nav-item"><i class="fa-solid fa-laptop-code"></i> Dashboard</div>
        <div class="nav-item" onclick="window.location='analytics.php'">
        <i class="fa-solid fa-chart-line"></i> Analytics
        </div>

        <div class="nav-item"><i class="fa-solid fa-users"></i> Users</div>
        <div class="nav-item"><i class="fa-solid fa-gear"></i> Settings</div>

        <div style="margin-top:auto;">
            <a href="/logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </div>

    <!-- MAIN AREA -->
    <div class="main">

        <!-- TOP BAR -->
        <div class="topbar">
            <h2>Dashboard</h2>

            <div class="user">
                <i class="fa-solid fa-circle-user" style="font-size:24px;"></i>
                <span><?php echo $_SESSION['user']; ?></span>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <div class="cards">
                <div class="card">
                    <h3>Total Clients</h3>
                    <p style="font-size:28px; font-weight:bold;">N/A</p>
                </div>

                <div class="card">
                    <h3>Active Sessions</h3>
                    <p style="font-size:28px; font-weight:bold;">3</p/>
                </div>

                <div class="card">
                    <h3>API Status</h3>
                    <p style="font-size:20px; color:#4cff4c;">Online</p>
                </div>

                <div class="card">
                    <h3>Last Login</h3>
                    <p id="local-time">Loading...</p>
                </div>
            </div>

        </div>

    </div>

</body>
<script>
function updateLocalTime() {
    const now = new Date();
    const options = {
        day: "2-digit",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        hour12: true
    };

    document.getElementById("local-time").innerText =
        now.toLocaleString(undefined, options);
}

updateLocalTime();
setInterval(updateLocalTime, 1000);
</script>
</html>
