@extends('admin_layout.admin')


@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User</h1>
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
                <h3 class="card-title">Add User</h3>
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
              {!!Form::open(['action' => 'App\Http\Controllers\UserController@saveuser', 'method' => 'POST' , 'enctype' => 'multipart/form-data'])!!}
              {{ csrf_field() }}
              <div class="card-body">

                <div class="form-group">
                  {{-- <label for="exampleInputEmail1">Product name</label>
                  <input type="text" name="product_name" class="form-control" id="exampleInputEmail1" placeholder="Enter product name"> --}}
                  {{Form::label('', 'User', ['for' => 'exampleInputEmail1' ])}}
                  {{Form::text('name', '',  ['placeholder' => 'User','class' => 'form-control', 'id'=> 'exampleInputEmail1' ])}}

                  <div class="form-group">
                    <label for="branch_code">Branch</label>
                    <select class="form-control" id="branch_code"  name="branch_code">
                      <option value="blank"></option>
                      @foreach ($branches as $branch)
                      <option value="{{$branch->branch_code}}">{{$branch->branch_code}}</option>
                      @endforeach
                    </select>
                  </div>

                  {{Form::label('', 'Password', ['for' => 'exampleInputEmail1' ])}}
                  {{Form::password('password', ['placeholder' => 'Password','class' => 'form-control', 'id'=> 'exampleInputEmail1' ])}}

                  {{Form::label('', 'Confirm Password', ['for' => 'exampleInputEmail1' ])}}
                  {{Form::password('password_confirmation', ['placeholder' => 'Confirm Password','class' => 'form-control', 'id'=> 'exampleInputEmail1' ])}}
                </div>


                <h4 style="font-weight: bold">Permissions</h4>
               <div class="row" style="display: flex">
                <div class="col-4">
                  <h5 style="font-weight: bold">Reports</h5>
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="inward" value=1 id="inward" >
                    <label class="form-check-label" for="inward">
                      Inward
                    </label>
                    </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="outward" value=1 id="outward">
                    <label class="form-check-label" for="outward">
                      Outward
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="total_inward" value=1 id="total_inward">
                    <label class="form-check-label" for="total_inward">
                      Total Inward
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="total_outward" value=1 id="total_outward">
                    <label class="form-check-label" for="total_outward">
                      Total Outward
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="total_inward_outward" value=1 id="total_inward_outward">
                    <label class="form-check-label" for="total_inward_outward">
                      Total Inward+Outward
                    </label>
                  </div>
                </div>
                <div class="col-4">
                  <h5 style="font-weight: bold">Transactions</h5>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="inward_trans" value=1 id="inward_trans">
                    <label class="form-check-label" for="inward_trans">
                      Inward Transaction
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="inward_approve" value=1 id="inward_approve">
                    <label class="form-check-label" for="inward_approve">
                       Inward Approve
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="outward_trans" value=1 id="outward_trans">
                    <label class="form-check-label" for="outward_trans">
                      Outward Transaction
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="outward_approve" value=1 id="outward_approve">
                    <label class="form-check-label" for="outward_approve">
                       Outward Approve
                    </label>
                  </div>
                </div>

                <div class="col-4">
                  <h5 style="font-weight: bold">System Control</h5>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="company" value=1 id="company">
                    <label class="form-check-label" for="company">
                      Company
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="branch" value=1 id="branch">
                    <label class="form-check-label" for="branch">
                      Branch
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="currency" value=1 id="currency">
                    <label class="form-check-label" for="currency">
                      Currency
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="country" value=1 id="country">
                    <label class="form-check-label" for="country">
                      Country
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="purpose_of_trans" value=1 id="purpose_of_trans">
                    <label class="form-check-label" for="purpose_of_trans">
                      Purpose of Transactions
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="trans_max_limit" value=1 id="trans_max_limit">
                    <label class="form-check-label" for="trans_max_limit">
                      Trans-max-limit
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="blacklist" value=1 id="blacklist">
                    <label class="form-check-label" for="blacklist">
                      Blacklist
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="exchange_rate" value=1 id="exchange_rate">
                    <label class="form-check-label" for="exchange_rate">
                      Exchange Rate
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="user_control" value=1 id="user_control">
                    <label class="form-check-label" for="user_control">
                      User
                    </label>
                  </div>
                </div>
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
