@extends('admin_layout.admin')


@section('content')
    {{ Form::hidden('', $increment = 1) }}
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Total Outward Transaction Report</h1>
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
                                <h3 class="card-title">Monthly Statement of Outward Remittance</h3>
                            </div>

                            @if (Session::has('status'))
                                <div class="alert alert-success">
                                    {{ Session::get('status') }}
                                </div>
                            @endif
                            <!-- /.card-header -->
                            {!! Form::open([
                                'action' => 'App\Http\Controllers\OutwardTransactionController@totaloutwardwithdate',
                                'method' => 'POST',
                                'enctype' => 'multipart/form-data',
                            ]) !!}
                            {{ csrf_field() }}
                            <div class="card-body" style="overflow-x: scroll">
                                <div class="grid" style="display: flex">
                                    <div class="col-1-4 col-1-4-sm" style="padding-right: 5%">
                                        <div class="controls">
                                            <label for="arrive" class="label-date"
                                                style="padding-right: 20px">&nbsp;&nbsp;Start Date</label>
                                            <input type="date" id="arrive" style="margin-right: 40px"
                                                class="floatLabel" name="startdate" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-1-4 col-1-4-sm">
                                        <div class="controls">
                                            <label for="arrive" class="label-date"
                                                style="padding-right: 20px">&nbsp;&nbsp;End Date</label>
                                            <input type="date" id="arrive" style="margin-right: 80px"
                                                class="floatLabel" name="enddate" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    {!! Form::submit('Search', ['class' => 'btn btn-success']) !!}
                                    {!! Form::close() !!}
                                    <a href="{{url('/exportexceloutwardtotal')}}" class="btn btn-success" style="margin-left: 20px"><i class="nav-icon fas fa-print">  Print</i></a>
                                </div>

                                <table id="" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">SrNo</th>
                                            <th rowspan="2">Date</th>
                                            <th rowspan="2">Total No.of Trans</th>
                                            <th colspan="2">Total Outward Remittance Amount
                                            <th colspan="2">Total Outward Remittance Amount From the date of Starting
                                                from the Business
                                                {{-- <th colspan="2">Total Outward Remittance Amount</th> --}}
                                        </tr>
                                        </th>

                                        <th>USD</th>
                                        <th>MMK(in Million)</th>
                                        <th>USD</th>
                                        <th>MMK(in Million)</th>

                                    </thead>
                                    @php
                                        $tusd=0;
                                        $mmk=0;
                                        $tbusd=0;
                                        $tbmmk=0;
                                        $count=0;
                                    @endphp
                                    <tbody>
                                        @if(request()->is('totaloutward'))

                                        {{-- @foreach ($totaloutward as $transaction) --}}
                                        <tr>
                                            <td>{{ $increment }}</td>
                                            <td>{{ $ed }}</td>
                                            <td>{{ $total_num_trans }}</td>
                                            <td> {{ number_format($T_usd_amount,2) }}</td>
                                            <td>{{ number_format($T_mmk_amount / 1000000,5) }}</td>
                                            <td> {{ number_format($Tb_usd_amount,2) }}</td>
                                            <td>{{ number_format($Tb_mmk_amount / 1000000,5) }}</td>
                                        </tr>

                                        @endif

                                        @if (request()->is('totaloutwardwithdate'))
                                        @foreach ($derived_array as $array)
                                        <tr>
                                            <td>{{ $increment }}</td>
                                            <td>{{ $array['dates'] }}</td>
                                            <td>{{ $array['count'] }}</td>
                                            <td>{{ number_format($array['tusd'],2) }}</td>
                                            <td>{{ number_format($array['tmmk'] /1000000,5) }}</td>
                                            <td>{{ number_format($array['TotalBUSD'],2) }}</td>
                                            <td>{{ number_format($array['TotalBMMK'] /1000000,5) }}</td>
                                        </tr>
                                        @php
                                            $count+=$array['count'];
                                            $tusd+= $array['tusd'] ;
                                            $mmk+=$array['tmmk'] /1000000;
                                            $tbusd+= $array['TotalBUSD'];
                                            $tbmmk+= $array['TotalBMMK'] /1000000 ;
                                        @endphp

                                        {{ Form::hidden('', $increment = $increment + 1) }}
                                        @endforeach
                                        <tr>
                                            <td colspan="2" style="font-weight: bold; text-align: center">Total</td>
                                            <td style="font-weight: bold">{{$count}}</td>
                                            <td style="font-weight: bold">{{number_format($tusd,2)}}</td>
                                            <td style="font-weight: bold">{{number_format($mmk,5)}}</td>
                                            {{-- <td style="font-weight: bold">{{number_format($tbusd,2)}}</td>
                                            <td style="font-weight: bold">{{number_format($tbmmk,5)}}</td> --}}

                                        </tr>
                                        @endif

                                        {{-- @endforeach --}}
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
    <script src="asset{{ 'backend/dist/js/adminlte.min.js' }}"></script>

    <script src="backend/dist/js/bootbox.min.js"></script>
    <!-- page script -->

    <script>
        $(document).on("click", "#delete", function(e) {
            e.preventDefault();
            var link = $(this).attr("href");
            bootbox.confirm("Do you really want to delete this element ?", function(confirmed) {
                if (confirmed) {
                    window.location.href = link;
                };
            });
        });
    </script>
    <!-- page script -->
    <script>
        $(function() {
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
