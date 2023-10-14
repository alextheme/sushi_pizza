// Initialize and add the map
const
    $ = jQuery,
    data_areas = $('.wrapper_customer_details').data('map-areas'),
    zoom = 12,
    title = 'SUSHIPAK, Żeromskiego 60, 50-312 Wrocław',
    position = {lat: 51.12455718031603, lng: 17.049562143638372},// 51.12455718031603, 17.049562143638372
    areas = [],
    maxDistance = parseFloat(data_areas.max_distance) ? parseFloat(data_areas.max_distance) : 999999999999;

console.log(maxDistance)

let
    map,
    marker2,
    directionsRenderer,
    directionsService;

async function initMap() {
    try {
        registerMapAndServices()

        drawDeliveryAreas()

        selectAddressOnMap()

        searchAddressFromInput()
    } catch (e) {
        console.log(e)
    }
}

function registerMapAndServices() {
    map = new google.maps.Map(document.getElementById("map_field"), {
        zoom: zoom,
        center: position,
        streetViewControl: false,
        mapId: "CUSTOM_MAP_ID",
    });

    // init shop marker
    new google.maps.Marker({
        map: map,
        position: position,
        title: title,
    });

    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);
    directionsService = new google.maps.DirectionsService();

    map.addListener('click', () => map.setOptions({scrollwheel: true}));
    map.addListener('drug', () => map.setOptions({scrollwheel: true}));
    map.addListener('mouseout', () => map.setOptions({scrollwheel: false}));
}

// Show delivery areas
function drawDeliveryAreas() {
    // const polygons_example = [
    //     [{"lat": 51.12681965932414, "lng": 17.038404154136426}, {"lat": 51.10936337964996, "lng": 16.982785868003614}, {"lat": 51.08327442875694, "lng": 16.958753275230176}, {"lat": 51.06580169853565, "lng": 16.982442545249707}, {"lat": 51.05824971197434, "lng": 17.026731180503614}, {"lat": 51.05954442573592, "lng": 17.079946207359082}, {"lat": 51.07723521876395, "lng": 17.107412027671582}, {"lat": 51.09621293852538, "lng": 17.100888895347364}, {"lat": 51.11367418536927, "lng": 17.07822959358955}],
    //     [{"lat": 51.13457588888509, "lng": 17.03085105355049}, {"lat": 51.11884662144572, "lng": 16.959096597984082}, {"lat": 51.08349010024799, "lng": 16.92648093636299}, {"lat": 51.053286307151, "lng": 16.955320047691114}, {"lat": 51.044868883025686, "lng": 17.020894693687207}, {"lat": 51.04724318318089, "lng": 17.10397880013252}, {"lat": 51.073999603406634, "lng": 17.128354715659864}, {"lat": 51.11604495711379, "lng": 17.11221854622627}, {"lat": 51.13199062377263, "lng": 17.061406778648145}],
    // ]

    if (!data_areas.show_areas || !data_areas.areas_for_map) return

    let zIndex = 100
    data_areas.areas_for_map.forEach((data_area, i) => {
        const paths = JSON
            .parse(data_area.coordinates
                .replace('<p>', '')
                .replace('</p>', '')
            )
            .map(p => ({lat: +p.lat, lng: +p.lng}))

        // Створення полігону
        areas[i] = new google.maps.Polygon({
            paths: paths,
            map: map,

            fillColor: data_area.fill_color,//"#FFA500",
            fillOpacity: data_area.fill_opacity,//.1,

            strokeColor: data_area.line_color,//'#FFA500',
            strokeOpacity: data_area.line_opacity,//.5,
            strokeWeight: data_area.line_weight,//1,

            clickable: false,
            draggable: false,
            editable: false,
            zIndex: zIndex--,
        });

        areas[i].key_area = i + 1

        // Додавання обробників подій для полігону
        // google.maps.event.addListener(areas[i], 'click', function (event) {
        //     console.log(this);
        //     console.log(this.key_area);
        // });
    })

    map.is_delivery_areas = data_areas.areas_for_map.length
}

function searchAddressFromInput() {
    const input = document.getElementById('billing_address_1')

    // Визначення прямокутника, що охоплює полігони
    let bounds = {
        north: position.lat + 0.2,
        south: position.lat - 0.2,
        east: position.lng + 0.2,
        west: position.lng - 0.2,
    };

    if (map.is_delivery_areas) {
        bounds = new google.maps.LatLngBounds();
        areas.forEach(area => {
            area.getPath().forEach(function (point) {
                bounds.extend(point);
            });
        })
    }

    // Показати випадаючий список для допомоги вибору адреси
    const autocomplete = new google.maps.places.Autocomplete(input, {
        bounds: bounds,
        fields: ["address_components", "geometry", "icon", "name"],
        componentRestrictions: {country: "pl"},
        strictBounds: true,
    });

    // Слухач події вибору варіанта зі списку автозаповнення
    autocomplete.addListener('place_changed', function () {
        const place = autocomplete.getPlace();

        if (!place.geometry) return

        calculateAndDisplayRoute(position, place.geometry.location)
    });

    input.addEventListener('input', function (event) {
        if (this.value === '') {
            directionsRenderer.setDirections({routes: []});
            marker2.setMap(null);
        }
    })

    $('.switch-button.delivery-way.left-buttton').on('click', function (event) {
        directionsRenderer.setDirections({routes: []});
        marker2.setMap(null);
    })

}

function selectAddressOnMap() {
    google.maps.event.addListener(map, 'click', function (event) {
        calculateAndDisplayRoute(position, event.latLng)
    });
}

function getDeliveryArea(location, last_area = false) {
    const condition = area => google.maps.geometry.poly.containsLocation(location, area)

    if (last_area) {
        return areas.filter(area => condition(area)).pop()
    } else {
        return areas.find(area => condition(area))
    }
}

function pasteAddressIntoFields(value) {
    document.getElementById('billing_address_1').value = value
    if (marker2) {
        marker2.title = value
    }
}

function addMarker2(position, position2) {
    // clear marker
    // marker2 ? marker2.setMap(null) : '';
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

    // Checking if the selected address is within the delivery area
    const area = getDeliveryArea(position2)
    const last_area = getDeliveryArea(position2, true)

    // Set marker
    addMarker2(position, position2)

    // Calculate distance
    var request = {
        origin: position,
        destination: position2,
        travelMode: google.maps.TravelMode['DRIVING', 'WALKING', 'BICYCLING', 'TRANSIT'], // or 'WALKING', 'BICYCLING', 'TRANSIT'
    };

    directionsService.route(request, function (response, status) {
        if (status === 'OK') {

            const data = {
                currentDistance: parseFloat(response.routes[0].legs[0].distance.value) / 1000,
                area: map.is_delivery_areas
                    ? {'last': last_area?.key_area, 'current': area?.key_area, 'quantity': map.is_delivery_areas}
                    : undefined,
                directions: response,
            }

            checkDistanceAndDeliveryArea(data)

        } else {
            alert('Directions request failed due to ' + status);
        }
    });
}

function checkDistanceAndDeliveryArea (data) {
    console.log( 'distance: ', data.currentDistance )

    if (data.area) {

        if (data.area.last) {
            // calc
            selectMethodShipping(data)
        } else {
            // pickup only
            console.log( 'outside the delivery area' )
            showPopupPickupOnly(data.directions)
        }

    } else {

        if (data.currentDistance <= maxDistance) {
            // calc
            selectMethodShipping(data)
        } else {
            // pickup only
            console.log( 'beyond the maximum distance' )
            showPopupPickupOnly(data.directions)
        }
    }
}

function selectMethodShipping(data) {

    let methodSippingElement = null
    let methodSippingDistance = 0
    let prevDistance = 0

    $('#shipping_method li').each((i, elem) => {
        if (i > 0) {

            const methodSippingText = $(elem).find('label').text().replaceAll(',', '.')

            const numbers = methodSippingText.match(/[-]{0,1}[\d]*[\.]{0,1}[\d]+/g)
            if (numbers) {
                methodSippingDistance = parseFloat(numbers[0]) // km
            }

            if (data.currentDistance > prevDistance && data.currentDistance <= methodSippingDistance) {
                methodSippingElement = elem
            }

            prevDistance = methodSippingDistance
        }
    })

    if (methodSippingElement) {
        $(methodSippingElement).children('input').click()
        pasteAddressIntoFields(data.directions.routes[0].legs[0].end_address)
        directionsRenderer.setDirections(data.directions)
    } else {
        // pickup only (в адмінці ліміт методів доставки)
        console.log( 'a: limit delivery methods' );
        showPopupPickupOnly(data.directions)
    }

}

function showPopupPickupOnly(directions) {
    $('body').addClass('body--checkout_popup_delivery_info');

    $('.checkout_popup_delivery_info .checkout_popup_button').on('click', function (event) {
        $('.switch-button.delivery-way.right-buttton').click()
        $($('#shipping_method li')[0]).children('input').click()
        directionsRenderer.setDirections({routes: []})
        document.getElementById('billing_address_1').value = ''
        marker2.setMap(null)
        $('body').removeClass('body--checkout_popup_delivery_info');
    })
}


