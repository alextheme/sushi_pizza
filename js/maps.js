// Initialize and add the map
let map,
    zoom = 14,
    title = 'SUSHIPAK, Żeromskiego 60, 50-312 Wrocław',
    position = { lat: 51.12455718031603, lng: 17.049562143638372 },
    marker2,
    geocode,
    directionsService,
    directionsRenderer,
    drawingManager,
    zonePolygon;

async function initMap() {
    try {
        // init map
        map = new google.maps.Map(document.getElementById("map_field"), {
            zoom: zoom,
            center: position,
            mapId: "CUSTOM_MAP_ID",
        });

        // init shop marker
        new google.maps.Marker({
            map: map,
            position: position,
            title: title,
        });

        // Calc
        geocode = new google.maps.Geocoder();
        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer();
        directionsRenderer.setMap(map);

        // setDrawingManager() // Draw Polygon

        drawZoneDelivery()
        selectAddressOnMap()

    } catch (e) {
        console.log(e)
    }
}

function selectAddressOnMap() {

    google.maps.event.addListener(map, 'click', function(e) {
        calculateAndDisplayRoute(position, e.latLng)
        addMarker2(position, e.latLng)
    });
}

function insertAddress2(value) {
    document.getElementById('billing_address_1').value = value
}

function addMarker2(position, position2) {
    // clear marker
    marker2?.setMap(null)

    // Set marker to delivery
    marker2 = new google.maps.Marker({
        map,
        position: position2,
        draggable: true,
        title: '',
        animation: google.maps.Animation.DROP,// BOUNCE, DROP
    });

    // Marker animation
    marker2.addListener('click', function () {
        marker2.setAnimation(google.maps.Animation.BOUNCE);

        setTimeout(() => {
            marker2.setAnimation(null);
        }, 1000)
    })

    // Marker Drag to reCalc Route
    marker2.addListener('dragend', function (e) {
        calculateAndDisplayRoute(position, e.latLng)
    })
}

function calculateAndDisplayRoute(position, position2) {

    // Запит для Directions Service
    var request = {
        origin: position,
        destination: position2,
        travelMode: google.maps.TravelMode['DRIVING'],// або 'WALKING', 'BICYCLING', 'TRANSIT'
    };

    // Виклик Directions Service
    directionsService.route(request, function (response, status) {
        if (status === 'OK') {

            directionsRenderer.setDirections(response);

            const address2 = response.routes[0].legs[0].end_address
            insertAddress2(address2)
            if ( marker2 ) {
                marker2.title = address2
            }

            // TODO: прорахунки перевірки зони і т.і............
            const distance = response.routes[0].legs[0].distance.text
            const duration = response.routes[0].legs[0].duration.text
            const address1 = response.routes[0].legs[0].start_address
            console.log( distance +'|'+ duration +'|'+ address2 )
        } else {
            alert('Directions request failed due to ' + status);
        }
    });
}

// TODO: відмалювати зони доставки
function drawZoneDelivery() {
    // pointAInput.split(',').map(coord => parseFloat(coord.trim())))
    const str = '16.9452518 51.1027955, 16.947655 51.0855458, 16.9634479 51.0631116, 16.9819873 51.0508113, 17.0197528 51.0432568, 17.0523685 51.0510271, 17.0777744 51.0654849, 17.0815509 51.0905057, 17.0588916 51.1161596, 17.0266193 51.1232711, 16.9452518 51.1027955'
    const coordinatesObjects = [];
    str
        .split(',')
        .map(c => c.trim())
        .forEach(c => {
            const coords = c.split(' ');
            coordinatesObjects.push({
                lat: parseFloat(coords[0]),
                lng: parseFloat(coords[1])
            });
        })


    console.log(coordinatesObjects )

    const flightPath = new google.maps.Polyline({
        path: coordinatesObjects,
        geodesic: true,
        strokeColor: "#FF0000",
        strokeOpacity: 1.0,
        strokeWeight: 2,
    });

    flightPath.setMap(map);
}




/**
 *
 */
////////////////////////////////////////////
function setDrawingManager() {
    // Draw
    drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.MARKER,
        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [
                google.maps.drawing.OverlayType.MARKER,
                google.maps.drawing.OverlayType.CIRCLE,
                google.maps.drawing.OverlayType.POLYGON,
                google.maps.drawing.OverlayType.POLYLINE,
                google.maps.drawing.OverlayType.RECTANGLE,
            ],
        },
        markerOptions: {
            icon: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",
        },
        circleOptions: {
            fillColor: "#ffff00",
            fillOpacity: 1,
            strokeWeight: 5,
            clickable: false,
            editable: true,
            zIndex: 1,
        },
    });

    drawingManager.setMap(map);
}

////////////////////////////////////////////
function defineZone() {
    // Визначення зони на карті за допомогою Drawing Manager
    google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
        zonePolygon = polygon;
    });
}

////////////////////////////////////////////
function checkPointInZone() {
    var pointAInput = document.getElementById('pointA').value;
    var pointBInput = document.getElementById('pointB').value;

    // Перевірка наявності введених координат
    if (!pointAInput || !pointBInput || !zonePolygon) {
        alert('Please define the zone and enter both point A and point B coordinates.');
        return;
    }

    // Розбір координат точок A та B
    var pointALatLng = new google.maps.LatLng(...pointAInput.split(',').map(coord => parseFloat(coord.trim())));
    var pointBLatLng = new google.maps.LatLng(...pointBInput.split(',').map(coord => parseFloat(coord.trim())));

    // Перевірка, чи точка B знаходиться в заданій зоні
    var isPointInZone = google.maps.geometry.poly.containsLocation(pointBLatLng, zonePolygon);

    // Відображення результату
    if (isPointInZone) {
        alert('Point B is inside the defined zone.');
    } else {
        alert('Point B is outside the defined zone.');
    }
}

// Виклик initMap при завантаженні сторінки
google.maps.event.addDomListener(window, 'load', initMap);