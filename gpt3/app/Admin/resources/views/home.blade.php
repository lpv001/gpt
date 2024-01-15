@extends('admin::layouts.app')

@section('content')
  <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3>Codes</h3>

                <p>Total Codes 
                  <span>: 
                      <b>{{ @$total_orders_today ?? 0 }}</b>
                  </span>
                </p> 
              </div>  
              <div class="icon">
                <i class="ion prime-sort-alpha-down"></i>
              </div>
              <a href="{{ route('codes.index') }}" class="small-box-footer">Show More <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3>Formats<sup style="font-size: 20px"></sup></h3>

                <p>Total Formats : <span><b>{{@$total_shops_today ?? 0}}</b></span></p>
              </div>
              <div class="icon">
                <i class="ion"></i>
              </div>
                <a href="{{ route('codes.index')}}" class="small-box-footer">Show More <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3>{{ @$total_users ?? 0}}</h3>

                <p>New User <span><b>{{ @$total_users_today ?? 0}}</b></span></p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="{{ route('users.index')}}" class="small-box-footer">Show Users <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3>{{ @$total_products ?? 0}}</h3>

                  <p>New Data <span><b>{{ @$total_product_today ?? 0}}</b></span></p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        
      </section>
@endsection

@section('scripts')
<script>
  var ctx = document.getElementById('myChart');
  var myChart = new Chart(ctx, {
      // type: 'bar',
      type: 'line',
      data: {
          labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
          datasets: [{
              // label: '# of Votes',
              title: {
                  display: true,
                  text: 'Custom Chart Title'
              },
              data: [12, 19, 3, 5, 2, 3],
              backgroundColor: [
                  '#3c8dbc',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)'
              ],
              borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)'
              ],
              borderWidth: 1
          }]
      },
      options: {
          scales: {
              y: {
                  beginAtZero: true
              }
          }
      }
  });
  </script>
@endsection
