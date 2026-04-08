<?php
	$pageTitle = "Roamance - Admin Panel";
	$pageCSS = "/assets/css/admin_panel.css";
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';
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
                            "<a href='/admin_panel.php?resolve_report=" . htmlspecialchars($report['id']) . "' class='btn btn-success btn-sm mb-1'>Resolve</a><br>" .
                            "<a href='/admin_panel.php?ban_reported=" . htmlspecialchars($report['reported_user_id']) . "' class='btn btn-danger btn-sm'>Ban User</a>" .
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
                                <a href='/admin_panel.php?view_profile=<?php echo htmlspecialchars($user['id']); ?>' class='btn btn-outline-primary btn-sm' title='View Profile'>
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
                            <a href="/admin_panel.php?view_profile=<?php echo $user['id']; ?>" class="btn btn-outline-primary btn-sm mb-1" title="View Profile"><i class="bi bi-person-fill"></i></a>
                            <a href="/admin_panel.php?ban_user=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" title="Ban User"><i class="bi bi-slash-circle"></i></a>
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
                            <a href="/admin_panel.php?view_profile=<?php echo $user['id']; ?>" class="btn btn-outline-primary btn-sm mb-1" title="View Profile"><i class="bi bi-person-fill"></i></a>
                            <a href="/admin_panel.php?unban_user=<?php echo $user['id']; ?>" class="btn btn-success btn-sm" title="Unban User"><i class="bi bi-check-circle"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4 ms-4" style="min-height: 200px;">
        <h5 class="card-title">Recent Activity</h5>
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