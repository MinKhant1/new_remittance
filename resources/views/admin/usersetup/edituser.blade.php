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
                <h3 class="card-title">Edit User</h3>
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
              {!!Form::open(['action' => 'App\Http\Controllers\UserController@updateuser', 'method' => 'POST' , 'enctype' => 'multipart/form-data'])!!}
              {{ csrf_field() }}
              <div class="card-body">

                <div class="form-group">
                  {{Form::hidden('id', $user->id)}}
                  {{-- <label for="exampleInputEmail1">Product name</label>
                  <input type="text" name="product_name" class="form-control" id="exampleInputEmail1" placeholder="Enter product name"> --}}
                  {{Form::label('', 'Username', ['for' => 'exampleInputEmail1' ])}}
                  {{Form::text('name', $user->name,  ['placeholder' => 'Username','class' => 'form-control', 'id'=> 'exampleInputEmail1' ])}}
                </div>

                {{-- <div class="form-group">
                  <label>Select role</label>
                  <select class="form-control select2" name="role" style="width: 100%;">
                    <option selected="selected">Select</option>
                    @foreach ($roles as $role)
                    @if($role->type == 1)
                    <option value="{{$role->type}}">editor</option>
                    @else
                    <option value="{{$role->type}}">checker</option>
                    @endif
                    @endforeach
                  </select>
                </div> --}}
               <h4 style="font-weight: bold">Permissions</h4>
               <div class="row" style="display: flex">
                <div class="col-4">
                  <h5 style="font-weight: bold">Reports</h5>

                    @if ($user->inward == 1)
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="inward" value=1 id="inward" checked>
                    <label class="form-check-label" for="inward">
                      Inward
                    </label>
                  </div>
                    @else
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="inward" value=1 id="inward" >
                    <label class="form-check-label" for="inward">
                      Inward
                    </label>
                    </div>
                    @endif
                    @if ($user->outward == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="outward" value=1 id="outward" checked>
                    <label class="form-check-label" for="outward">
                      Outward
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="outward" value=1 id="outward">
                    <label class="form-check-label" for="outward">
                      Outward
                    </label>
                  </div>
                @endif
                @if ($user->total_inward == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="total_inward" value=1 id="total_inward" checked>
                    <label class="form-check-label" for="total_inward">
                      Total Inward
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="total_inward" value=1 id="total_inward">
                    <label class="form-check-label" for="total_inward">
                      Total Inward
                    </label>
                  </div>
                  @endif
                  @if ($user->total_outward == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="total_outward" value=1 id="total_outward" checked>
                    <label class="form-check-label" for="total_outward">
                      Total Outward
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="total_outward" value=1 id="total_outward">
                    <label class="form-check-label" for="total_outward">
                      Total Outward
                    </label>
                  </div>
                  @endif
                  @if ($user->total_inward_outward == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="total_inward_outward" value=1 id="total_inward_outward" checked>
                    <label class="form-check-label" for="total_inward_outward">
                      Total Inward+Outward
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="total_inward_outward" value=1 id="total_inward_outward">
                    <label class="form-check-label" for="total_inward_outward">
                      Total Inward+Outward
                    </label>
                  </div>
                  @endif
                </div>
                <div class="col-4">
                  <h5 style="font-weight: bold">Transactions</h5>
                  @if ($user->inward_trans == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="inward_trans" value=1 id="inward_trans" checked>
                    <label class="form-check-label" for="inward_trans">
                      Inward Transaction
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="inward_trans" value=1 id="inward_trans">
                    <label class="form-check-label" for="inward_trans">
                      Inward Transaction
                    </label>
                  </div>
                  @endif
                  @if ($user->inward_approve == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="inward_approve" value=1 id="inward_approve" checked>
                    <label class="form-check-label" for="inward_approve">
                       Inward Approve
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="inward_approve" value=1 id="inward_approve">
                    <label class="form-check-label" for="inward_approve">
                       Inward Approve
                    </label>
                  </div>
                  @endif
                  @if ($user->outward_trans == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="outward_trans" value=1 id="outward_trans" checked>
                    <label class="form-check-label" for="outward_trans">
                      Outward Transaction
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="outward_trans" value=1 id="outward_trans">
                    <label class="form-check-label" for="outward_trans">
                      Outward Transaction
                    </label>
                  </div>
                  @endif
                  @if ($user->outward_approve == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="outward_approve" value=1 id="outward_approve" checked>
                    <label class="form-check-label" for="outward_approve">
                       Outward Approve
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="outward_approve" value=1 id="outward_approve">
                    <label class="form-check-label" for="outward_approve">
                       Outward Approve
                    </label>
                  </div>
                  @endif
                </div>

                <div class="col-4">
                  <h5 style="font-weight: bold">System Control</h5>
                  @if ($user->company == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="company" value=1 id="company" checked>
                    <label class="form-check-label" for="company">
                      Company
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="company" value=1 id="company">
                    <label class="form-check-label" for="company">
                      Company
                    </label>
                  </div>
                  @endif
                  @if ($user->branch == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="branch" value=1 id="branch" checked>
                    <label class="form-check-label" for="branch">
                      Branch
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="branch" value=1 id="branch">
                    <label class="form-check-label" for="branch">
                      Branch
                    </label>
                  </div>
                  @endif
                  @if ($user->currency == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="currency" value=1 id="currency" checked>
                    <label class="form-check-label" for="currency">
                      Currency
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="currency" value=1 id="currency">
                    <label class="form-check-label" for="currency">
                      Currency
                    </label>
                  </div>
                  @endif
                  @if ($user->country == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="country" value=1 id="country" checked>
                    <label class="form-check-label" for="country">
                      Country
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="country" value=1 id="country">
                    <label class="form-check-label" for="country">
                      Country
                    </label>
                  </div>
                  @endif
                  @if ($user->purpose_of_trans == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="purpose_of_trans" value=1 id="purpose_of_trans" checked>
                    <label class="form-check-label" for="purpose_of_trans">
                      Purpose of Transactions
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="purpose_of_trans" value=1 id="purpose_of_trans">
                    <label class="form-check-label" for="purpose_of_trans">
                      Purpose of Transactions
                    </label>
                  </div>
                  @endif
                  @if ($user->trans_max_limit == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="trans_max_limit" value=1 id="trans_max_limit" checked>
                    <label class="form-check-label" for="trans_max_limit">
                      Trans-max-limit
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="trans_max_limit" value=1 id="trans_max_limit">
                    <label class="form-check-label" for="trans_max_limit">
                      Trans-max-limit
                    </label>
                  </div>
                  @endif
                  @if ($user->blacklist == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="blacklist" value=1 id="blacklist" checked>
                    <label class="form-check-label" for="blacklist">
                      Blacklist
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="blacklist" value=1 id="blacklist">
                    <label class="form-check-label" for="blacklist">
                      Blacklist
                    </label>
                  </div>
                  @endif
                  @if ($user->exchange_rate == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="exchange_rate" value=1 id="exchange_rate" checked>
                    <label class="form-check-label" for="exchange_rate">
                      Exchange Rate
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="exchange_rate" value=1 id="exchange_rate">
                    <label class="form-check-label" for="exchange_rate">
                      Exchange Rate
                    </label>
                  </div>
                  @endif
                  @if ($user->user_control == 1)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="user_control" value=1 id="user_control" checked>
                    <label class="form-check-label" for="user_control">
                      User
                    </label>
                  </div>
                  @else
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="user_control" value=1 id="user_control">
                    <label class="form-check-label" for="user_control">
                      User
                    </label>
                  </div>
                  @endif
                </div>
              </div>
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
  @endsection
