
@include('admin.common.header')
 <div class="content-page">

            <!-- Start content -->
            <div class="content">

                <div class="container-fluid">

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="breadcrumb-holder">
                                <h1 class="main-title float-left">Dashboard</h1>
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item">Home</li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                                <div class="clearfix"></div>
                            </div>
                            

                        </div>
                    </div>
                    <!-- end row -->


                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                            <div class="card-box noradius noborder bg-danger">
                                <i class="far fa-user float-right text-white"></i>
                                <a href="{{route('admin.flatowners.index')}}"><h6 class="text-white text-uppercase m-b-20">All Approved Users</h6></a>
                                <h1 class="m-b-20 text-white counter">{{$usercount ?? 0}}</h1>
                               
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                            <div class="card-box noradius noborder bg-purple">
                                <i class="fa fa-home float-right text-white"></i>
                               <a href="{{route('admin.society.index')}}"> <h6 class="text-white text-uppercase m-b-20">All Approved Society</h6></a>
                                <h1 class="m-b-20 text-white counter">{{$societiescount ?? 0}}</h1>
                              
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                            <div class="card-box noradius noborder bg-warning">
                                <i class="fa fa-comments float-right text-white"></i>
                                <a href="{{route('admin.complaints.index')}}"><h6 class="text-white text-uppercase m-b-20">Open Complaints</h6></a>
                                <h1 class="m-b-20 text-white counter">{{$complaints ?? 0}}</h1>
                              
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                            <div class="card-box noradius noborder bg-info">
                                <i class="fa fa-inr float-right text-white"></i>
                                <h6 class="text-white text-uppercase m-b-20">Society Revenue({{date('Y')}})</h6>
                                <h1 class="m-b-20 text-white counter">{{$society_revenue  ?? 0}}</h1>
                               
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                      <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar"></i>Society Revenue
                                </div>

                                <div class="card-body">
                                    <canvas id="barChart"></canvas>
                                </div>
                            </div>
                            <!-- end card-->
                        </div>

                    </div>




                     <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar"></i>Society Users
                                </div>

                                <div class="card-body">
                                    <canvas id="barChartUser"></canvas>
                                </div>
                            </div>
                            <!-- end card-->
                        </div>

                    </div>













                </div>
                <!-- END container-fluid -->

            </div>
            <!-- END content -->

        </div>
        <!-- END content-page -->




@include('admin.common.footer')

<script type="text/javascript">
    
// barChart
// function get_chart_data(){
    var ctx_bar_chart = document.getElementById("barChart").getContext('2d');
var barChart = new Chart(ctx_bar_chart, {
        type: 'bar',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: 'Amount received({{date('Y')}})',
                data: [{{$jan_revenue}}, {{$feb_revenue}}, {{$mar_revenue}}, {{$apr_revenue}}, {{$may_revenue}}, {{$june_revenue}}, {{$july_revenue}}, {{$aug_revenue}}, {{$sep_revenue}}, {{$oct_revenue}}, {{$nov_revenue}}, {{$dec_revenue}}],


                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'               
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
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
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
});





 var ctx_bar_chart = document.getElementById("barChartUser").getContext('2d');
var barChart = new Chart(ctx_bar_chart, {
        type: 'bar',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: 'Registered User({{date('Y')}})',
                data: [{{$jan_user}}, {{$feb_user}}, {{$mar_user}}, {{$apr_user}}, {{$may_user}}, {{$june_user}}, {{$july_user}}, {{$aug_user}}, {{$sep_user}}, {{$oct_user}}, {{$nov_user}}, {{$dec_user}}],


                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'               
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
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
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
});











// }
</script>