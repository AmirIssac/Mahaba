@extends('Layouts.main')
@section('links')
<script src="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.js"></script>
<link type="text/css" rel="stylesheet" href="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.css"/>
@endsection
@section('body')
<!-- Header-->
<header class="bg-prim py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white my-sheep-header">
            <h1 class="display-4 fw-bolder text-prim">خاروفي</h1>
            <div style="display: flex; justify-content: space-around;" class="my-sheep-content">
                <div style="display: flex; flex-direction: column">
                    <i style="font-size: 32px;" class="fa-solid fa-phone"></i>
                    {{$profile->phone}}
                </div>
                <div style="display: flex; flex-direction: column">
                    <i style="font-size: 32px;" class="fa-solid fa-box-open"></i>
                    {{$orders_count}}
                </div>
                <div style="display: flex; flex-direction: column">
                    <i style="font-size: 32px;" class="fa-solid fa-envelope"></i>
                    {{$user->email}}
                </div>
            </div>
            <p class="lead fw-normal text-white-50 mb-0"> Dabbagh | دباغ</p>
        </div>
    </div>
</header>


<!-- Map Begin -->
<div id="map" class="map">

</div>
<!-- Map End -->


@section('scripts')
<script>
$(document).ready(function(){

// check for Geolocation support
if (navigator.geolocation) {
  console.log('Geolocation is supported!');
}
else {
  console.log('Geolocation is not supported for this Browser/OS.');
}

var startPos;
var  MQ, map, directions, routes = new Array();
L.mapquest.key = 'GPUOqQDwMnMxLWNY1wVUe96aw4ihyVr9';
  /*
  var geoSuccess = function(position) {
    startPos = position;
     var latitude = startPos.coords.latitude;
     var longitude = startPos.coords.longitude;
    var map = L.mapquest.map('map' , {
        center: [latitude , longitude],
        layers: L.mapquest.tileLayer('map'),
        zoom: 16,
    });
    //alert(map);
    map.load(function() {
          map.icons.marker.add({ lng: longitude, lat: latitude })
          map.fitBounds();
        });
  };
  */
    var geoSuccess = function(position) {    // find your current position and load the map
        startPos = position;
        var latitude = startPos.coords.latitude;
        var longitude = startPos.coords.longitude;
        var map = L.mapquest.map('map' , {
            center: [latitude , longitude],
            layers: L.mapquest.tileLayer('map'),
            zoom: 16,
        });
        map.addControl(L.mapquest.control());
        L.marker([latitude, longitude], {
          icon: L.mapquest.icons.marker(),
          draggable: false
        }).bindPopup('Denver, CO').addTo(map);

        $('#latitude').val(latitude);
        $('#longitude').val(longitude);
        // reverse geocoding (request api)
        function httpGet(theUrl)
        {
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
            xmlHttp.send( null );
            return xmlHttp.responseText;
        }

        console.log(httpGet('http://www.mapquestapi.com/geocoding/v1/reverse?key=GPUOqQDwMnMxLWNY1wVUe96aw4ihyVr9&location='+latitude+','+longitude+'&includeRoadMetadata=true&includeNearestIntersection=true'));
        var json_location = httpGet('http://www.mapquestapi.com/geocoding/v1/reverse?key=GPUOqQDwMnMxLWNY1wVUe96aw4ihyVr9&location='+latitude+','+longitude+'&includeRoadMetadata=true&includeNearestIntersection=true');
        var location_info_object = JSON.parse(json_location);  // object
        var address = "";
        //address = address.concat(location_info_object.locations[0].adminArea1)
        //$('#address').val(address);
        address = address.concat(location_info_object.results[0].locations[0].street.concat(' , '+location_info_object.results[0].locations[0].adminArea5).concat(' , '+location_info_object.results[0].locations[0].adminArea3).concat(' , '+location_info_object.results[0].locations[0].adminArea1));
        $('#address').val(address);
    };
    navigator.geolocation.getCurrentPosition(geoSuccess);
    /*
    alert('final lat is ' + latitude);
    */

// 'map' refers to a <div> element with the ID map

});
</script>
@endsection
@endsection
