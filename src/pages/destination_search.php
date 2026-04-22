<?php
	$pageTitle = "Roamance - Atlas";
	$pageCSS = ["/assets/css/passport.css", "/assets/css/destination_search.css"];
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

    $visitedCountries = [];
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT d.location FROM user_destinations ud JOIN destinations d ON ud.destination_id = d.id WHERE ud.user_id = :user_id");
        $stmt->execute([':user_id' => $_SESSION['user_id']]);
        $visitedCountries = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    $tripsCountries = [];
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT location FROM trips WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $_SESSION['user_id']]);
        $tripsCountries = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    $homeCountry = isset($_SESSION['user_id']) ? getHomeCountry($pdo, $_SESSION['user_id']) : null;


    $rawStamps = isset($_SESSION['user_id']) ? getUserStamps($pdo, $_SESSION['user_id']) : [];
    $stamps = array_map(function($s) {
        return [
            'icon'    => getCountryFlag($s['location']),
            'country' => $s['location'],
            'date'    => $s['visited_date'],
            'desc'    => $s['description']
        ];
    }, $rawStamps);

    $visitedCount = count($visitedCountries);
    $plannedCount = count($tripsCountries);
    $stampCount = count($stamps);
?>

<div class="container py-4 atlas-page">

    <section class="atlas-top-layout">
        <aside class="atlas-journal-panel">
            <p class="journal-mini-title">Roamance Travel Record</p>
            <h1 class="atlas-page-title">My Atlas</h1>

            <p class="atlas-page-copy">
                Keep a record of places you have visited, places you are heading to, and memories you have collected along the way.
            </p>

            <div class="atlas-metric-list">
                <div class="atlas-metric-row">
                    <span class="metric-value"><?= $visitedCount ?></span>
                    <span class="metric-text">Countries Visited</span>
                </div>
                <div class="atlas-metric-row">
                    <span class="metric-value"><?= $plannedCount ?></span>
                    <span class="metric-text">Trips Planned</span>
                </div>
                <div class="atlas-metric-row">
                    <span class="metric-value"><?= $stampCount ?></span>
                    <span class="metric-text">Passport Stamps</span>
                </div>
            </div>

            <div class="atlas-journal-divider"></div>

            <div class="atlas-country-block">
                <h2>Current Route</h2>

                <h3>Visited</h3>
                <div class="country-line-list">
                    <?php if (!empty($visitedCountries)): ?>
                        <?php foreach ($visitedCountries as $country): ?>
                            <span class="country-chip visited-chip"><?= htmlspecialchars($country) ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="empty-text">No visited countries recorded yet.</p>
                    <?php endif; ?>
                </div>

                <h3>Going To</h3>
                <div class="country-line-list">
                    <?php if (!empty($tripsCountries)): ?>
                        <?php foreach ($tripsCountries as $country): ?>
                            <span class="country-chip planned-chip"><?= htmlspecialchars($country) ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="empty-text">No trips planned yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <a href="/pages/post_a_trip.php" class="atlas-action-btn">Plan Next Destination</a>
        </aside>

        <section class="atlas-map-board">
            <div class="atlas-board-top">
                <div>
                    <p class="board-kicker">Explorer Map</p>
                    <h2>Destination Atlas</h2>
                </div>

                <div class="atlas-legend">
                    <span><i class="legend-dot visited"></i> Visited</span>
                    <span><i class="legend-dot planned"></i> Going</span>
                    <span><i class="legend-dot home"></i> Home Country</span>
                    <span><i class="legend-dot neutral"></i> Unmarked</span>
                </div>
            </div>

            <div class="atlas-search-wrap">
                <input type="text" id="searchInput" class="form-control atlas-search" placeholder="Search country or destination...">
            </div>

            <div class="map-shell">
                <div id="map"></div>
            </div>

            <p class="atlas-map-note">Tap any country to explore travellers heading there.</p>
        </section>
    </section>

    <section class="atlas-stamp-strip">
        <div class="stamp-strip-header">
            <div>
                <p class="board-kicker">Collected Entries</p>
                <h2>Passport Stamps</h2>
            </div>
        </div>

        <div class="stamp-scroll-row">
            <?php foreach($stamps as $stamp): ?>
            <div class="stamp <?= isset($stamp['desc']) && $stamp['desc'] !== '' && $stamp['desc'] !== '0' ? 'has-desc' : '' ?>">
                <span class="icon"><?= $stamp['icon'] ?></span>
                <span class="country"><?= htmlspecialchars($stamp['country']) ?></span>
                <span class="date"><?= htmlspecialchars($stamp['date']) ?></span>
                <?php if(isset($stamp['desc']) && $stamp['desc'] !== '' && $stamp['desc'] !== '0'): ?>
                    <span class="desc"><?= htmlspecialchars($stamp['desc']) ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

</div>

<script>
let map;
let marker;
const locationNormalizeMap = {
    'England': 'United Kingdom',
    'Scotland': 'United Kingdom',
    'Wales': 'United Kingdom',
    'Northern Ireland': 'United Kingdom',
    'Great Britain': 'United Kingdom',
    'Britain': 'United Kingdom',
    'United States of America': 'United States',
    'USA': 'United States',
    'US': 'United States',
    'America': 'United States',
    'Czechia': 'Czech Republic'
};
function normalizeLocations(arr) {
    return arr.map(c => locationNormalizeMap[c] ?? c);
}
const visitedCountries = normalizeLocations(<?php echo json_encode($visitedCountries); ?>);
const tripsCountries = normalizeLocations(<?php echo json_encode($tripsCountries); ?>);

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 25, lng: 0 },
        zoom: 3,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: false,
        styles: [
            {
                featureType: "all",
                elementType: "labels.icon",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "poi",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "road",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "transit",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "water",
                elementType: "geometry",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "water",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "landscape",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "landscape.natural",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "poi.park",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "administrative.province",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "administrative.locality",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "administrative.neighborhood",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "administrative.land_parcel",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "administrative.country",
                elementType: "labels.text",
                stylers: [{ visibility: "on" }]
            },
            {
                featureType: "administrative.country",
                elementType: "labels.text.fill",
                stylers: [{ color: "#18314f" }]
            }
        ]
    });

    map.data.setStyle((feature) => {
        const raw = feature.getProperty('ADMIN') ?? feature.getProperty('name') ?? feature.getProperty('NAME');
        const country = locationNormalizeMap[raw] ?? raw;
        const visited = visitedCountries.includes(country);
        const planned = tripsCountries.includes(country);
        const rawHome = '<?php echo htmlspecialchars($homeCountry ?? ''); ?>';
        const home = (locationNormalizeMap[rawHome] ?? rawHome) === country;

        return {
            fillColor: home ? "#31506e" : visited ? "#3ace3a" : planned ? "#f0a315" : "#8d9aaa",
            fillOpacity: visited || planned || home ? 0.72 : 0.22,
            strokeColor: "black",
            strokeWeight: 1,
            clickable: true
        };
    });

    map.data.addListener('click', (event) => {
        const country =
            event.feature.getProperty('ADMIN') ||
            event.feature.getProperty('name') ||
            event.feature.getProperty('NAME');

        if (!country) return;

        window.location.href = "/pages/discovery_feed.php?trip_country=" + encodeURIComponent(country);
    });

    map.data.loadGeoJson('/assets/data/countries.geojson');

    const input = document.getElementById('searchInput');
    const searchBox = new google.maps.places.SearchBox(input);

    map.addListener('bounds_changed', () => {
        searchBox.setBounds(map.getBounds());
    });

    searchBox.addListener('places_changed', () => {
        const places = searchBox.getPlaces();
        if (places.length === 0) return;

        if (marker) marker.setMap(null);

        const place = places[0];
        if (!place.geometry || !place.geometry.location) return;

        marker = new google.maps.Marker({
            map: map,
            position: place.geometry.location
        });

        map.setCenter(place.geometry.location);
        map.setZoom(12);
    });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2QU_U5Ck0fQvEFTE2RGDSEQAm1ITlcZU&libraries=places&callback=initMap" async defer></script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>
