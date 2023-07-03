@extends('admin_layout.admin')


@section('content')
{{Form::hidden('', $increment = 1)}}
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 style="margin-bottom: 20px">Customer Detail</h1>
            <h5 style="font-size: 20px" class="text-bold">Name: <span style="font-weight: 400">Gway Si</span></h5>
            <h5 class="text-bold">NRC: <span style="font-weight: 400">Gway Si</span></h5>
            <h5 class="text-bold">Total No of Outward Transactions: <span style="font-weight: 400">Gway Si</span></h5>
          </div>

        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">


        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Outward Transactions</h3>
              </div>

            

              
              <!-- /.card-header -->
              <div class="card-body" style="overflow-x: auto">
              

                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Approve Status</th>
                      <th>Sender Name</th>
                      <th>Sender address/phone no</th>
                      <th>Sender NRC/Passport</th>
                      <th>Purpose of transaction</th>
                      <th>Deposit point</th>
                      <th>Receiver Name</th>
                      <th>Receiver Country</th>
                      <th>MMK Amount</th>
                      <th>Equivent USD</th>     
                  </tr>
                  </thead>
                  <tbody>
                  {{-- @foreach ($inward_transactions as $inwardtransaction)
                
          
                  <tr>
   
                  
                 
                    <td>{{$increment}}</td>
                 
                    <td>{{$inwardtransaction->branch_id}}</td>
                    <td>{{$inwardtransaction->receiver_name}}</td>
                    <td>{{$inwardtransaction->receiver_nrc_passport}}</td>
                    <td>{{$inwardtransaction->receiver_address_ph}}</td>
                    <td>{{$inwardtransaction->purpose}}</td>
                    <td>{{$inwardtransaction->withdraw_point}}</td>
                    <td>{{$inwardtransaction->sender_name}}</td>
                    <td class="nrc_passport">{{$inwardtransaction->sender_nrc_passport}}</td>
                    <td>{{$inwardtransaction->sender_country_code}}</td>
                    <td>{{$inwardtransaction->currency_code}}</td>
                    <td>{{number_format($inwardtransaction->amount,2)}}</td>
                    <td>{{number_format($inwardtransaction->equivalent_usd,2)}}</td>
                    <td>{{number_format($inwardtransaction->amount_mmk,2)}}</td>
                    <td>{{$inwardtransaction->exchange_rate}}</td>
                    <td>{{$inwardtransaction->exchange_rate_usd}}</td>
                    <td>{{$inwardtransaction->txd_date_time}}</td>
                    
                     
                     
                  </tr>
                  {{Form::hidden('', $increment = $increment + 1)}}
                  @endforeach --}}
                  </tbody>
                  <tfoot>
                
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @endsection

  @section('style')

  <link rel="stylesheet" href="backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">

  @endsection

@section('scripts')

<!-- DataTables -->
<script src="backend/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="backend/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="asset{{'backend/dist/js/adminlte.min.js'}}"></script>

<script src="backend/dist/js/bootbox.min.js"></script>
<!-- page script -->

<script>
  $(document).on("click", "#delete", function(e){
  e.preventDefault();
  var link = $(this).attr("href");
  bootbox.confirm("Do you really want to delete this element ?", function(confirmed){
    if (confirmed){
        window.location.href = link;
      };
    });
  });
</script>
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": false,
      "autoWidth": false,
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

{{-- <script>
  nrc_inward =  document.querySelectorAll('.nrc_passport');
  // nrc_inward.forEach(element => {
    
  // });
  //console.log(nrc_inward);
  nrc_blacklist= @json($blacklists);
  nrc_blacklist.forEach(blacklist => {
     nrc_inward.forEach(inward => {
  //    console.log(inward.parentNode);
      if(inward.innerText == blacklist.nrc_passportno)
      {
        inward.parentNode.style.backgroundColor = 'rgba(255, 133, 133, 0.4)';
      }
  });
  });
</script> --}}

@endsection
