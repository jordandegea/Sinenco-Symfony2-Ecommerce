
function loadAddressPicker() {
    
    // On fait une boucle 0..19 
    // car on ne peut verifier lexistence des forms
    for (i = 0; i < 20; i++) { 
      
        var addresspickerMap = new Array() ; 

        addresspickerMap[i] = $( "#useraddress_"+i+"_address" ).addresspicker({
            regionBias: "fr",
            language: "fr",
            updateCallback: function(geocodeResult, parsedGeocodeResult){
                        $('#callback_result').text(JSON.stringify(parsedGeocodeResult, null, 4));
                    },
            mapOptions: {
                zoom: 4,
                center: new google.maps.LatLng(46, 2),
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            },
            elements: {
                //map: "#map",
                //lat: "#lat",
                //lng: "#lng",
                street_number: '#useraddress_'+i+'_streetNumber',
                route: '#useraddress_'+i+'_route',
                locality: '#useraddress_'+i+'_city',
                //sublocality: '#sublocality',
                //administrative_area_level_3: '#administrative_area_level_3',
                //administrative_area_level_2: '#administrative_area_level_2',
                administrative_area_level_1: '#useraddress_'+i+'_stateRegion',
                country: '#useraddress_'+i+'_country',
                postal_code: '#useraddress_'+i+'_zipCode'
                //type: '#type'
            }
        });

    }
    
    
};

loadAddressPicker();
