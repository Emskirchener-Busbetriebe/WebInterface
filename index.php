<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'db.php';

if (isset($_POST['delete_warn'])) {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM warns WHERE ID = ?");
    $stmt->execute([$id]);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

$stmt = $pdo->query("SELECT * FROM warns ORDER BY date DESC, time DESC");
$warns = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }
        .navbar {
            height: 55px;
            width: 100%;
            background-color: #1b2028;
            color: white;
        }
        .sidepanel {
            position: absolute;
            top: 55px;
            left: 0;
            width: 300px;
            height: calc(100dvh - 55px);
            background-color: #1f252e;
            color: rgb(255, 255, 255);
            padding: 10px;
        }
        .content {
            margin-left: 320px;
            padding: 35px;
            height: calc(100dvh - 55px);
            background-color: #212831;
            overflow-y: auto;
        }
        h1 {
            color: white;
            margin: 0;
        }
        .warn-dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header-buttons {
            display: flex;
            gap: 10px;
        }
        .content-header-button {
            background-color: #293039;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
        }
        .warn-user-content {
            background-color: #2a3039;
            border-radius: 8px;
            padding: 20px;
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            position: relative;
        }
        .warn-user-panel {
            color: white;
            font-size: 1.1em;
            font-weight: bold;
            display: grid;
            grid-template-columns: 1fr 2fr 1fr 1fr 1fr;
            gap: 15px;
            flex-grow: 1;
        }
        .warn-subheading {
            color: #ffffff;
            font-size: 0.85em;
            font-weight: normal;
        }
        .warn-reason {
            max-width: 260px;
        }
        .warn-reason-content {
            max-width: 260px;
            max-height: 3.6em;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            transition: all 0.3s ease;
            word-wrap: break-word;
        }
        .warn-reason-content.expanded {
            max-height: none;
            -webkit-line-clamp: unset;
            overflow: visible;
        }
        .read-more-btn {
            color: #4d9eff;
            cursor: pointer;
            font-size: 0.8em;
            margin-top: 5px;
            display: inline-block;
        }
        .read-more-btn:hover {
            text-decoration: underline;
        }
        .warn-dashboard {
            margin-bottom: 100px;
        }
        .remove {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 50px;
            background-color: #ff323a;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .remove svg {
            width: 24px;
            height: 24px;
            fill: white;
        }
        @media (max-width: 1200px) {
            .warn-user-panel {
                grid-template-columns: 1fr;
            }
            .warn-reason {
                max-width: 100%;
            }
            .warn-reason-content {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <main>
        <div class="navbar">
            <span>Navbar Placholder</span>
        </div>
        <div class="sidepanel">
            <span>Sidepanel Placeholder</span>
        </div>
        <div class="content">
            <div class="warn-dashboard">
                <div class="warn-dashboard-header">
                    <h1>Warns</h1>
                    <div class="header-buttons">
                        <div class="content-header-button">
                            <span>Settings</span>
                        </div>
                        <div class="content-header-button">
                            <span>Users</span>
                        </div>
                    </div>
                </div>
                
                <?php foreach ($warns as $warn): ?>
                <div class="warn-user-content">
                    <div class="warn-user-panel">
                        <div class="warn-username">
                            <span class="warn-subheading">Username</span><br>
                            <span><?= htmlspecialchars($warn['discordUsername']) ?></span>
                        </div>
                        <div class="warn-reason">
                            <span class="warn-subheading">Reason</span><br>
                            <div class="warn-reason-content">
                                <?= nl2br(htmlspecialchars($warn['reason'])) ?>
                            </div>
                            <span class="read-more-btn">Read more</span>
                        </div>
                        <div class="warn-moderator">
                            <span class="warn-subheading">Moderator</span><br>
                            <span><?= htmlspecialchars($warn['moderatorUsername']) ?></span>
                        </div>
                        <div class="warn-date">
                            <span class="warn-subheading">Date/Time</span><br>
                            <span><?= date('d.m.Y H:i', strtotime($warn['date'].' '.$warn['time'])) ?> Uhr</span>
                        </div>
                        <div class="warn-count">
                            <span class="warn-subheading">Count</span><br>
                            <span><?= $warn['warncount'] ?></span>
                        </div>
                    </div>
                    <form method="POST" class="remove">
                        <input type="hidden" name="id" value="<?= $warn['ID'] ?>">
                        <button type="submit" name="delete_warn" style="background: none; border: none; cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M17 6V4c0-1.1-.9-2-2-2H9c-1.1 0-2 .9-2 2v2H2v2h2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8h2V6zM9 4h6v2H9zM6 20V8h12v12z"></path>
                                <path d="M9 10h2v8H9zM13 10h2v8h-2z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.read-more-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const content = this.previousElementSibling;
                    const isExpanded = content.classList.toggle('expanded');
                    
                    if (isExpanded) {
                        this.textContent = 'Read less';
                        content.style.webkitLineClamp = 'unset';
                    } else {
                        this.textContent = 'Read more';
                        content.style.webkitLineClamp = '2';
                    }
                });
            });

            document.querySelectorAll('form.remove').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Möchten Sie diesen Warn wirklich löschen?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>