<?php
$userId = $_SESSION["user_id"];
$user = getNextPassport($pdo, $userId, $selectedCountry);

if (!$user) {
    $currentProfileId = null;
    $profileImage = "";
    $firstName = "";
    $lastName = "";
    $country = "";
    $age = "";
    $bio = "";
    $galleryImages = [];
	$nextTrip = null;
	$stamps = [];
} else {
	$currentProfileId = $user['user_id'];
	$profileImage = !empty($user['profile_picture']) ? $user['profile_picture'] : '/assets/images/default_profile.png';
	$firstName = $user['first_name'];
	$lastName = $user['last_name'];
	$country = $user['country'];
	$age = $user['age'];
	$bio = $user['bio'];
	$stamps = $user['stamps'] ?? [];
	$nextTrip = $user['nextTrip'];
	$galleryImages = $user['galleryImages'];
}
?>

<link rel="stylesheet" href="/assets/css/passport.css">

<div class="passport-wrapper mx-auto">
	<div class="passport position-relative mx-auto">
			<div id="approvedStamp" class="stamp_overlay approved">
				<img src="/assets/images/approved_stamp.svg" alt="Approved Stamp">
			</div>
			<div id="rejectedStamp" class="stamp_overlay rejected">
				<img src="/assets/images/denied_stamp.svg" alt="Rejected Stamp">
			</div>
			<div class= "row g-0 g-lg-3 align-items-stretch passport-content">
				<div class="col-12 col-lg-5">
					<div class="passport-left">
						<p class="gallery-title">MY TRAVELS</p>
						<div class="title-line"></div>
						<div class="carousel">
							<button class="arrow left" onclick="moveSlide(-1)" <?= count($galleryImages) > 1 ? '' : 'style="display:none;"' ?>>&#10094;</button>
							<div class="carousel-window">
								<div class="carousel-track" id="carouselTrack">
									<?php if (!empty($galleryImages)): ?>
										<?php foreach($galleryImages as $img): ?>
											<img src="<?= $img ?>" alt="Travel Photo">
										<?php endforeach; ?>
									<?php else: ?>
										<div class="no-gallery-placeholder">
											<span class="no-gallery-icon">📷</span>
											<p>No travel photos yet</p>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<button class="arrow right" onclick="moveSlide(1)" <?= count($galleryImages) > 1 ? '' : 'style="display:none;"' ?>>&#10095;</button>
						</div>
					</div>
				</div>

				<div class="col-12 col-lg-7">
					<div class="passport-right">
						<div class="info">
							<div class="profile-header">
								<img src="<?= $profileImage ?>" alt="<?= $firstName . ' ' . $lastName ?>" class="profile-img">
								<div class="user-info">
									<div class="tpass-header">
										<img id="tpassIcon" src="/assets/images/TPassIcon.png" alt="TPassIcon">
										<p id="tpass">Travel Passport</p>
									</div>
									<hr>
									<div class ="details">
										<div class="details-left">
											<p class="header">SURNAME</p>
											<p class="name-field"><?= $lastName ?></p>
												<p class="header">FORENAME</p>
											<p class="name-field"><?= $firstName ?></p>
										</div>
										<div class="details-right">
											<p class="header">NATIONALITY</p>
											<p class="other-field"><?= $country ?></p>
											<p class="header">AGE</p>
											<p class="other-field"><?= $age ?> years</p>
										</div>
									</div>
								</div>
							</div>
							<div class="body">
								<div class="BioDest">
									<div class="bio">
										<p class="heading">TRAVELLER BIO</p>
										<p class="body-text"><?= $bio ?></p>
									</div>
									<div class="dest">
											<p class="heading">NEXT PLANNED TRIP</p>
											<?php if ($nextTrip): ?>
												<p class="body-text">
													<?= htmlspecialchars($nextTrip['location']) ?> • <?= date('d M Y', strtotime($nextTrip['start_date'])) ?>
												</p>
											<?php else: ?>
												<p class="body-text">No planned trips</p>
											<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
						<div class="separator">
							<span>VISA STAMPS</span>
						</div>

						<div id="stampsArea">
											<?php if (empty($stamps)): ?>
								<div class="no-stamps">
									<span class="no-stamps-icon">✈️</span>
									<p class="no-stamps-text">This user has posted no trips yet</p>
								</div>
							<?php else: ?>
								<div class="stamps-container">
									<div class="stamps">
										<?php foreach($stamps as $stamp): ?>
											<div class="stamp <?= isset($stamp['desc']) && $stamp['desc'] !== '' && $stamp['desc'] !== '0' ? 'has-desc' : '' ?>">
												<span class="icon"><?= $stamp['icon'] ?></span>
												<span class="country"><?= $stamp['country'] ?></span>
												<span class="date"><?= $stamp['date'] ?></span>
												<?php if(isset($stamp['desc']) && $stamp['desc'] !== '' && $stamp['desc'] !== '0'): ?>
													<span class="desc"><?= $stamp['desc'] ?></span>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div><!-- /.passport -->
	<!-- Interests slide-out panel — lives inside passport-wrapper so it animates with it -->
	<div class="interests-panel" id="interestsPanel">
		<button class="interests-tab" id="interestsTab" aria-label="Toggle interests">
			<span>Interests</span>
		</button>
		<div class="interests-page-wrapper">
			<div class="interests-page">
				<div class="interests-page-header">
					<div class="tpass-header" style="border-bottom: #1e3a5f 2px solid; margin-bottom: 1rem; padding-bottom: 0.5rem;">
						<img src="/assets/images/TPassIcon.png" alt="TPassIcon" style="width:23px;height:23px;">
						<p style="font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:450;color:#1e3a5f;margin:0;">Interests</p>
					</div>
				</div>
				<div id="interestsTags">
					<?php $interests = $user['interests'] ?? []; ?>
					<?php if (empty($interests)): ?>
						<p class="interests-empty">This user has no interests listed.</p>
					<?php else: ?>
						<div class="interests-tags">
							<?php foreach ($interests as $interest): ?>
								<span class="interest-tag"><?= htmlspecialchars($interest['name']) ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="noProfileOverlay" id="noProfileOverlay" style="<?= $currentProfileId !== null ? 'display:none;' : 'display:flex;' ?>">
		<p>No more profiles available</p>
		<p>Try adjusting your preferences or check back later!</p>
	</div>

	<div class="cover top-cover">
		<img src="/assets/images/favicon_light.ico" alt="emb">
	</div>
</div>


<script src="/includes/js/passport.js"></script>

<script>
let currentProfileId = <?= json_encode($currentProfileId) ?>;
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const stamps = document.querySelectorAll(".stamps .stamp");

    stamps.forEach(stamp => {
        const angle = (Math.random() * 10) - 5;
        stamp.style.transform = `rotate(${angle}deg)`;
    });
});
</script>
