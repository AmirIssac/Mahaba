@extends('Layouts.main')
@section('links')
<script src="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.js"></script>
<link type="text/css" rel="stylesheet" href="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.css"/>
@endsection
@section('content')
<section style="margin-top: -100px;" class="contact spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                <div class="contact__widget">
                    <span class="icon_phone"></span>
                    <h4>Phone</h4>
                    <p>{{$profile->phone}}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                <div class="contact__widget">
                    <i class="fa fa-shopping-cart order-icon"></i>
                    <h4>Orders</h4>
                    <p>{{$orders_count}}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                <div class="contact__widget">
                    <span class="icon_box-checked"></span>
                    <h4>Points</h4>
                    <p>{{$profile->points}}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                <div class="contact__widget">
                    <span class="icon_mail_alt"></span>
                    <h4>Email</h4>
                    <p>{{$user->email}}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form Begin -->
<div style="margin-top: -100px" class="contact-form spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="contact__form__title">
                    <h2>My Information</h2>
                </div>
            </div>
        </div>
        <form action="{{route('submit.profile')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="{{$profile->first_name}}">
                </div>
                <div class="col-lg-4 col-md-4">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="{{$profile->last_name}}">
                </div>
                <div class="col-lg-4 col-md-4">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{$profile->phone}}">
                </div>
                <div class="col-lg-4 col-md-4">
                    <label>Address</label>
                    <input type="text" name="address_address" id="address">
                    <input type="hidden" name="address_latitude" id="latitude">
                    <input type="hidden" name="address_longitude" id="longitude">
                    <button type="submit" class="site-btn">Submit</button>
                </div>
                
                {{--
                <div class="col-lg-12 text-center">
                    <textarea placeholder="Your message"></textarea>
                    <button type="submit" class="site-btn">SEND MESSAGE</button>
                </div>
                --}}
            </div>
        </form>
    </div>
</div>
<!-- Contact Form End -->

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