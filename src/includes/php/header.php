<header class="mb-4">
    <a href="/index.php">
        <img class="logo" src="/assets/images/Roamance v7.png" alt="Roamance Logo">
    </a>

    <nav class="site-nav">
	<ul class="nav">
		<?php if(isset($_SESSION['user_id'])): ?>
            		<li class="nav-item">
                		<a class="nav-link" href="/index.php">Home</a>
           		</li>
		<?php endif; ?>

            <li class="nav-item">
                <a class="nav-link" href="/pages/about.php">About</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/pages/contact.php">Contact</a>
            </li>

            <?php if (isset($_SESSION['user_id'])): ?>
                
                <li class="nav-item profile-wrapper">
                    <a href="#" class="profile-circle" id="profileToggle">
                        <img src="/assets/images/default_profile.jpg"
                             alt="Profile"
                             class="profile-pic">
                    </a>

                    <ul class="dropdown-menu" id="profileMenu">
                        <li><a href="/pages/profile.php">Edit Profile</a></li>
                        <li><a href="/pages/settings.php">Settings</a></li>
                        <li><a href="/actions/logout.php">Logout</a></li>
                    </ul>
                </li>
		
		<?php else: ?>
		
		<li class="nav-buttons">
			<a href="/pages/login.php" class="btn btn-customLogin">Log In</a>
                    	<a href="/pages/create_account.php" class="btn btn-customSignUp">Sign Up</a>
                </li>

            	<?php endif; ?>
	</ul>
    </nav>
</header>

<!-- Dropdown Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggle = document.getElementById("profileToggle");
    const menu = document.getElementById("profileMenu");

    if (!toggle || !menu) return;

    toggle.addEventListener("click", function(e) {
        e.preventDefault();
        menu.style.display =
            menu.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", function(e) {
        if (!toggle.contains(e.target) && !menu.contains(e.target)) {
            menu.style.display = "none";
        }
    });
});
</script>
