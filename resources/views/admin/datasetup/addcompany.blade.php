@extends('admin_layout.admin')


@section('content')
 
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Company</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Add Company</h3>
              </div>
              
              @if (Session::has('status'))
              <div class="alert alert-success">
                {{Session::get('status')}}
              </div>
              @endif

              @if (count($errors) > 0)
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{$error}}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              <!-- /.card-header -->
              <!-- form start -->
              {!!Form::open(['action' => 'App\Http\Controllers\CompanyController@savecompany', 'method' => 'POST' , 'enctype' => 'multipart/form-data'])!!}
              {{ csrf_field() }}
              <div class="card-body">
                
                <div class="form-group">
                 
                  {{-- <label for="exampleInputEmail1">Product name</label>
                  <input type="text" name="product_name" class="form-control" id="exampleInputEmail1" placeholder="Enter product name"> --}}
                  {{Form::label('', 'Company code', ['for' => 'exampleInputEmail1' ])}}
                  {{Form::text('company_code', '',  ['placeholder' =>'Company Code','class' => 'form-control', 'id'=> 'exampleInputEmail1' ])}}
                </div>

                <div class="form-group">
                  {{-- <label for="exampleInputEmail1">Product name</label>
                  <input type="text" name="product_name" class="form-control" id="exampleInputEmail1" placeholder="Enter product name"> --}}
                  {{Form::label('', 'Company name', ['for' => 'exampleInputEmail1' ])}}
                  {{Form::text('company_name', '',  ['placeholder' => 'Company Name','class' => 'form-control', 'id'=> 'exampleInputEmail1' ])}}
                </div>

                <div class="form-group">
                  {{-- <label for="exampleInputEmail1">Product name</label>
                  <input type="text" name="product_name" class="form-control" id="exampleInputEmail1" placeholder="Enter product name"> --}}
                  {{Form::label('', 'Company Phone Number', ['for' => 'exampleInputEmail1' ])}}
                  {{Form::text('company_phno', '',  ['placeholder' => 'Company Phone Number','class' => 'form-control', 'id'=> 'exampleInputEmail1' ])}}
                </div>

                <div class="form-group">
                  {{-- <label for="exampleInputEmail1">Product name</label>
                  <input type="text" name="product_name" class="form-control" id="exampleInputEmail1" placeholder="Enter product name"> --}}
                  {{Form::label('', 'Company Address', ['for' => 'exampleInputEmail1' ])}}
                  {{Form::text('company_address', '',  ['placeholder' => 'Company Address','class' => 'form-control', 'id'=> 'exampleInputEmail1' ])}}
                </div>

                <div class="form-group">
                  {{-- <label for="exampleInputEmail1">Product name</label>
                  <input type="text" name="product_name" class="form-control" id="exampleInputEmail1" placeholder="Enter product name"> --}}
                  {{Form::label('', 'Company Image', ['for' => 'exampleInputEmail1' ])}}
                  {{Form::file('image',  ['class' => 'form-control', 'id'=> 'exampleInputEmail1' ])}}
                </div>

           
                {{-- <label for="exampleInputFile">Company Image</label>
                <div class="input-group">
                  <div class="custom-file">
                  {{Form::label('', 'Choose file', ['class' => 'custom-file-label', 'for' => 'exampleInputFile' ])}}
                  {{Form::file('image',  ['class' => 'custom-file-input', 'id'=> 'exampleInputFile' ])}}
                  </div> --}}
                  
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <!-- <button type="submit" class="btn btn-success">Submit</button> -->
                {{-- <input type="submit" class="btn btn-success" value="Save"> --}}
                {!!Form::submit('Save', ['class' => 'btn btn-success'])!!}
              </div>
              {!!Form::close()!!}
              
            </div>
            <!-- /.card --> 
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @endsection

  @section('scripts')
    <!-- jquery-validation -->
<script src="backend/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="backend/plugins/jquery-validation/additional-methods.min.js"></script>
<!-- AdminLTE App -->
<script src="backend/dist/js/adminlte.min.js"></script>

<script>
    $(function () {
      $.validator.setDefaults({
        submitHandler: function () {
          alert( "Form successful submitted!" );
        }
      });
      $('#quickForm').validate({
        rules: {
          email: {
            required: true,
            email: true,
          },
          password: {
            required: true,
            minlength: 5
          },
          terms: {
            required: true
          },
        },
        messages: {
          email: {
            required: "Please enter a email address",
            email: "Please enter a vaild email address"
          },
          password: {
            required: "Please provide a password",
            minlength: "Your password must be at least 5 characters long"
          },
          terms: "Please accept our terms"
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });
    });
    </script>
  @endsection