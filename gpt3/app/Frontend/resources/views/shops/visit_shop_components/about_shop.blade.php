<div class="row">
    <div class="col-sm">
        <p class="mb-1">Shop opened : {{ $shop->created_at ?? '' }}</p>
        <p class="mb-1">Ownder : {{ $shop->user->full_name ?? '' }}</p>
        <p class="mb-1">Address : <span>{{$shop->address ?? ''}}</span></p>
    
        <h5 class="my-3">Location</h5>

        <div id="map" style="height: 400px; width:100%"></div>
    </div>
</div>

<script>

    function initMap() {
        // The location of Uluru
            const uluru = { lat: {!! @$shop ? $shop->lat : 0 !!} , lng: {!! @$shop ? $shop->lng : 0 !!} };
            // The map, centered at Uluru
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: uluru,
            });
            // The marker, positioned at Uluru
            const marker = new google.maps.Marker({
                position: uluru,
                map: map,
        });
    }
    
</script>