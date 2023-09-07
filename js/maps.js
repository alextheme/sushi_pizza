/**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * SPDX-License-Identifier: Apache-2.0
 */



// let plZawal = { lat: 51.134760, lng: 17.037680 };
// let plSwierad = {lat: 51.40089, lng: 16.20149};
let ulZeromskiego60Wrocław = {lat: 51.12455718031603, lng: 17.049562143638372};

let zoom = 12;
let lastMarker;

const zawalna = {};

const swieradowska = {};


function initMapFunc(ranges, ids, stitle, localization) {

    let i = 1;

    // Create the map Zawalna.
    const map = new google.maps.Map(document.getElementById(ids), {
        zoom: zoom,
        center: localization,
        mapTypeId: "terrain",
    });

    setTimeout(function () {


        const options = {
            fields: ["formatted_address", "geometry", "name"],
            strictBounds: false,
            types: ["geocode"],
        };
        const input = document.getElementById("billing_address_1");

        const autocomplete = new google.maps.places.Autocomplete(input, options);
        autocomplete.bindTo("bounds", map);
    }, 1000);


    new google.maps.Marker({
        position: localization,
        map,
        title: stitle,
    });

    if (document.getElementById("map_field")) {
        let address_input = document.getElementById("address-filled");

        address_input.addEventListener('click', function () {
            locateAddress(document.getElementById("billing_address_1").value, map);
        })

    }

    for (const range in ranges) {


        i += 0.2;
        const sCircle = new google.maps.Circle({
            strokeColor: "#9ce6b6",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#016323",
            fillOpacity: 0.2 / i,
            map,
            center: ranges[range].center,
            radius: ranges[range].range * 1000,
        });
    }
}

function funEventCheck() {
    let address_input = document.getElementById("address-filled");
    address_input.click();
}

function initMap() {
    console.log('map init')

    if (document.getElementById("mapz")) {
        initMapFunc(swieradowska, "mapz", "Miasto Wrocław", ulZeromskiego60Wrocław);
    } else if (document.getElementById("map_field")) {

        initMapFunc(swieradowska, "map_field", "Miasto Wrocław", ulZeromskiego60Wrocław);
    }

}


function locateAddress(address, map) {
    if (lastMarker != undefined) {
        lastMarker.setMap(null);
    }
    let geocoder = new google.maps.Geocoder(); // initialize google map object
    geocoder.geocode({'address': address}, function (results, status) {
        if (results) {
            let latitude = results[0].geometry.location.lat();
            let longitude = results[0].geometry.location.lng();
            let myCenter = new google.maps.LatLng(latitude, longitude);
            lastMarker = new google.maps.Marker({
                position: myCenter,
                map
            });
        } else {
            //alert("Nie znaleźlismy takiego adresu!");
        }
    });


}
