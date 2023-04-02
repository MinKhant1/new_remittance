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
            <h1>Inward Transaction Report</h1>
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
              {!!Form::open(['action' => 'App\Http\Controllers\InwardTransactionController@searchinward', 'method' => 'POST' , 'enctype' => 'multipart/form-data'])!!}
              {{ csrf_field() }}
              <div class="card-body" style="overflow-x: scroll">
                <div class="grid" style="display: flex">
                    <div class="col-1-4 col-1-4-sm" style="padding-right: 5%">
                        <div class="controls">
                          <label for="arrive" style="padding-right: 20px" class="label-date">&nbsp;&nbsp;Start Date</label>
                          <input type="date" style="margin-right: 40px" id="arrive" class="floatLabel" name="startdate" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                      </div>
                      <div class="col-1-4 col-1-4-sm">
                        <div class="controls">
                          <label for="arrive" style="padding-right: 20px" class="label-date">&nbsp;&nbsp;End Date</label>
                          <input type="date" style="margin-right: 80px" id="arrive" class="floatLabel" name="enddate" value="<?php echo date('Y-m-d'); ?>">
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
                      {!!Form::submit('Search', ['class' => 'btn btn-success','id'=>'search'])!!}
                      {!!Form::close()!!}
                    </div>

                    <div style="margin-bottom:3%">
                      <a href="{{url('/exportexcelinward')}}" class="btn btn-success" style="margin-left: 20px" ><i class="nav-icon fas fa-print">  Print</i></a>
                    </div>
                </div>



                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Branch ID</th>
                    <th>Receiver Name</th>
                    <th>Receiver NRC/Passport</th>
                    <th>Receiver address/Phone no</th>
                    <th>Purpose of transaction</th>
                    <th>Withdraw point</th>
                    <th>Sender Name</th>
                    <th>Sender Country</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Equivent USD</th>
                    <th>MMK Amount</th>
                    <th>Exchange rate</th>
                    <th>Exchange rate USD</th>
                    <th>Txd Date Time</th>
                    {{-- <th>Created_at</th> --}}

                  </tr>
                  </thead>
                  <tbody>
                    @php

                    $last_date= Carbon\Carbon::now();
                        // $last_date=new  \Carbon\Carbon('1900-01-23');
                        $isNotFirst=false;
                        $mmkAmount=0;
                        $amount=0;
                        $subtotal_amount_array=array();
                        $usdAmount=0;
                        $totalAmount=0;
                        $totalMMKAmount=0;
                        $totalUSDAmount=0;

                    @endphp



                    @foreach ($inward_transactions as $transacton=>$collection)

                    @php
                        $index=0;
                    @endphp
                    @foreach ($collection as $inwardtransaction)

                    @if (++$index<count($collection))

                    <tr>

                      <td>{{$increment}}</td>
                      {{Form::hidden('', $increment = $increment + 1)}}
                      <td>{{$inwardtransaction->branch_id}}</td>
                      <td>{{$inwardtransaction->receiver_name}}</td>
                      <td>{{$inwardtransaction->receiver_nrc_passport}}</td>
                      <td>{{$inwardtransaction->receiver_address_ph}}</td>
                      <td>{{$inwardtransaction->purpose}}</td>
                      <td>{{$inwardtransaction->withdraw_point}}</td>
                      <td>{{$inwardtransaction->sender_name}}</td>
                      <td>{{$inwardtransaction->sender_country_code}}</td>
                      <td>{{$inwardtransaction->currency_code}}</td>
                      <td>{{number_format($inwardtransaction->amount,2)}}</td>
                      <td>{{number_format($inwardtransaction->equivalent_usd,2)}}</td>
                      <td>{{number_format($inwardtransaction->amount_mmk,2)}}</td>
                      <td>{{number_format($inwardtransaction->amount_mmk/$inwardtransaction->amount,2)}}</td>
                      <td>{{number_format($inwardtransaction->amount_mmk/$inwardtransaction->equivalent_usd,2)}}</td>
                      <td>{{$inwardtransaction->txd_date_time}}</td>


                    </tr>
                    @else

                      @foreach ($inwardtransaction as $key => $value)
                      <tr>


                             <td colspan="9" style="font-weight: bold; text-align: right">Sub Total</td>
                             <td style="font-weight: bold">{{$key}}</td>
                             <td style="font-weight: bold">{{number_format($value['amount'],2)}}</td>
                             <td style="font-weight: bold">{{number_format($value['equivalent_usd'],2)}}</td>
                             <td colspan="4" style="font-weight: bold">{{number_format($value['amount_mmk'],2)}}</td>

                        </tr>
                         @endforeach

                         @endif
                         @endforeach



                @endforeach

                <tr><td colspan="16"></td></tr>

                @foreach ($total_collection as $key=>$total)



                           <tr>
                             <td colspan="9"  style="font-weight: bold; text-align: right">Grand Total</td>

                             <td style="font-weight: bold">{{$key}}</td>
                             <td style="font-weight: bold">{{number_format($total['amount'],2)}}</td>
                             <td style="font-weight: bold">{{number_format($total['equivalent_usd'],2)}}</td>
                             <td colspan="4" style="font-weight: bold">{{number_format($total['amount_mmk'],2)}}</td>


                           </tr>
                           @endforeach
                           <tr>
                            <td colspan="9" style="font-weight: bold; text-align: right">Grand Total</td>
                            <td></td>
                            <td></td>
                             <td style="font-weight: bold">{{number_format($ttlusd,2)}}</td>
                             <td colspan="4" style="font-weight: bold">{{number_format($ttlmmk,2)}}</td>
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
      {{ $inward_transactions->links() }}
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
        "paging": false,
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

  </script>

@endsection
