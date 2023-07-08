@extends('admin_layout.admin_dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Dashboard</h1>
        </div>

        <!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
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
  <!-- /.content-header -->



  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
     
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-8">
          {!!Form::open(['action' => 'App\Http\Controllers\HomeController@countwithdate', 'method' => 'POST' , 'enctype' => 'multipart/form-data'])!!}
                {{ csrf_field() }}
          <h5>Transactions Status</h5>
          <div class="grid" style="display: flex">
            <div class="col-1-4 col-1-4-sm" style="padding-right: 5%">
                <div class="controls">
                  <label for="arrive" class="label-date"  style="padding-right: 20px">&nbsp;&nbsp;Start Date</label>
                  <input type="date" id="arrive"  style="margin-right: 40px" class="floatLabel" name="startCountDate"   value="{{$startCountDate}}">                 
                </div>
              </div>
              <div class="col-1-4 col-1-4-sm">
                <div class="controls">
                  <label for="arrive" class="label-date" style="padding-right: 20px">&nbsp;&nbsp;End Date</label>
                  <input type="date" id="arrive"   style="margin-right: 80px"  class="floatLabel"  name="endCountDate" value="{{$endCountDate}}">                 
                </div>
              </div>
              {!!Form::submit('Search', ['class' => 'btn btn-success'])!!}
              {!!Form::close()!!}
        </div>
        </div>
        <div class="col-12" style="border-bottom: solid 2px black">
         <div class="col-8">
          <div class="card" style="margin-top: 10px">
            <div class="card-header">
            <h3 class="card-title">Inward Transactions</h3>
            </div>
            
            <div class="card-body p-0">
            <table class="table table-sm">
            <thead>
            <tr>
           
            <th>Date</th>
            <th>Total Transactions</th>
            <th>Approved Transactions</th>
            <th>Remaining Transactions</th>
            </tr>
            </thead>
            <tbody>

              @foreach ($inwardCounts as $key=>$inwardcount)
              <tr>
                
              <td style="font-weight: 500">{{$key}}</td>
              <td style="font-size: 20px" class="text-center"><span class="badge bg-info">{{$inwardcount['total_count']}}</span></td>
              <td style="font-size: 20px" class="text-center"><span class="badge bg-success">{{$inwardcount['approved_count']}}</span></td>
              <td style="font-size: 20px" class="text-center"><span class="badge bg-danger">{{$inwardcount['remaining_count']}}</span></td>
              </tr>
                  
              @endforeach
          

           

            </tbody>
            </table>
            </div>
            
            </div>
            
            </div>
          </div>


          
        </div>


        <div class="col-12" style="border-bottom: solid 2px black">
          <div class="col-8">
           <div class="card" style="margin-top: 10px">
             <div class="card-header">
             <h3 class="card-title">Outward Transactions</h3>
             </div>
             
             <div class="card-body p-0">
             <table class="table table-sm">
             <thead>
             <tr>
            
             <th>Date</th>
             <th>Total Transactions</th>
             <th>Approved Transactions</th>
             <th>Remaining Transactions</th>
             </tr>
             </thead>
             <tbody>
 
               @foreach ($outwardCounts as $key=>$outwardCount)
               <tr>
                 
               <td style="font-weight: 500">{{$key}}</td>
               <td style="font-size: 20px" class="text-center"><span class="badge bg-info">{{$outwardCount['total_count']}}</span></td>
               <td style="font-size: 20px" class="text-center"><span class="badge bg-success">{{$outwardCount['approved_count']}}</span></td>
               <td style="font-size: 20px" class="text-center"><span class="badge bg-danger">{{$outwardCount['remaining_count']}}</span></td>
               </tr>
                   
               @endforeach
           
 
            
 
             </tbody>
             </table>
             </div>
             
             </div>
             
             </div>
           </div>
 
 
           
         </div>
        <br>
        <div class="col-8">
          {!!Form::open(['action' => 'App\Http\Controllers\HomeController@dailywithdate', 'method' => 'POST' , 'enctype' => 'multipart/form-data'])!!}
                {{ csrf_field() }}
          <h5>Daily Transaction</h5>
          <div class="grid" style="display: flex">
            <div class="col-1-4 col-1-4-sm" style="padding-right: 5%">
                <div class="controls">
                  <label for="arrive" class="label-date"  style="padding-right: 20px">&nbsp;&nbsp;Start Date</label>
                  <input type="date" id="arrive"  style="margin-right: 40px" class="floatLabel" name="startdate"  value="<?php echo date('Y-m-d'); ?>">                 
                </div>
              </div>
              <div class="col-1-4 col-1-4-sm">
                <div class="controls">
                  <label for="arrive" class="label-date" style="padding-right: 20px">&nbsp;&nbsp;End Date</label>
                  <input type="date" id="arrive"   style="margin-right: 80px"  class="floatLabel"  name="enddate" value="<?php echo date('Y-m-d'); ?>">                 
                </div>
              </div>
              {!!Form::submit('Search', ['class' => 'btn btn-success'])!!}
              {!!Form::close()!!}
        </div>
        </div>
        <div class="col-12" style="display: flex; border-bottom: solid 2px black">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                @if ($dailyinward)

                <h3>{{$dailyinward}}</h3>
                @else
                <h3>0</h3>
                @endif


                <p><i class="fas fa-chart-line fa-x fa-fw"></i> Daily Inward</p>
              </div>
              <div class="icon">
                {{-- <i class="ion ion-bag"></i> --}}
                <i class="fa fa-calendar" aria-hidden="true"></i>
              </div>
              {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                @if ($dailyoutward)

                <h3>{{$dailyoutward}}</h3>
                @else
                <h3>0</h3>
                @endif


                <p><i class="fas fa-chart-line fa-x fa-fw"></i> Daily Outward</p>
              </div>
              <div class="icon">
                {{-- <i class="ion ion-bag"></i> --}}
                <i class="fa fa-calendar" aria-hidden="true"></i>
              </div>
              {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
          </div>
        </div>
    
        <div class="col-8"> <br>
          {!!Form::open(['action' => 'App\Http\Controllers\HomeController@monthlywithdate', 'method' => 'POST' , 'enctype' => 'multipart/form-data'])!!}
                {{ csrf_field() }}
          <h5>Monthly Transaction</h5>
          <div class="grid" style="display: flex">
            <div class="col-1-4 col-1-4-sm" style="padding-right: 5%">
                <div class="controls">
                  <label for="arrive" class="label-date"  style="padding-right: 10px">&nbsp;&nbsp;Start Month</label>
                  <input type="month" id="arrive"  style="margin-right: 20px" class="floatLabel" name="startdate" value="<?php echo date('Y-m'); ?>">
                  
                </div>
              </div>
              <div class="col-1-4 col-1-4-sm">
                <div class="controls">
                  <label for="arrive" class="label-date" style="padding-right: 10px">&nbsp;&nbsp;End Month</label>
                  <input type="month" id="arrive" style="margin-right: 50px" class="floatLabel" name="enddate" value="<?php echo date('Y-m'); ?>">
                 
                </div>
              </div>
              {!!Form::submit('Search', ['class' => 'btn btn-success'])!!}
              {!!Form::close()!!}
        </div>
        </div>
        <div class="col-12" style="display: flex; border-bottom: solid 2px black">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              @if ($monthlyinward!=null)

              <h3>{{$monthlyinward}}</h3>
              @else
              <h3>0</h3>
              @endif

              <p><i class="fas fa-chart-line fa-x fa-fw"></i> Monthly Inward</p>
            </div>
            <div class="icon">
              {{-- <i class="ion ion-person-add"></i> --}}
              <i class="fa fa-calendar" aria-hidden="true"></i>
            </div>
            {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              @if ($monthlyoutward!=null)

              <h3>{{$monthlyoutward}}</h3>
              @else
              <h3>0</h3>
              @endif

              <p><i class="fas fa-chart-line fa-x fa-fw"></i> Monthly Outward</p>
            </div>
            <div class="icon">
              {{-- <i class="ion ion-person-add"></i> --}}
              <i class="fa fa-calendar" aria-hidden="true"></i>
            </div>
            {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
          </div>
        </div>
        </div>
        <!-- ./col -->
        <div class="col-8"> <br>
          {!!Form::open(['action' => 'App\Http\Controllers\HomeController@yearlywithdate', 'method' => 'POST' , 'enctype' => 'multipart/form-data'])!!}
                {{ csrf_field() }}
          <h5>Yearly Transaction</h5>
          <div class="grid" style="display: flex">
            <div class="col-1-4 col-1-4-sm" style="padding-right: 5%">
                <div class="controls">
                  <label for="arrive" class="label-date" style="padding-right: 20px">&nbsp;&nbsp;Start Year</label>
                  <input type="number" id="arrive" min="1900" max="2099" style="margin-right: 90px"  step="1" class="floatLabel" name="startdate" value="<?php echo date('Y'); ?>" />                 
                </div>
              </div>
              <div class="col-1-4 col-1-4-sm">
                <div class="controls">
                  <label for="arrive" class="label-date" style="padding-right: 20px">&nbsp;&nbsp;End Year</label>
                  <input type="number" id="arrive" min="1900" max="2099" step="1" style="margin-right: 120px"  class="floatLabel" name="enddate" value="<?php echo date('Y'); ?>" />
                
                </div>
              </div>
              {!!Form::submit('Search', ['class' => 'btn btn-success'])!!}
              {!!Form::close()!!}
        </div>
        </div>
        <div class="col-12" style="display: flex; border-bottom: solid 2px black">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              @if ($yearlyinward)

              <h3>{{$yearlyinward}}</h3>
              @else
              <h3>0</h3>
              @endif

              <p><i class="fas fa-chart-line fa-x fa-fw"></i> Yearly</p>
            </div>
            <div class="icon">
              {{-- <i class="ion ion-pie-graph"></i> --}}
              <i class="fa fa-calendar" aria-hidden="true"></i>
            </div>
            {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              @if ($yearlyoutward)

              <h3>{{$yearlyoutward}}</h3>
              @else
              <h3>0</h3>
              @endif

              <p><i class="fas fa-chart-line fa-x fa-fw"></i> Yearly</p>
            </div>
            <div class="icon">
              {{-- <i class="ion ion-pie-graph"></i> --}}
              <i class="fa fa-calendar" aria-hidden="true"></i>
            </div>
            {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
          </div>
        </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->

    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


