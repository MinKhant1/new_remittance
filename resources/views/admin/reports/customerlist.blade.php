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
            <h1>Cutomer List</h1>
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
                <h3 class="card-title">Cutomer List</h3>
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
                      <div class="col-3" style="display: flex ">
                        <label for="exampleFormControlSelect1">Select Cutomer Type</label>
                      <select class="form-control" id="exampleFormControlSelect1"  name="branch_id">
                        <option value="">All</option>
                        <option value="">Residence</option>
                        <option value="">Non-Residence</option>
                      </select>

                      </div>

                      <div class=" ml-4 mr-4">                      
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="inward" id="inward_check">
                        <label class="form-check-label" for="inward_check">
                          Inward
                        </label>
                      </div>

                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="outward" id="outward_check">
                        <label class="form-check-label" for="outward_check">
                          Outward
                        </label>
                      </div>
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
                    <th>Cutomer Name</th>
                  </tr>
                  </thead>
                    <tr>
                      <td></td>
                      <td>Test</td>
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
