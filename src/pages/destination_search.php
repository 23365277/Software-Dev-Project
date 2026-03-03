<?php
	$pageTitle = "Roamance - Destination Search";
	$pageCSS = "/assets/css/destination_search.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>

<div class="container py-4">
    <div class="searchBar col-lg-4 col-md-12 col-sm-12">
        <input type="text" id="searchInput" class="form-control" placeholder="Search for a destination...">
    </div>

    <div class="mapView col-12">
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>
</div>

<script>
let map;
let marker;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 53.3498, lng: -6.2603 },
        zoom: 8
    });

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
