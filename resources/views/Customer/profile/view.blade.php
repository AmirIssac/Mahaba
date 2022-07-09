@extends('Layouts.main')
@section('links')
{{--
<script src="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.js"></script>
<link type="text/css" rel="stylesheet" href="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.css"/>
--}}
<style>
    @media only screen and (max-width: 600px) {
        .my-sheep-content {
            flex-direction: column;
        }
        .icon-div{
            margin: 25px 0px;
        }
        .phone-div{
            margin: 5px 0 0 10px;
        }
    }
 </style>
@endsection
@section('body')
<!-- Header-->
<header class="bg-prim py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white my-sheep-header">
            <h1 class="display-4 fw-bolder text-prim">خاروفي</h1>
            <div style="display: flex; justify-content: space-around; padding:50px" class="my-sheep-content">
                <div style="display: flex; flex-direction: column" class="icon-div">
                    <i style="font-size: 32px;" class="fa-solid fa-phone"></i>
                    {{$profile->phone}}
                </div>
                <div style="display: flex; flex-direction: column" class="icon-div">
                    <i style="font-size: 32px;" class="fa-solid fa-box-open"></i>
                    {{$orders_count}}
                </div>
                <div style="display: flex; flex-direction: column" class="icon-div">
                    <i style="font-size: 32px;" class="fa-solid fa-envelope"></i>
                    {{$user->email}}
                </div>
            </div>
            <p class="lead fw-normal text-white-50 mb-0"> Dabbagh | دباغ</p>
        </div>
    </div>
</header>


<section class="bg-second py-2 text-second">
    <div class="container">
        <div class="text-center text-white product-div">
            <div class="row bg-second">
                <div class="col-lg-12 text-second">
                    <div style="background-color: #f5ebeb" id="checkout-box">
                        <h5>My information</h5>
                        <form action="{{route('submit.profile')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-2">
                                    <label>First Name</label>
                                    <input type="text" name="first_name" value="{{$profile->first_name}}">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-2">
                                    <label>Last Name</label>
                                    <input type="text" name="last_name" value="{{$profile->last_name}}">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-2 phone-div">
                                    <label>Phone</label>
                                    <input type="text" name="phone" value="{{$profile->phone}}">
                                </div>
                                <div style="margin: 10px 0 0 0;" class="col-lg-4 col-md-4">
                                    <label>Address</label>
                                    <input type="text" name="address_address" value="{{ $profile->address_address }}" placeholder="enter your full address" id="address">
                                    <input type="hidden" name="address_latitude" id="latitude">
                                    <input type="hidden" name="address_longitude" id="longitude">
                                </div>
                                <div class="col-lg-8 col-md-8">
                                </div>
                                <div  style="margin: 10px 0 0 0;" class="col-lg-4 col-md-4">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<!-- Map Begin -->
<div id="map" class="map">

</div>
<!-- Map End -->

{{--

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
--}}
@endsection
