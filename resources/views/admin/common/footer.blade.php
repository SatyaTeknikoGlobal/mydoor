 <footer class="footer">
    <span class="text-right">                
        Copyright <a target="_blank" href="#">Tekniko Global</a>
    </span>
    <span class="float-right">
     
        Powered by <a target="_blank" href="https://teknikoglobal.com/"><b>Tekniko Global</b></a>
    </span>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="{{asset('public/assets/js/modernizr.min.js')}}"></script>
<script src="{{asset('public/assets/js/jquery.min.js')}}"></script>
<script src="{{asset('public/assets/js/moment.min.js')}}"></script>

<script src="{{asset('public/assets/js/popper.min.js')}}"></script>
<script src="{{asset('public/assets/js/bootstrap.min.js')}}"></script>

<script src="{{asset('public/assets/js/detect.js')}}"></script>
<script src="{{asset('public/assets/js/fastclick.js')}}"></script>
<script src="{{asset('public/assets/js/jquery.blockUI.js')}}"></script>
<script src="{{asset('public/assets/js/jquery.nicescroll.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>

<!-- App js -->
<script src="{{asset('public/assets/js/admin.js')}}"></script>
<script src="{{asset('public/assets/plugins/select2/js/select2.min.js')}}"></script>

</div>
<!-- END main -->

<!-- BEGIN Java Script for this page -->
<script src="{{asset('public/assets/plugins/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/datatables/datatables.min.js')}}"></script>

<!-- Counter-Up-->
<script src="{{asset('public/assets/plugins/waypoints/lib/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/counterup/jquery.counterup.min.js')}}"></script>



<!-- Charts data -->
<script src="{{asset('public/assets/data/data_charts_dashboard.js')}}"></script>






<!-- END Java Script for this page -->

</body>


<!-- Mirrored from demo.bootstrap24.com/nura-admin-4/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 09 Aug 2021 06:13:39 GMT -->
</html>
<script type="text/javascript">
    $(document).ready(function(){
        $('#dataTable').DataTable();
    });
</script>

<script type="text/javascript">
    $( "#delete_item" ).click(function() {
      alert( "Handler for .click() called." );
  });
</script>
 <script>
        $(document).on('ready',function() {
            $('.select2').select2();
        });
    </script>

    <script>
var ctx = document.getElementById('resultChat').getContext('2d');

var right = '{{$right ?? 0}}';
var wrong = '{{$wrong ?? 0}}';


var resultChat = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Wrong', 'Right'],
        datasets: [{
            label: '# Results',
            data: [wrong, right,],
            backgroundColor: [
                'rgba(255,0,0)',
                'rgba(0,128,0)',
            ],
            borderColor: [
                'rgba(255,0,0)',
                'rgba(0,128,0)',
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