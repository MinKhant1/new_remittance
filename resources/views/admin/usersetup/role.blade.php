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
            <h1>Role</h1>
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
                <h3 class="card-title">All Role</h3>
              </div>
              <div class="col-12" style="margin: 1% -1% 0%">
                <a href="{{url('/addrole')}}" class="btn btn-primary float-right">Add New Role</a>
              </div>

              @if (Session::has('status'))
              <div class="alert alert-success">
                {{Session::get('status')}}
              </div>
              @endif
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Num.</th>
                    <th>Role</th>
                    <th>Update</th>
                    <th>Delete</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($roles as $role)
                  <tr>
                    <td>{{$increment}}</td>
                    @if($role->type == 1)
                    <td>Admin</td>
                    @else
                    <td>Checker</td>
                    @endif
                    <td>
                      <a href="{{url('/editrole/' . $role->id)}}" class="btn btn-primary"><i class="nav-icon fas fa-edit"></i></a></td>
                    <td>
                      <a href="{{url('/deleterole/'. $role->id)}}" id="delete" class="btn btn-danger" ><i class="nav-icon fas fa-trash"></i></a>
                    </td>
                  </tr>
                           
                  {{Form::hidden('', $increment = $increment + 1)}}       
                  @endforeach                                 
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Num.</th>
                    <th>Role</th>
                    <th>Update</th>
                    <th>Delete</th>
                  </tr>
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
<script src="backend/dist/js/adminlte.min.js"></script>

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
      "responsive": true,
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