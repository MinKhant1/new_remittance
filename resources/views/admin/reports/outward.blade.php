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
            <h1>Outward Transaction Report</h1>
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
                <h3 class="card-title">Daily Transaction Detail</h3>
              </div>

              @if (Session::has('status'))
              <div class="alert alert-success">
                {{Session::get('status')}}
              </div>
              @endif
              <!-- /.card-header -->
              {!!Form::open(['action' => 'App\Http\Controllers\OutwardTransactionController@searchoutward', 'method' => 'POST' , 'enctype' => 'multipart/form-data'])!!}
              {{ csrf_field() }}
              <div class="card-body" style="overflow-x: scroll">
                <div class="grid" style="display: flex">
                    <div class="col-1-4 col-1-4-sm" style="padding-right: 5%">
                        <div class="controls">
                          <label for="arrive" style="padding-right: 20px" class="label-date">&nbsp;&nbsp;Start Date</label>
                          <input type="date" id="arrive" style="margin-right: 40px" class="floatLabel" name="startdate" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                      </div>
                      <div class="col-1-4 col-1-4-sm">
                        <div class="controls">
                          <label for="arrive" style="padding-right: 20px" class="label-date">&nbsp;&nbsp;End Date</label>
                          <input type="date" id="arrive" style="margin-right: 80px" class="floatLabel" name="enddate" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                      </div>

                      <div class="col-3" style="display: flex ">
                        <label for="exampleFormControlSelect1">Select Branch</label>
                      <select class="form-control" id="exampleFormControlSelect1"  name="branch_id">
                        <option value=""></option>
                        @foreach ($branches as $branch)
                        <option value="{{$branch->branch_code}}">{{$branch->branch_name}}</option>
                        @endforeach
                      </select>

                      </div>

                      <div class="col-3" style="display: flex ">
                        <label for="state_division" class="mr-sm-2">State & Divison:</label>
                        <select class="form-control" id="state_division" name="state_division" >
                          <option value=""></option>
                          <option value="Ayeyarwady Region">Ayeyarwady Region</option>
                          <option value="Bago Region">Bago Region</option>
                          <option value="Chin State">Chin State</option>
                          <option value="Kachin State">Kachin State</option>
                          <option value="Kayah State">Kayah State</option>
                          <option value="Kayin State">Kayin State</option>
                          <option value="Magway Region">Magway Region</option>
                          <option value="Mandalay Region">Mandalay Region</option>
                          <option value="Mon State">Mon State</option>
                          <option value="Naypyidaw Union Territory">Naypyidaw Union Territory</option>
                          <option value="Rakhine State">Rakhine State</option>
                          <option value="Sagaing Region">Sagaing Region</option>
                          <option value="Shan State">Shan State</option>
                          <option value="Tanintharyi Region">Tanintharyi Region</option>
                          <option value="Yangon Region">Yangon Region</option>

                        </select>
                      </div>
                      <div style="margin-bottom:3%">
                      {!!Form::submit('Search', ['class' => 'btn btn-success'])!!}
                      {!!Form::close()!!}
                    </div>
                    <div style="margin-bottom:3%">
                      <a href="{{url('/exportexceloutward')}}" class="btn btn-success" style="margin-left: 20px"><i class="nav-icon fas fa-print"> Print</i></a>
                    </div>
                </div>



                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Sender Name</th>
                    <th>Sender address/phone no</th>
                    <th>Sender NRC/Passport</th>
                    <th>Purpose of transaction</th>
                    <th>Deposit point</th>
                    <th>Receiver Name</th>
                    <th>Receiver Country</th>
                    <th>MMK Amount</th>
                    <th>Equivent USD</th>
                    <th>Dates</th>



                  </tr>
                  </thead>
                  <tbody>
                    @php

                $last_date= Carbon\Carbon::now();
                  

                @endphp
                  @foreach ($outwardtransactions as $dated_transaction=>$collection)
                  @php
                        $index=0;
                    @endphp
                       @foreach ($collection as $transaction)

                       @if (++$index<count($collection))
                  <tr>

                    <td>{{$increment}}</td>
                    <td>{{$transaction->sender_name}}</td>
                    <td>{{$transaction->sender_address_ph}}</td>
                    <td>{{$transaction->sender_nrc_passport}}</td>
                    <td>{{$transaction->purpose}}</td>
                    <td>{{$transaction->deposit_point}}</td>
                    <td>{{$transaction->receiver_name}}</td>
                    <td>{{$transaction->receiver_country_code}}</td>
                    <td>{{number_format($transaction->amount_mmk,2)}}</td>
                    <td>{{number_format($transaction->equivalent_usd,2)}}</td>
                    <td>{{$transaction->txd_date_time}}</td>
                  </tr>
                  @else
               
                      <tr>
                        <td colspan="8" style="font-weight: bold; text-align: right">SubTotal</td>
                        <td style="font-weight: bold">{{$transaction['mmk']}}</td>
                        <td style="font-weight: bold">{{$transaction['usd']}}</td>
                      </tr>
                   
                      @endif

        
            
                  {{Form::hidden('', $increment = $increment + 1)}}
                  @endforeach
                  @endforeach
                  <tr><td colspan="11"></td></tr>
                  <tr>
                    <td colspan="8" style="font-weight: bold; text-align: right">Total</td>
                    <td style="font-weight: bold">{{number_format($grandtotalmmk,2)}}</td>
                    <td colspan="2" style="font-weight: bold">{{number_format($grandtotalusd,2)}}</td>
                  </tr>
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
<script src="asset{{'backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js'}}"></script>
<!-- AdminLTE App -->
<script src="asset{{'backend/dist/js/adminlte.min.js'}}"></script>

<script src="asset{{'backend/dist/js/bootbox.min.js'}}"></script>
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
<script>a
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
