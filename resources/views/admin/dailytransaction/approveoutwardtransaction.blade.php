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
            <h1>Approve Outward Transaction</h1>
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
                <h3 class="card-title">Approve Outward Transaction</h3>
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
                    <th>Num</th>
                    <th>Trans.No</th>
                    <th>Sender Name</th>
                    <th>Sender address/phone no</th>
                    <th>Sender NRC/Passport</th>
                    <th>Purpose of transaction</th>
                    <th>Deposit point</th>
                    <th>Receiver Name</th>
                    <th>Receiver Country</th>
                    <th>MMK Amount</th>
                    <th>Equivent USD</th>
                    

                    <th>Approve</th>

                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($outwardtransactions as $transaction)
                  <tr>
                      <td>{{$increment}}</td>
                   @if ($transaction->approve_status == 1)
                      <td>{{$transaction->tran_no}}<span> </span><i class="fa fa-check-square" style="color: green"> </i></td>
                     @else
                      <td>{{$transaction->tran_no}}<span> </span><i class="fa fa-check-square" style="color: red"></i></td>
                   @endif
                      <td>{{$transaction->sender_name}}</td>
                      <td>{{$transaction->sender_address_ph}}</td>
                      {{-- <td>{{$transaction->receiver_phno}}</td> --}}
                      <td>{{$transaction->sender_nrc_passport}}</td>
                      <td>{{$transaction->purpose}}</td>
                      <td>{{$transaction->deposit_point}}</td>
                      <td>{{$transaction->receiver_name}}</td>
                      <td>{{$transaction->receiver_country_code}}</td>
                      {{-- <td>{{$transaction->currency}}</td> --}}
                      <td>{{$transaction->amount_mmk}}</td>
                      <td>{{$transaction->equivalent_usd}}</td>
                      <td>
                        @if ($transaction->approve_status == 1)
                        <a href="{{url('/unapproveoutward/'.$transaction->id)}}" class="btn btn-success">Unapprove</a>
                      @else
                        <a href="{{url('/approveoutward/'.$transaction->id)}}" class="btn btn-danger">Approve</a>
                      @endif
                      </td>

                      {{-- <td>{{$transaction->created_at->toDateString()}}</td> --}}

                    {{-- {<td>{{$transaction->country_code}}</td>
                    <td>{{$transaction->country_name}}</td> --}}
                    {{-- <td>
                      <a href="{{url('/editcountry/' . $country->id)}}" class="btn btn-primary"><i class="nav-icon fas fa-edit"></i></a></td>
                    <td>
                      <a href="{{url('/deletecountry/'. $country->id)}}" id="delete" class="btn btn-danger" ><i class="nav-icon fas fa-trash"></i></a>
                    </td> --}}
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
      "responsive": false,

    });
  });
</script>

@endsection
