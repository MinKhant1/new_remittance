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
            <h1>Total Inward/ Outward Transaction</h1>
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
                <h3 class="card-title">Monthly Statement of Inward and Outward Remittance</h3>
              </div>

              @if (Session::has('status'))
              <div class="alert alert-success">
                {{Session::get('status')}}
              </div>
              @endif
               {!!Form::open(['action' => 'App\Http\Controllers\OutwardTransactionController@totalinwardoutwardwithdate', 'method' => 'POST' , 'enctype' => 'multipart/form-data'])!!}
              <!-- /.card-header -->
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
                      <a href="{{url('/exportexcelinoutwardtotal')}}" class="btn btn-success" style="margin-left: 20px"><i class="nav-icon fas fa-print">  Print</i></a>
                </div>

                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th rowspan="2">SrNo</th>
                        <th rowspan="2">Date</th>
                        <th colspan="3" style="text-align: center">Total inwward Amount  </th>
                        <th colspan="3" style="text-align: center">Total Outward Amount</th>
                        <th colspan="3" style="text-align: center">Net Amount</th>
                    </tr>
                </th>

                    <th>Total No.of Trans</th>
                    <th>USD</th>
                    <th>MMK(in Million)</th>

                    <th>Total No.of Trans</th>
                    <th>USD</th>
                    <th>MMK(in Million)</th>

                    <th>Total No.of Trans</th>
                    <th>USD</th>
                    <th>MMK(in Million)</th>

                  </thead>
                  <tbody>
                    @php
                        $intrans = 0;
                        $inusd = 0;
                        $inmmk = 0;

                        $outtrans = 0;
                        $outusd = 0;
                        $outmmk = 0;

                        $nettrans = 0;
                        $netusd = 0;
                        $netmmk = 0;
                    @endphp
                    @if(request()->is('totalinwardoutward'))
                    <tr>
                      <td>{{$increment}}</td>
                      <td>{{$sd}}</td>
                      <td>{{$T_Inamount->icount??'0'}}</td>
                      <td>{{number_format($T_Inamount->itusd??'0',2)}}</td>
                      <td>{{number_format(($T_Inamount->itmmk??'0')/1000000,5)}}</td>

                      <td>{{$T_Outamount->ocount??'0'}}</td>
                      <td>{{number_format($T_Outamount->otusd??'0',2)}}</td>
                      <td>{{number_format(($T_Outamount->otmmk??'0')/1000000,5)}}</td>

                      <td>{{$Net_trans??'0'}}</td>
                      <td>{{number_format($Net_usd??'0',2)}}</td>
                      <td>{{number_format(($Net_mmk??'0')/1000000,5)}}</td>
                    </tr>
                  {{Form::hidden('', $increment = $increment + 1)}}
                    @endif

                    @if(request()->is('totalinwardoutwardwithdate'))

                    @foreach ($T_amount as $t_array)

                    <tr>
                        <td>{{$increment}}</td>
                        <td>{{$t_array['dates']??'0'}}</td>
                        <td>{{$t_array['icount']??'0'}}</td>
                        <td>{{number_format($t_array['itusd']??'0',2)}}</td>
                        <td>{{number_format(($t_array['itmmk']??'0')/1000000,5)}}</td>

                        <td>{{$t_array['ocount']??'0'}}</td>
                        <td>{{number_format($t_array['otusd']??'0',2)}}</td>
                        <td>{{number_format(($t_array['otmmk']??'0')/1000000,5)}}</td>

                        <td>{{ $t_array['netcount']??'0' }}</td>
                        <td>{{ number_format($t_array['netusd']??'0',2) }}</td>
                        <td>{{ number_format(($t_array['netmmk']??'0')/1000000,5) }}</td>
                    </tr>
                    @php
                        $intrans+=$t_array['icount'];
                        $inusd+= $t_array['itusd'] ;
                        $inmmk+=$t_array['itmmk'] /1000000;

                        $outtrans+=$t_array['ocount'];
                        $outusd+= $t_array['otusd'] ;
                        $outmmk+=$t_array['otmmk'] /1000000;

                        $nettrans+=$t_array['netcount'];
                        $netusd+= $t_array['netusd'] ;
                        $netmmk+=$t_array['netmmk'] /1000000;

                    @endphp

                    {{Form::hidden('', $increment = $increment + 1)}}
                    @endforeach
                    <tr>
                        <td colspan="2" style="font-weight: bold; text-align: center">Total</td>
                        <td style="font-weight: bold">{{$intrans}}</td>
                        <td style="font-weight: bold">{{number_format($inusd,2)}}</td>
                        <td style="font-weight: bold">{{number_format($inmmk,5)}}</td>

                        <td style="font-weight: bold">{{$outtrans}}</td>
                        <td style="font-weight: bold">{{number_format($outusd,2)}}</td>
                        <td style="font-weight: bold">{{number_format($outmmk,5)}}</td>

                        <td style="font-weight: bold">{{$nettrans}}</td>
                        <td style="font-weight: bold">{{number_format($netusd,2)}}</td>
                        <td style="font-weight: bold">{{number_format($netmmk,5)}}</td>

                    </tr>
                    @endif
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
