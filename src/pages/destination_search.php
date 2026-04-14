<?php
	$pageTitle = "Roamance - Atlas";
	$pageCSS = ["/assets/css/passport.css", "/assets/css/destination_search.css"];
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

    $visitedCountries = [];
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT d. location FROM user_destinations ud JOIN destinations d ON ud.destination_id = d.id WHERE ud.user_id = :user_id");
        $stmt->execute([':user_id' => $_SESSION['user_id']]);
        $visitedCountries = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    $tripsCountries = [];
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT location FROM trips WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $_SESSION['user_id']]);
        $tripsCountries = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    $rawStamps = isset($_SESSION['user_id']) ? getUserStamps($pdo, $_SESSION['user_id']) : [];
    $stamps = array_map(function($s) {
        return [
            'icon'    => getCountryFlag($s['location']),
            'country' => $s['location'],
            'date'    => $s['visited_date'],
            'desc'    => $s['description']
        ];
    }, $rawStamps);
?>

<div class="container py-4">
    <div class="searchBar col-lg-4 col-md-12 col-sm-12">
        <input type="text" id="searchInput" class="form-control" placeholder="Search for a destination...">
    </div>

    <div class="mapView col-12">
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
            <h2>Stamps</h2>
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
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <h2 class="Currencies">Currencies</h2>
            </div>
        </div>
    </div>
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
        center: { lat: 53.3498, lng: -6.2603 },
        zoom: 8
    });

    map.data.setStyle((feature) => {
        const raw = feature.getProperty('ADMIN') ?? feature.getProperty('name') ?? feature.getProperty('NAME');
        const country = locationNormalizeMap[raw] ?? raw;
        const visited = visitedCountries.includes(country);
        const planned = tripsCountries.includes(country);
        return {
            fillColor: visited ? "green" : planned ? "yellow" : "gray",
            fillOpacity: visited || planned ? 0.7 : 0.25,
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
