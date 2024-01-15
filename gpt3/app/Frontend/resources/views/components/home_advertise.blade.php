    @php
    $counter = 0;
    @endphp
    @foreach ($brands as $key => $brand)
        @if ($brand['id'] == 0)
        @else
        @if ($counter < 4)

        <!--
        <div class="col-xs-12 col-sm">
            <a href="{{route('search.brand', $brand['id'])}}">
                <div class="p-3" 
                    style="
                    margin: auto;
                    border-radius: 0px;
                    max-width: 100%;
                    max-height: 100%;
                    vertical-align: middle;
                    background-image: url('{{$brand['image_name']}}');
                    background-repeat:no-repeat; background-position: center center; 
                    ">
                </div>
            </a>
        </div>
        -->
        
        <div class="col-xs-12 col-sm">
            <a href="{{route('search.brand', $brand['id'])}}">
                <img src="{{$brand['image_name']}}" style="
                    margin: auto;
                    max-width: 100%;
                    max-height: 60px;
                ">
            </a>
        </div>
        
        <!--
        <img src=
"https://media.geeksforgeeks.org/wp-content/uploads/geeksforgeeks-25.png" 
    alt="Geeks Image">
        -->

        @php
        $counter++;
        @endphp
        @endif
        @endif
    @endforeach
