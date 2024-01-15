<h5>{{ 20 }} Products</h5>

<div class="row">
    <div class="col-sm-8">
        <div class="d-flex">
            <input type="text" class="form-control">
            <a href="#" class="btn btn-sm btn-warning px-4 pt-2"><i class="fa fa-search" style="font-size: 18px"></i></a>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="btn-group float-right">
            <span class="mt-2 mx-2"> Sort By :</span>
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Popular Products
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="#">Latest Product</a>
              <a class="dropdown-item" href="#">Lowest prices</a>
              <a class="dropdown-item" href="#">Highest prices</a>
            </div>
          </div>
    </div>
</div>

<div class="row my-5">
    <div class="col-sm">
        <div class="d-flex align-items-center flex-wrap">

            @foreach ($products as $item)
            {{-- <div class="col-sm-2"> --}}
                <div class="card mx-3 mb-3 mx-auto" style="width: 16rem;">
                    <img class="card-img-top mx-auto" src="{{ $item['image_name'] }}" alt="{{ $item['name'] }}" style="margin:0; height: 10em !important;width: 10em !important;" >
                    <div class="card-body text-center">
                      <h5 class="card-title">
                            {{$item['name']}}
                      </h5>
                      <span class="m-0 h1 font-weight-bold text-shop" style="">USD ${{ $item['unit_price'] }}</span>

                    </div>
                  </div>
            {{-- </div> --}}
                    
            @endforeach
        </div>
    </div>
    

</div>