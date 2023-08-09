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
            <h1>Total Inward Transaction Report</h1>
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
                <h3 class="card-title">Monthly Statement of Inward Remittance</h3>
              </div>

              @if (Session::has('status'))
              <div class="alert alert-success">
                {{Session::get('status')}}
              </div>
              @endif
              <!-- /.card-header -->
              {!!Form::open(['action' => 'App\Http\Controllers\InwardTransactionController@totalinwardwithdate', 'method' => 'POST' , 'enctype' => 'multipart/form-data'])!!}
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

                      {!!Form::submit('Search', ['class' => 'btn btn-success'])!!}
                      {!!Form::close()!!}
                      <a href="{{url('/exportexcelinwardtotal')}}" class="btn btn-success" style="margin-left: 20px"><i class="nav-icon fas fa-print">  Print</i></a>
                </div>

                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th rowspan="2">SrNo</th>
                    <th rowspan="2">Date</th>
                    <th rowspan="2">USD</th>
                    <th rowspan="2">EUR</th>
                    <th rowspan="2">JPY</th>
                    <th rowspan="2">KRW</th>
                    <th rowspan="2">MYR</th>
                    <th rowspan="2">SGD</th>
                    <th rowspan="2">THB</th>
                    <th rowspan="2">AED</th>
                    <th rowspan="2">QAR</th>
                    <th rowspan="2">Others Country</th>
                    <th rowspan="2" >Total No.of Trans</th>
                    <th colspan="4">Total Inward Remittance Amount
                    <th colspan="4">Total Inward Remittance Amount From the date of Starting from the Business
                    </tr>
                    </th>


                    <th>USD</th>
                    <th>MMK(in Million)</th>
                    @if ($is_text30_valid)
                    <th>MMK Allowance</th>
                    <th>Total MMK Amount</th>
                    @endif
                  
                    <th>USD</th>
                    <th>MMK(in Million)</th>
                    @if ($is_text30_valid)
                    <th>MMK Allowance</th>
                    <th>Total MMK Amount</th>
                    @endif
                  
                  </thead>
                  <tbody>

                    @php
                        $tusd=0;
                        $mmk=0;
                        $tbusd=0;
                        $tbmmk=0;
                        $count = 0;
                    @endphp

                   @if(request()->is('totalinward'))
                  <tr>
                    <td>{{ $increment }}</td>
                    <td>{{ $sd }}</td>
                    <td>{{ number_format($usd_amounts,2) }}</td>
                    <td>{{ number_format($eur_amounts,2) }}</td>
                    <td>{{ number_format($jpy_amounts,2) }}</td>
                    <td>{{ number_format($krw_amounts,2) }}</td>
                    <td>{{ number_format($myr_amounts,2) }}</td>
                    <td>{{ number_format($sgd_amounts,2) }}</td>
                    <td>{{ number_format($thb_amounts,2) }}</td>
                    <td>{{ number_format($aed_amounts,2) }}</td>
                    <td>{{ number_format($qar_amounts,2) }}</td>
                    <td>{{ number_format($other_amount,2) }}</td>
                    <td>{{ $total_num_trans }}</td>
                    @if($T_amount != null)
                    <td>{{ number_format($T_amount->tusd,2) }}</td>
                    <td>{{ number_format($T_amount->tmmk /1000000,10) }}</td>
                    @else
                    <td>0</td>
                    <td>0</td>
                    @endif

                    @if ($is_text30_valid)
                        @if (!empty($T_amount->t_mmk_allowance))
                        <td>{{ number_format($T_amount->t_mmk_allowance,2) }}</td> 
                        @else
                        <td>0</td>
                        @endif
                        @if (!empty($T_amount->t_mmk_amount))
                        <td>{{ number_format($T_amount->t_mmk_amount /1000000,10) }}</td>
                        @else
                        <td>0</td>
                        @endif
                    @endif
                    <td>{{ number_format($Tb_amount[0]->tbusd,2) }}</td>
                    <td>{{ number_format($Tb_amount[0]->tbmmk /1000000,10 )}}</td>

                    @if ($is_text30_valid)
                    @if (!empty($Tb_amount[0]->tb_mmk_allowance))
                    <td>{{ number_format($Tb_amount[0]->tb_mmk_allowance,2) }}</td> 
                    @else
                    <td>0</td>
                    @endif
                    @if (!empty($Tb_amount[0]->tb_mmk_amount))
                    <td>{{ number_format($Tb_amount[0]->tb_mmk_amount /1000000,10) }}</td>
                    @else
                    <td>0</td>
                    @endif
              
                    @endif
             
                  </tr>
                  @endif

                  @if(request()->is('totalinwardwithdate'))
                  @foreach ($dategp_array  as $item)
                  <tr>
                    <td>{{ $increment }}</td>
                    <td>{{ $item['dates'] }}</td>
                    @if (array_key_exists('usd_amounts',$item))

                    <td>{{ number_format($item['usd_amounts'],2)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if (array_key_exists('eur_amounts',$item))

                    <td>{{ number_format($item['eur_amounts'],2)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if (array_key_exists('jpy_amounts',$item))

                    <td>{{ number_format($item['jpy_amounts'],2)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if (array_key_exists('krw_amounts',$item))

                    <td>{{ number_format($item['krw_amounts'],2)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if (array_key_exists('myr_amounts',$item))

                    <td>{{ number_format($item['myr_amounts'],2)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if (array_key_exists('sgd_amounts',$item))

                    <td>{{ number_format($item['sgd_amounts'],2)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if (array_key_exists('thb_amounts',$item))

                    <td>{{ number_format($item['thb_amounts'],2)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if (array_key_exists('aed_amounts',$item))

                    <td>{{ number_format($item['aed_amounts'],2)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if (array_key_exists('qar_amounts',$item))

                    <td>{{ number_format($item['qar_amounts'],2)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if (array_key_exists('other_amounts',$item))

                    <td>{{ number_format($item['other_amounts'],2)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    <td>{{ $item['count'] }}</td>
                    @if (array_key_exists('tusd',$item))

                    <td>{{ number_format($item['tusd'],2)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if (array_key_exists('tmmk',$item))

                    <td>{{ number_format($item['tmmk'] /1000000,5)}}</td>
                    @else
                        <td>0</td>
                    @endif

                    @if (array_key_exists('t_mmk_allowance',$item))

                    <td>{{ number_format($item['t_mmk_allowance'] /1000000,5)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    
                    @if (array_key_exists('t_mmk_amount',$item))

                    <td>{{ number_format($item['t_mmk_amount'] /1000000,5)}}</td>
                    @else
                        <td>0</td>
                    @endif


                    @if (array_key_exists('TotalBUSD',$item))

                    <td>{{ number_format($item['TotalBUSD'],2)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if (array_key_exists('TotalBMMK',$item))

                    <td>{{ number_format($item['TotalBMMK'] /1000000,5)}}</td>
                    @else
                        <td>0</td>
                    @endif


                    @if (array_key_exists('tb_mmk_allowance',$item))

                    <td>{{ number_format($item['tb_mmk_allowance'] /1000000,5)}}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if (array_key_exists('tb_mmk_amount',$item))

                    <td>{{ number_format($item['tb_mmk_amount'] /1000000,5)}}</td>
                    @else
                        <td>0</td>
                    @endif

                  {{Form::hidden('', $increment = $increment + 1)}}
                </tr>

                @php
                    $count+= $item['count'];
                    $tusd+= $item['tusd'] ;
                    $mmk+=$item['tmmk'] /1000000;
                    $tbusd+= $item['TotalBUSD'];
                    $tbmmk+= $item['TotalBMMK'] /1000000 ;
                @endphp
                @endforeach
                <tr>
                    <td colspan="12" style="font-weight: bold; text-align: center">Total</td>
                    <td style="font-weight: bold">{{$count}}</td>
                    <td style="font-weight: bold">{{number_format($tusd,2)}}</td>
                    <td style="font-weight: bold">{{number_format($mmk,5)}}</td>
                  

                </tr>

                  @endif

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
