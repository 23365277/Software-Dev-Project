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
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card" style="min-height: 200px;">
                <h5 class="card-title">New Sign-Ups</h5>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
            <div class="card" style="min-height: 400px;">
                <h5 class="card-title">User Management</h5>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
            <div class="card" style="min-height: 400px;">
                <h5 class="card-title">Banned Users</h5>
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
    type: 'line',
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