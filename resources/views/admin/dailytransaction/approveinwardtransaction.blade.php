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
            <h1>Approve Inward Transaction</h1>
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
                <h3 class="card-title">Approve Inward Transaction</h3>
              </div>

              @if (Session::has('status'))
              <div class="alert alert-warning">
                {{Session::get('status')}}
              </div>
              @endif
              <!-- /.card-header -->
              <div class="card-body" style="overflow-x: auto">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Trans.No</th>
                    <th>Receiver Name</th>
                    <th>Receiver NRC/Passport</th>
                    <th>Receiver address/phone no</th>
                    <th>Purpose of transaction</th>
                    <th>Withdraw point</th>
                    <th>Sender Name</th>
                    <th>Sender NRC/Passport<</th>
                    <th>Sender Country</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Equivent USD</th>
                    <th>MMK Amount</th>
                    <th>MMK Allowance</th>
                    <th>Total MMK Amount</th>
                    <th>Exchange Rate</th>
                    <th>Exchange Rate USD</th>
                    <th>Txd_date_time</th>
                    <th>Update / Approve</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($inwardtransactions as $inwardtransaction)
                  <tr>
                    @if ($inwardtransaction->approve_status == 1)
                    <td>{{$increment}}  <span> </span><i class="fa fa-check-square" style="color: green"> </i></td>
                    @else
                    <td>{{$increment}}  <span> </span><i class="fa fa-check-square" style="color: red"></i></td>
                 @endif
                 <td>{{$inwardtransaction->sr_id}}</td>
                 <td>{{$inwardtransaction->receiver_name}}</td>
                 <td>{{$inwardtransaction->receiver_nrc_passport}}</td>
                 <td>{{$inwardtransaction->receiver_address_ph}}</td>
                 <td>{{$inwardtransaction->purpose}}</td>
                 <td>{{$inwardtransaction->withdraw_point}}</td>
                 <td>{{$inwardtransaction->sender_name}}</td>
                 <td>{{$inwardtransaction->sender_nrc_passport}}</td>
                 <td>{{$inwardtransaction->sender_country_code}}</td>
                 <td>{{$inwardtransaction->currency_code}}</td>
                 <td>{{$inwardtransaction->amount}}</td>
                 <td>{{$inwardtransaction->equivalent_usd}}</td>
                 <td>{{$inwardtransaction->amount_mmk/1000000}}</td>
                 <td>{{$inwardtransaction->mmk_allowance}}</td>
                 <td>{{$inwardtransaction->total_mmk_amount}}</td>
                 <td>{{$inwardtransaction->exchange_rate}}</td>
                 <td>{{$inwardtransaction->exchange_rate_usd}}</td>
                 <td>{{$inwardtransaction->txd_date_time}}</td>

                    <td>
                      @if ($inwardtransaction->status == 1)
                      <a href="{{url('/unapproveinward/'.$inwardtransaction->id)}}" class="btn btn-success">Unapprove</a>
                    @else
                      <a href="{{url('/approveinward/'.$inwardtransaction->id)}}" class="btn btn-danger">Approve</a>
                    @endif
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

@endsection
