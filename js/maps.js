// Initialize and add the map
var
// const
    $ = jQuery,
    data_areas_checkout_page = $('.wrapper_customer_details').data('map-areas'),
    data_areas_shipping_page = $('#wrapper_map').data('map-areas'),
    data_areas = data_areas_checkout_page || data_areas_shipping_page,
    mapZoom = 12,
    title = 'Holy Pizza, Zwycięska 14e/3, 53-033 Wrocław',
    position = {lat: 51.0584822, lng: 17.0122301},
    areas = [],
    maxDistance = data_areas && parseFloat(data_areas.max_distance) ? parseFloat(data_areas.max_distance) : 999999999999,
// let
    map,
    marker2,
    directionsRenderer,
    directionsService,
    inputAddress = $('#billing_address_1'),
    addressCorrect = false,
    checkboxTermsElement = null;

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

async function initMap() {
    try {

        if (data_areas) {
            registerMapAndServices();
            drawDeliveryAreas();

            if (data_areas_checkout_page) {
                searchAddressFromInput();
                selectAddressOnMap();
                checkCorrectAddress();
                resetMapChangeMethodDelivery();
            }
        }

    } catch (e) {
        console.log(e)
    }
}

function registerMapAndServices() {
    // checkout page or dostawa page
    const map_wrapper = document.getElementById("mapz") || document.getElementById("map_field");

    map = new google.maps.Map(map_wrapper, {
        zoom: mapZoom,
        center: position,
        streetViewControl: false,
        mapId: "CUSTOM_MAP_ID",
        // mapTypeId: "roadmap",
    });

    window.map = map;

    const icon = {
        url: 'https://maps.gstatic.com/mapfiles/place_api/icons/v1/png_71/geocode-71.png',
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25),
    };

    // init shop marker
    new google.maps.Marker({
        map: map,
        icon,
        title: title,
        position: position,
    });

    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);
    directionsService = new google.maps.DirectionsService();

    // map.addListener('click', () => map.setOptions({scrollwheel: true}));
    // map.addListener('drug', () => map.setOptions({scrollwheel: true}));
    // map.addListener('mouseout', () => map.setOptions({scrollwheel: false}));
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
            .parse(data_area.coordinates.replace('<p>', '').replace('</p>', ''))
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

function errorAddressInfo(text) {
    const className = 'addressDeliveryError';

    if (!text) {
        $(`.${className}`).remove();
        return;
    }

    if (!$(`.${className}`).length) {
        const jqElement = $(`<div class="no_address_search ${ className }">${ text }</div>`);
        $('#billing_address_1').parent().append(jqElement);
    }
}

function searchAddressFromInput() {
    inputAddress.on('blur', function (event) {
        const self = event.target;

        const activeDeliveryCourier = !!$('.switch_delivery_methods').not('.checked').length;

        setTimeout(function() {
            if (!addressCorrect && activeDeliveryCourier && self.value !== '') {
                const textPl = 'Proszę podać dokładny adres:<br><b>nazwę ulicy, numer budynku / numer lokalu,</b><br>a następnie wybrać lokalizację z rozwijanej listy.';
                const textUa = 'Будь ласка, вкажіть точну адресу:<br><b>назву вулиці, номер будинку / номер квартири,</b><br>а потім виберіть місце розташування зі списку, що випадає.';
                const textRu = 'Пожалуйста, укажите точный адрес:<br><b>название улицы, номер дома/номер квартиры,</b><br>а затем выберите местоположение из раскрывающегося списка.';
                const lang = getCookie('pll_language');
                const text = lang === 'pl' ? textPl : lang === 'ru' ? textRu : textUa;
                errorAddressInfo(text);

                setTimeout(function() {
                    self.focus();
                }, 100);
            }

            if (self.value === '' || !addressCorrect) {
                $('#billing_address_1_field').addClass('woocommerce-invalid');
            }

        }, 300);
    })

    inputAddress.on('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    })

    inputAddress.on('input', function (event) {
        setAddressNotCorrect();
        errorAddressInfo();
        if($('#billing_address_1_field').hasClass('woocommerce-invalid')) {
            $('#billing_address_1_field').removeClass('woocommerce-invalid');
        }

        if (this.value === '') {
            resetMap();
        }
    })

    function searchAddress() {
        resetMap();

        /**
         * Автозаповнення
         */

        // Определение прямоугольника, заключающего в себе многоугольники
        const bounds = new google.maps.LatLngBounds();
        areas.forEach( area => {
            area.getPath().forEach(function (point) {
                bounds.extend(point);
            });
        })

        const autocomplete = new google.maps.places.Autocomplete( inputAddress[0], {
            bounds: bounds,
            componentRestrictions: { country: "pl" },
            fields: ["formatted_address", "geometry", "name"],
            strictBounds: true,
            types: ["address"],
        });

        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();

            if (!place.geometry || !place.geometry.location) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                console.log("No details available for input: '" + place.name + "'");
                return;
            }

            addressCorrect = true;
            errorAddressInfo();
            calculateAndDisplayRoute(position, place.geometry.location);
        });
    }

    searchAddress();
}

function checkCorrectAddress() {
    console.log('check correct address.');

    // При зміні методу доставки завжди дивимось після оновлення
    // html документа на актуальність кнопок
    function checkRelevanceButton() {
        let checkStart = false;
        let checkEnd = false;

        if (checkboxTermsElement) {
            checkboxTermsElement.off('change');
        }

        const interval_id = setInterval(() => {
            if ($('.blockUI.blockOverlay').length) {
                if ( !checkStart ) {
                    checkStart = true;
                }
            } else {
                if ( checkStart ) {
                    checkEnd = true;

                    console.log( 'end interval');
                    clearInterval(interval_id);

                    $('#shipping_method input[type="radio"]').one('change', function(e) {
                        console.log(e.type, 2);
                        checkRelevanceButton();
                    });

                    checkboxTermsElement = $('#terms.woocommerce-form__input-checkbox');
                    checkboxTermsElement.on('change', function() {
                        if (!addressCorrect) {
                            $(this).prop('checked', false);
                            $('#billing_address_1_field').addClass('woocommerce-invalid');
                            window.scrollTo(0, 0);
                        }
                    });
                }
            }
            console.log('wait...')
        }, 300);
    }
    checkRelevanceButton();
}

function selectAddressOnMap() {
    google.maps.event.addListener(map, 'click', function (event) {
        calculateAndDisplayRoute(position, event.latLng, true)
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
    inputAddress.val(value)
    if (marker2) {
        marker2.title = value
    }
}

function addMarker2(position2) {
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

        // infowindow.setContent(place.name || "");
        // infowindow.open(map);
    })

    // Marker Drag to reCalc Route
    marker2.addListener('dragend', function (e) {
        calculateAndDisplayRoute(position, e.latLng, true)
    })
}

function calculateAndDisplayRoute(position, position2, pasteAddressInInputAddress = false) {

    // Checking if the selected address is within the delivery area
    const area = getDeliveryArea(position2)
    const last_area = getDeliveryArea(position2, true)

    // Set marker
    // addMarker2(position2)
    // map.setCenter(position2)

    // Calculate distance
    var request = {
        origin: position,
        destination: position2,
        travelMode: google.maps.TravelMode['DRIVING', 'WALKING', 'BICYCLING'], // DRIVING, WALKING, BICYCLING, TRANSIT
    };

    directionsService.route(request, function (response, status) {
        if (status === 'OK') {

            const data = {
                currentDistance: parseFloat(response.routes[0].legs[0].distance.value) / 1000,
                area: map.is_delivery_areas
                    ? {'last': last_area?.key_area, 'current': area?.key_area, 'quantity': map.is_delivery_areas}
                    : undefined,
                directions: response,
                pasteAddressInInputAddress,
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
            selectMethodShipping(data);
            addressCorrect = true;
        } else {
            // pickup only
            console.log( 'outside the delivery area' );
            setAddressNotCorrect();
            showPopupPickupOnly();
        }

    } else {

        if (data.currentDistance <= maxDistance) {
            // calc
            selectMethodShipping(data);
        } else {
            // pickup only
            console.log( 'beyond the maximum distance' );
            showPopupPickupOnly();
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

            if (data.currentDistance > prevDistance) {// && data.currentDistance <= methodSippingDistance) {
                methodSippingElement = elem
            }

            prevDistance = methodSippingDistance
        }
    })

    if (methodSippingElement) {
        $(methodSippingElement).children('input').click()
        if (data.pasteAddressInInputAddress) {
            pasteAddressIntoFields(data.directions.routes[0].legs[0].end_address)
        }
        directionsRenderer.setDirections(data.directions)
    } else {
        // pickup only (в адмінці ліміт методів доставки)
        console.log( 'info: limit delivery methods' );
        showPopupPickupOnly();
    }

}

function showPopupPickupOnly() {
    $('body').addClass('body--checkout_popup_delivery_info');

    $('.checkout_popup_delivery_info .popup_info__button').on('click', function (event) {
        resetMap();
        $('body').removeClass('body--checkout_popup_delivery_info');
    })
}

function resetMapChangeMethodDelivery() {
    let elementLeftButton = null;

    const intervalID = setInterval(() => {
        elementLeftButton = $('.switch-button.delivery-way.left-button');

        if (elementLeftButton.length) {
            clearInterval(intervalID);

            elementLeftButton.on('click', function (event) {
                resetMap();
            })
        }
    }, 300);
}

function resetMap() {
    directionsRenderer.setDirections({routes: []});
    marker2?.setMap(null);
    map.setCenter(position);
    map.setZoom(mapZoom);
    inputAddress.val('');
}

function setAddressNotCorrect() {
    addressCorrect = false;
    if (checkboxTermsElement) {
        checkboxTermsElement.prop('checked', false);
    }
}
