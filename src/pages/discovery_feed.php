<?php
	$pageTitle = "Roamance - Discovery Feed";
	$pageCSS = "/assets/css/discovery_feed.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>

<div class="container py-2">

    <div class="row">
        <div class="col-lg-4 col-md-7 col-sm-10 text-center mb-4">
            <h2 class="mb-3">Discover New Connections</h2>
            <p class="text-muted">
                Explore profiles of fellow travelers and find your next adventure buddy!
            </p>
        </div>
        


        <div class="col-lg-6 col-md-9 col-sm-12">
            <div class="card shadow">
                <h3 class="card-title">Name Surname, 28</h3>
            </div>
            <!-- Profile Card -->
            <div class="card shadow">

                <div class="card-body text-center">

                    <!-- Pictures -->
                    <div class="mb-3">
                        <img src="https://via.placeholder.com/400x300"
                             class="img-fluid rounded"
                             alt="Profile Image">
                    </div>

                    <!-- Info -->
                    <p class="card-text text-muted">
                        Loves travelling 🌍 | Based in Dublin | Hiking & Photography
                    </p>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <button class="btn btn-outline-danger px-4">
                            Dislike
                        </button>

                        <button class="btn btn-success px-4">
                            Like
                        </button>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>