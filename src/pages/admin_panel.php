<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

    if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 'USER') !== 'ADMIN') {
        header("Location: /pages/home.php");
        exit;
    }

    if (isset($_GET['resolve_report'])) {
        resolveReport((int) $_GET['resolve_report']);
        header("Location: /pages/admin_panel.php");
        exit;
    }

    if (isset($_GET['ban_reported'])) {
        banUser((int) $_GET['ban_reported']);
        header("Location: /pages/admin_panel.php");
        exit;
    }

    if (isset($_GET['ban_user'])) {
        banUser((int) $_GET['ban_user']);
        header("Location: /pages/admin_panel.php");
        exit;
    }

    if (isset($_GET['suspend_user'])) {
        $days = isset($_GET['days']) ? (int) $_GET['days'] : 7;
        suspendUser((int) $_GET['suspend_user'], $days);
        header("Location: /pages/admin_panel.php");
        exit;
    }

    if (isset($_GET['unban_user'])) {
        unbanUser((int) $_GET['unban_user']);
        header("Location: /pages/admin_panel.php");
        exit;
    }

    $pageTitle = "Roamance - Admin Panel";
    $pageCSS = "/assets/css/admin_panel.css";
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>

<?php
    $userGraphData = getUsersForGraph();
    $userDates = $userGraphData['userDates'];
    $userCounts = $userGraphData['userCounts'];

    $matchGraphData = getMatchesForGraph();
    $matchDates = $matchGraphData['matchDates'];
    $matchCounts = $matchGraphData['matchCounts'];
?>

<div class="container mt-4">
    <h1>Dashboard</h1>
    <h3 class="mb-4">Monitor the platforms journey</h3>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card" style="min-height: 200px;">
                <h5 class="card-title">Total Users: <?php echo getTotalUsers(); ?></h5>
                <p class="card-text fs-1"><canvas id="userChart" width="400" height="300"></canvas></p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card" style="min-height: 200px;">
                <h5 class="card-title">Total Matches: <?php echo getTotalMatches(); ?></h5>
                <p class="card-text fs-1"><canvas id="matchChart" width="400" height="300"></canvas></p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card" style="min-height: 200px;">
                <h5 class="card-title">Reports</h5>
                <div class="scrollableContainer">
                    <p><?php
                        $reports = getRecentReports();
                        foreach ($reports as $report):
                    ?>
                    <div class="reportEntry">
                        <?php echo "<div class='listReport'>" .
                            "Report ID: " . htmlspecialchars($report['id']) . "<br>" .
                            "Reporter ID: " . htmlspecialchars($report['reporter_id']) . "<br>" .
                            "Reported ID: " . htmlspecialchars($report['reported_user_id']) . "<br>" .
                            "Reported Email: " . htmlspecialchars($report['reported_email']) . "<br>" .
                            "Reason: " . htmlspecialchars($report['reason']) . "<br>" .
                            "Date: " . date("M d, Y", strtotime($report['created_at'])) .
                        "</div>"; ?>

                        <?php echo "<div class='reportActions'>" .
                            "<a href='/pages/admin_panel.php?resolve_report=" . htmlspecialchars($report['id']) . "' class='btn btn-success btn-sm mb-1'>Resolve</a><br>" .
                            "<button class='btn btn-warning btn-sm' onclick=\"openActionModal(" . (int)$report['reported_user_id'] . ", '" . htmlspecialchars(addslashes($report['reported_email']), ENT_QUOTES) . "')\">Actions</button>" .
                        "</div>"; ?>
                    </div>
                    <?php endforeach; ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card" style="min-height: 200px;">
                <div class="cardSignUps">
                    <h5 class="card-title">New Sign-Ups</h5>
                    <div class="scrollableContainer">
                        <p><?php
                            $newUsers = getNewestUsers();
                            foreach ($newUsers as $user):
                        ?>
                        <div class="newUserEntry">
                            <?php echo "<div class='listNewUser'>" . htmlspecialchars($user['email']) . "<br>" . "Joined: " . date("M d, Y", strtotime($user['created_at'])) . "<br>" . "ID: " . htmlspecialchars($user['id']) . "</div>"; ?>
                            <div class='userActions'>
                                <a href='/pages/profile_view.php?user_id=<?php echo htmlspecialchars($user['id']); ?>' class='btn btn-outline-primary btn-sm' title='View Profile'>
                                    <i class='bi bi-person-fill'></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
            <div class="card" style="height: 400px; display: flex; flex-direction: column;">
                <h5 class="card-title">User Management</h5>
                <div class="mb-2">
                    <input type="text" id="userSearch" class="form-control" placeholder="Search by email or ID...">
                </div>
                <div class="scrollableContainer" id="userManagementList" style="flex: 1; overflow-y: auto; max-height: unset;">
                    <?php foreach (getAllUsers() as $user): ?>
                    <div class="newUserEntry">
                        <div class="listNewUser">
                            <?php echo htmlspecialchars($user['email']); ?><br>
                            Joined: <?php echo date("M d, Y", strtotime($user['created_at'])); ?><br>
                            ID: <?php echo htmlspecialchars($user['id']); ?>
                        </div>
                        <div class="userActions">
                            <a href="/pages/profile_view.php?user_id=<?php echo $user['id']; ?>" class="btn btn-outline-primary btn-sm" title="View Profile"><i class="bi bi-person-fill"></i></a>
                            <button class="btn btn-warning btn-sm" title="Actions" onclick="openActionModal(<?php echo (int)$user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['email']), ENT_QUOTES); ?>')"><i class="bi bi-three-dots-vertical"></i></button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
            <div class="card" style="height: 400px; display: flex; flex-direction: column;">
                <h5 class="card-title">Banned Users</h5>
                <div class="scrollableContainer" style="flex: 1; overflow-y: auto; max-height: unset;">
                    <?php foreach (getBannedUsers() as $user): ?>
                    <div class="newUserEntry">
                        <div class="listNewUser">
                            <?php echo htmlspecialchars($user['email']); ?><br>
                            Joined: <?php echo date("M d, Y", strtotime($user['created_at'])); ?><br>
                            ID: <?php echo htmlspecialchars($user['id']); ?>
                        </div>
                        <div class="userActions">
                            <a href="/pages/profile_view.php?user_id=<?php echo $user['id']; ?>" class="btn btn-outline-primary btn-sm" title="View Profile"><i class="bi bi-person-fill"></i></a>
                            <a href="/pages/admin_panel.php?unban_user=<?php echo $user['id']; ?>" class="btn btn-success btn-sm" title="Unban User"><i class="bi bi-check-circle"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4 ms-4" style="min-height: 200px;">
        <h5 class="card-title">Recent Activity</h5>
        <div class="scrollableContainer" style="max-height: 300px;">
            <?php foreach (getRecentActivity() as $event): ?>
            <div class="activityEntry">
                <?php if ($event['type'] === 'signup'): ?>
                    <span class="badge bg-success">Sign-up</span>
                <?php elseif ($event['type'] === 'contact'): ?>
                    <span class="badge bg-primary">Contact</span>
                <?php else: ?>
                    <span class="badge bg-danger">Report</span>
                <?php endif; ?>
                <span class="activityEmail"><?php echo htmlspecialchars($event['email']); ?></span>
                <?php if ($event['extra']): ?>
                    <span class="activityExtra">— <?php echo htmlspecialchars($event['extra']); ?></span>
                <?php endif; ?>
                <?php if ($event['type'] === 'contact' && $event['message']): ?>
                    <?php
                        $full    = $event['message'];
                        $preview = mb_strlen($full) > 80 ? mb_substr($full, 0, 80) . '…' : $full;
                    ?>
                    <span class="activityExtra" title="<?php echo htmlspecialchars($full); ?>">
                        "<?php echo htmlspecialchars($preview); ?>"
                    </span>
                <?php endif; ?>
                <span class="activityTime"><?php echo date("M d, g:ia", strtotime($event['created_at'])); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- User Action Modal -->
<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionModalLabel">User Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Select an action for: <strong id="actionUserEmail"></strong></p>
                <div id="suspendOptions" style="display:none;" class="mt-2">
                    <label class="form-label">Suspension Duration:</label>
                    <select class="form-select" id="suspendDays">
                        <option value="1">1 Day</option>
                        <option value="3">3 Days</option>
                        <option value="7" selected>7 Days</option>
                        <option value="14">14 Days</option>
                        <option value="30">30 Days</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="suspendBtn">Suspend</button>
                <button type="button" class="btn btn-danger" id="banBtn">Ban</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const userLabels = <?php echo json_encode($userDates); ?>;
    const userCount = <?php echo json_encode($userCounts); ?>;

    const matchCount = <?php echo json_encode($matchCounts); ?>;
    const matchLabels = <?php echo json_encode($matchDates); ?>;

    const recentUsers = <?php echo json_encode(getNewestUsers()); ?>;
</script>

<script>
new Chart(document.getElementById('userChart'), {
    type: 'bar',
    data: {
        labels: userLabels,
        datasets: [{
            label: 'Users per Day',
            data: userCount,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Date'
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Users'
                }
            }
        }
    }
});

new Chart(document.getElementById('matchChart'), {
    type: 'bar',
    data: {
        labels: matchLabels,
        datasets: [{
            label: 'Matches per Day',
            data: matchCount, // Replace with actual match data
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Date'
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Matches'
                }
            }
        }
    }
});
</script>

<script>
document.getElementById('userSearch').addEventListener('input', function () {
    const query = this.value.toLowerCase();
    const entries = document.querySelectorAll('#userManagementList .newUserEntry');

    entries.forEach(entry => {
        const text = entry.querySelector('.listNewUser').textContent.toLowerCase();
        entry.style.display = text.includes(query) ? '' : 'none';
    });
});
</script>

<script>
let actionUserId = null;
let actionModal = null;

function openActionModal(userId, userEmail) {
    actionUserId = userId;
    document.getElementById('actionUserEmail').textContent = userEmail;
    document.getElementById('suspendOptions').style.display = 'none';
    document.getElementById('suspendBtn').textContent = 'Suspend';

    if (!actionModal) {
        actionModal = new bootstrap.Modal(document.getElementById('actionModal'));
    }
    actionModal.show();
}

document.getElementById('suspendBtn').addEventListener('click', function () {
    const suspendOptions = document.getElementById('suspendOptions');
    if (suspendOptions.style.display === 'none') {
        suspendOptions.style.display = 'block';
        this.textContent = 'Confirm Suspend';
    } else {
        const days = document.getElementById('suspendDays').value;
        window.location.href = `/pages/admin_panel.php?suspend_user=${actionUserId}&days=${days}`;
    }
});

document.getElementById('banBtn').addEventListener('click', function () {
    if (confirm(`Are you sure you want to permanently ban this user?`)) {
        window.location.href = `/pages/admin_panel.php?ban_user=${actionUserId}`;
    }
});
</script>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>
