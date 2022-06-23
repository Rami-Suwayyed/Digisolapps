import * as Helper from '../modules/helpers.js'
var lang = $("html").attr('lang')

$(".fetchAddressInfo").on("click",function(e){
    e.preventDefault();
    let lat = parseFloat(inputLat.value);
    let lng = parseFloat(inputLng.value);

    let mapBox = $("#map-box");
    let infoBox = $("#info-box");
    let parentBox = $("#parent-box");
    let locationData = {};

    if(!isNaN(lat) && !isNaN(lng)){

        var latlng = new google.maps.LatLng(lat, lng);
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'latLng': latlng }, async function (results, status) {
            if (status !== google.maps.GeocoderStatus.OK) {
                alert(status);
            }
            if (status === google.maps.GeocoderStatus.OK) {
                console.log(results);
                results.forEach(element => {
                    element.types.forEach(el => {
                        switch (el) {
                            case "country":
                                locationData.country = element.address_components[0].long_name
                                break;
                            case "administrative_area_level_1":
                                locationData.governorate = element.address_components[0].long_name
                                break;
                            case "locality":
                                console.log("loc")
                                locationData.locality = element.address_components[0].long_name
                                break;
                            case "sublocality":
                                locationData.subLocality = element.address_components[0].long_name
                                break;
                            case "neighborhood":
                                locationData.neighborhood = element.address_components[0].long_name
                                break;
                            default:
                                break;
                        }

                    });
                });
                const response = await Helper.ajaxCall(`/${lang}/ajax/locations-support/check`, locationData)
                if (response.status_number === 'S200') {
                    let result = response.data.join(" - ");
                    mapBox.fadeOut(300, function () {
                        infoBox.find("#location").children(".text").text(result);
                        infoBox.fadeIn();
                    });
                } else if (response.status_number === 'S300') {
                    alert("The Location Already Registered");
                } else {
                    alert("This Location is not supported");
                }
                console.log(locationData);
            }
        });

    }else{
        alert("Define The Location Please ..");
    }

});
$("#CancelLocation").on("click",async function (e) {
    await Helper.ajaxCall(`/${lang}/ajax/locations-support/cancel`)
    location.reload();
});
