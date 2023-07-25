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
            <h1>Outward Transaction</h1>
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
                <h3 class="card-title">Outward Transaction</h3>
              </div>
              <div class="col-12" style="margin: 1% -1% 0%">
                <a href="{{url('/addoutwardtransaction')}}" class="btn btn-primary float-right">Add New Transaction</a>
              </div>

              @if (Session::has('status'))
              <div class="alert alert-success">
                {{Session::get('status')}}
              </div>
              @endif
              <!-- /.card-header -->
              <div class="card-body" style="overflow-x: auto">
                {!!Form::open(['action' => 'App\Http\Controllers\OutwardTransactionController@outwardtransactionwithdate', 'method' => 'POST' , 'enctype' => 'multipart/form-data'])!!}
                {{ csrf_field() }}
                <div class="card-body" style="overflow-x: scroll">
                  <div class="grid" style="display: flex">
                      <div class="col-1-4 col-1-4-sm" style="padding-right: 5%">
                          <div class="controls">
                            <label for="arrive" class="label-date" style="padding-right: 20px">&nbsp;&nbsp;Start Date</label> 
                            <input type="date" id="arrive" style="margin-right: 40px" class="floatLabel" name="startdate" value="<?php echo date('Y-m-d'); ?>">
                          </div>
                        </div>
                        <div class="col-1-4 col-1-4-sm">
                          <div class="controls">
                            <label for="arrive" class="label-date" style="padding-right: 20px">&nbsp;&nbsp;End Date</label>
                            <input type="date" id="arrive" style="margin-right: 80px" class="floatLabel" name="enddate" value="<?php echo date('Y-m-d'); ?>">
                          </div>
                        </div>
                        {!!Form::submit('Search', ['class' => 'btn btn-success'])!!}
                        {!!Form::close()!!}
                  </div>
                  
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
                    <th>File</th>
                    <th>Update</th>
                    <th>Delete</th>                
                    <th>Print</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($outwardtransactions as $transaction)
                  <tr>
                    
                      <td>{{$increment}}</td>
                      @if ($transaction->status == 1)
                      <td><i class="fa fa-check-square" style="color: green"> </i></td>
                     @else
                      <td><i class="fa fa-check-square" style="color: red"></i></td>
                   @endif
                      <td>{{$transaction->sender_name}}</td>
                      <td>{{$transaction->sender_address_ph}}</td>
                      <td class="nrc_passport">{{$transaction->sender_nrc_passport}}</td>
                      <td>{{$transaction->purpose}}</td>
                      <td>{{$transaction->deposit_point}}</td>
                      <td>{{$transaction->receiver_name}}</td>
                      <td>{{$transaction->receiver_country_code}}</td>
                      <td>{{$transaction->amount_mmk}}</td>
                      <td>{{$transaction->equivalent_usd}}</td>
                      <td> 
                        {{-- @if ($transaction->file != 'Nofile.jpg') --}}
                        @if ($transaction->file != 'Nofile.jpg' && isset($transaction->file) ) 
                    
                        <a href="{{url('/download/'. $transaction->file)}}" class="btn btn-warning" ><i class="nav-icon fas fa-print"></i>
                        </a></td>
                      
                        @else                     
               
                      
                      @endif  
  
                     
                    <td>
                      @if ($transaction->status==0)
                      <a href="{{url('/editoutwardtransaction/' . $transaction->id)}}" class="btn btn-primary"><i class="nav-icon fas fa-edit"></i></a></td>
                      @else
                          
                      @endif
                     
                    <td>
                      @if ($transaction->status==0)
                      <a href="{{url('/deleteoutwardtransaction/'. $transaction->id)}}" id="delete" class="btn btn-danger" ><i class="nav-icon fas fa-trash"></i></a>
                      @else
                          
                      @endif
                    </td>
                    <td>
                      <a href="{{url('/viewpdfoutward/'. $transaction->id)}}" id="viewpdf" class="btn btn-warning" ><i class="nav-icon fas fa-print"></i></a>
                    </td>
                  </tr>
                  {{Form::hidden('', $increment = $increment + 1)}}  
                  @endforeach                     
                  </tbody>
                  <tfoot>
                  {{-- <tr>
                    <th>Num.</th>
                    <th>Country Code</th>
                    <th>Country Name</th>
                    <th>Update</th>
                    <th>Delete</th>
                  </tr> --}}
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

<script>
  nrc_outward =  document.querySelectorAll('.nrc_passport');
  // nrc_inward.forEach(element => {
    
  // });
  //console.log(nrc_inward);
  nrc_blacklist= @json($blacklists);
  nrc_blacklist.forEach(blacklist => {
    nrc_outward.forEach(outward => {
  //    console.log(inward.parentNode);
      if(outward.innerText == blacklist.nrc_passportno)
      {
        outward.parentNode.style.backgroundColor = 'rgba(255, 133, 133, 0.4)';
      }
  });
  });
</script>

@endsection