@extends('frontend::layouts.main')

@section('content')
<br>
<section class="section-request padding-y-sm">
<div class="container">

    <div class="row">
        <aside class="col-sm-3 left-side">
            @include('frontend::my.partials.sidebar_menu')
        </aside> <!-- col.// -->

        <main class="col-sm-9 right-side">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <header class="card-header">
                            <h6 class="title">Redeem Gift</h6>
                        </header>

                        <div class="row mt-3">
                            <div class="col-sm mx-3 py-2" style="background-color: #69b928 !important">
                                <div class="d-flex">
                                    <p class="m-0 mr-3 text-white">Your Points : <span id="myPoint" data-value="{{ $point?? 0 }}">{{ $point?? 0 }}</span> pts</p>
                                    <p class="m-0 mx-3 text-white">Your Balance : USD $<span id="myBalance" data-value="{{ $balance?? 0 }}">{{ $balance?? 0 }}</span></p>
                                    <a href="#" class="btn btn-exchange btn-success m-0 mx-3 py-0 border border-white">Exchange</a>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm mx-3">
                                <div class="list-group list-group-horizontal" id="list-tab" role="tablist">
                                    <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">Items gift</a>
                                    <a class="list-group-item list-group-item-action exchanged-list-items" onclick="getExchangedGift()" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">My exchanged gift</a>
                                </div>
                            </div>
                            <div class="col-sm"></div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="tab-content" id="nav-tabContent">
                                    {{-- toggle itmes --}}

                                        <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list" style="background-color: rgba(255, 0, 0, 0) !important">
                                            @include('frontend::my.reedem-gift.items', ['redeem_items' => $redeem_items])
                                        </div>
                                    {{--end  toggle itmes --}}

                                    {{-- toggle itmes list --}}
                                        <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list" style="background-color: rgba(255, 0, 0, 0) !important">
                                            
                                        </div>
                                    {{--end toggle itmes list --}}
                                     
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- col // -->
            </div>
        </main>
    </div> <!-- row.// -->

</div><!-- container // -->
</section>

@endsection

@section('scripts')
    <script>
        var title = 'Redeem Gift <img class="ml-3" width="30" height="30" src="{{asset('img/icons/gift.png')}}" alt="gift.png">';
        var items = [];

        $(document).on('click','.btn-exchange', function() {
            let point = $('#myPoint').data('value');
            let myinput = 0;

            Swal.fire({
                title: title,
                input: 'number',
                inputAttributes: {
                    autocapitalize: 'off',
                    class: 'number'
                },
                showCancelButton: true,
                confirmButtonText: 'Exchange',
                showLoaderOnConfirm: true,
                preConfirm: (value) => {
                    myinput = parseFloat(value);
                    $.post( "{{ route('redeem-point.exchange') }}", {_token: "{{ csrf_token() }}", point:value}, function(response) {
                        return response;
                    })
                    .done(function(response) {
                        console.log(response);
                    })
                    .fail(function(response) {
                        Swal.showValidationMessage(
                        `Something error: ${response.responseJSON.message}`
                        );
                    })
                    .always(function() {
                        // alert( "finished" );
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        let my_balance = $('#myBalance').attr('data-value');
                        let total = parseFloat(my_balance) + parseFloat(result.value);

                        $('#myBalance').attr('data-value', total);
                        $('#myBalance').text(total);

                        let my_total_point = parseFloat($('#myPoint').attr('data-value'));
                        $('#myPoint').attr('data-value', my_total_point - myinput);
                        $('#myPoint').text(my_total_point - myinput);
                        Swal.fire({
                            title: `Congratulation`,
                            text: `You have successfully exchange to $${result.value}`
                            // imageUrl: result.value.avatar_url
                        })
                    }
            });
        });

        $(document).on('click', '.cart-exchange', function(e) {
            e.preventDefault();

            let cart = {
                id : $(this).data('id'),
                name : $(this).data('name'),
                point : parseFloat($(this).data('point')),
                image : $(this).data('img'),
            };

            let date = new Date().toISOString().split('T');
            let my_points = parseFloat($('#myBalance').attr('data-value'));

            let text = my_points >= cart.point ? `Are your sure want to exchange this item?` : `Not enough point to exchange! You have only ${my_points} pts.`;

            Swal.fire({
                title: title,
                text: text ,
                showCancelButton: true,
                confirmButtonText: `Confirm`,
                customClass: {
                    confirmButton: 'btn-success',
                }
            }).then((result) => {
                /* Read more about isConfirmed*/
                if (result.isConfirmed) {
                    if(my_points >= cart.point) {
                        Swal.fire({
                            title:title,
                            text: "Item changed successfully."
                        });

                        cart.created_at = date[0];
                        
                        $('#myBalance').text((my_points - cart.point).toFixed(2));
                        $('#myBalance').attr('data-value', my_points - cart.point);
                        items.push(cart);
                    }
                } 
            });
        });

      
      function getExchangedGift()
      {
        let html ='';

        items.forEach(item => {
            html += `<div class="row">
                        <div class="col-sm d-flex">
                            <img class="card-img-top" style="margin:0; height: 6rem !important;width: 6rem !important;" src="${item.image}" alt="Card image cap">
                            <div class="ml-2">
                                <p class="m-0">Name : ${item.name != '' ? item.name : 'Unkown name'}</p>
                                <p class="m-0">USD : $${item.point}</p>
                                <p class="m-0">Date : ${item.created_at}</p>
                            </div>
                        </div>
                    </div>
                    <hr>`;
        });  
        $('#list-profile').html(html);
      }
    </script>
    

@endsection
