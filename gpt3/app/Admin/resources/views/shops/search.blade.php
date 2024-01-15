<div class="form-group col-sm-1 pull-right">
<!-- <label for=""></label> -->
</div>

<div class="form-group col-sm-2 pull-right">
{!! Form::text('date', null, ['class' => 'form-control','id' => 'date', 'placeholder' => 'Registered Date', 'autocomplete'=> 'off']) !!}
</div>

<div class="form-group col-sm-2 pull-right">
    <!-- <label for="exampleFormControlSelect1">Sort By</label> -->
        <select class="form-control" id="filter-value-1">
        <option value=''>None</option>
        </select>
</div>


<div class="form-group col-sm-2 pull-right">
    <!-- <label for="exampleFormControlSelect1">Sort By</label> -->
        <select class="form-control" id="filter-value">
        <option value=''>None</option>
        </select>
</div>

<div class="form-group col-sm-2 pull-right">
    <!-- <label for="exampleFormControlSelect1">Sort By</label> -->
        <select class="form-control" id="filter-by">
        <option value="" disabled selected hidden>Sort By --</option>
        <option value=''>All</option>
        <option value='supplier'>Supplier</option>
        <option value='membership'>Membership</option>
        <option value='city'>City</option>
        <option value='status'>Status</option>
        <option value='date'>Date</option>
        </select>
</div>

<div class="form-group col-sm-3 pull-right">
<!-- <label>Search</label> -->
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Search']) !!}
</div>


@section('scripts')
    <script type="text/javascript">
        let membership = {!! $membership !!};
        let cities = {!! $city !!};
        let districts = {!! $districts !!};
        let status = [
            {'id': 0, 'name':'Pending'},
            {'id': 1, 'name':'Accepted'},
            {'id': 10, 'name':'Rejected'}
        ];
        // console.log(shops);
        
        let select_filterby = '';

       $("#filter-by").change(function() {
            select_filterby = $(this).val().toLowerCase();
            $('#filter-value').html('<option value="">None</option>');
            $('#filter-value-1').html('<option value="">None</option>');
            if(select_filterby == 'supplier') {
                $.each(membership, function(key, value) {
                        $('#filter-value')
                            .append($("<option></option>")
                                .attr("value",value.id)
                                .text(value.name)); 
                });
            } else if (select_filterby == 'membership') {
                $.each(membership, function(key, value) {
                        $('#filter-value')
                            .append($("<option></option>")
                                .attr("value",value.id)
                                .text(value.name)); 
                });
            } else if (select_filterby == 'city') {
                $.each(cities, function(key, value) {
                        $('#filter-value')
                            .append($("<option></option>")
                                .attr("value",value.id)
                                .text(value.default_name)); 
                });
            } else if (select_filterby == 'status') {
                $.each(status, function(key, value) {
                    console.log(status)
                        $('#filter-value')
                            .append($("<option></option>")
                                .attr("value", value.id)
                                .text(value.name)); 
                });
            } else {
                $('#filter-value').html('<option>None</option>');
            }
       });


       $("#filter-value").change(function() {
            var val = $(this).val().toLowerCase();
            if(select_filterby == 'city'){
                $('#filter-value-1').html('<option value="">None</option>');
                    districts.find( district => {
                    if(district.city_province_id == val ) {
                        // console.log(district)
                        $('#filter-value-1')
                            .append($("<option></option>")
                                .attr("value", district.id)
                                .text(district.default_name)); 
                    }
                });
            }  
       });

       $("#btn-search").click(function() {
           let filter_by = $('#filter-by').val();
           let filter_value = $('#filter-value').val();
           let filter_value_1 = $('#filter-value-1').val();

            $('<input>').attr({
                        type: 'hidden',
                        name: 'search_by',
                        value: filter_by
            }).appendTo('form')

            $('<input>').attr({
                        type: 'hidden',
                        name: 'filter_value',
                        value: filter_value
            }).appendTo('form')

            $('<input>').attr({
                        type: 'hidden',
                        name: 'filter_value_1',
                        value: filter_value_1
            }).appendTo('form')
       });
    </script>

@endsection
