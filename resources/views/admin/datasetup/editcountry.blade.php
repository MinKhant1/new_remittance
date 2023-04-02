@extends('admin_layout.admin')


@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Country</h1>
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
                <h3 class="card-title">Edit Country</h3>
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
              {!!Form::open(['action' => 'App\Http\Controllers\CountryController@updatecountry', 'method' => 'POST' , 'enctype' => 'multipart/form-data'])!!}
              {{ csrf_field() }}
              <div class="card-body">

                <div class="form-group">
                  <label for="country_code">Country Code</label>
                  <select class="form-control" id="country_code"  name="country_code" onchange="changecountrycode()">
                    <option value="blank"></option>
                    @foreach ($country_lists as $country)
                    <option value="{{$country->country_code}}">{{$country->country_name}}</option>
                    @endforeach
                  </select>                 
                </div>

                <div class="form-group">
                  <label for="country_name">Country Name</label>
                  <select class="form-control" id="country_name"  name="country_name">
                    <option value="blank"></option>
                    @foreach ($country_lists as $country)
                    <option value="{{$country->country_name}}">{{$country->country_code}}</option>
                    @endforeach
                  </select>                      
                </div>

                {{-- <label for="exampleInputFile">Slider image</label>
                <div class="input-group">
                  <div class="custom-file">
                  {{Form::label('', 'Choose file', ['class' => 'custom-file-label', 'for' => 'exampleInputFile' ])}}
                  {{Form::file('slider_image',  ['class' => 'custom-file-input', 'id'=> 'exampleInputFile' ])}}
                  </div>
                  <div class="input-group-append">
                    <span class="input-group-text">Upload</span>
                  </div>
                </div> --}}
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <!-- <button type="submit" class="btn btn-success">Submit</button> -->
                {{-- <input type="submit" class="btn btn-success" value="Save"> --}}
                {!!Form::submit('Update', ['class' => 'btn btn-success'])!!}
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
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

<script>
      function changecountrycode()
    {

      let countryCodeIndex=document.getElementById('country_code').selectedIndex;
      let countryName=document.getElementById('country_name');
      countryName.selectedIndex=countryCodeIndex;
      
    }
</script>


  @endsection
