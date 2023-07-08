<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    {{-- <a href="{{url('/')}}" class="brand-link">
      <img src=""  class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-bold pl-4">ERS</span>
    </a> --}}

    <!-- Sidebar -->
   
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <a href="{{url('/')}}" class="d-block">
            @if ($company)  
            <div class="image">
              <img src="{{'/images/company/'.$company->image}}" style="width: 200px" class="img-thumbnail elevation-2" alt="User Image">
            </div></a>
            @endif
      </div>
   
<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
      {{-- <li class="nav-item has-treeview {{request()->is('admin') ? 'menu-open' : ''}}">
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{url('/admin')}}" class="nav-link {{request()->is('admin') ? 'active' : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>Dashboard v1</p>
            </a>
          </li>
        </ul>
      </li> --}}

     

      {{-- Daily Transaction --}}

      <li class="nav-header">Daily Transaction</li>
      <li class="nav-item">
        <a href="{{url('/inwardtransaction')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fa fa-user" aria-hidden="true"></i>
          <p>Inward Transaction</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/approveinwardtransaction')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fas fa-edit" aria-hidden="true"></i>
          <p>Inward Approve</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/outwardtransaction')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fa fa-user" aria-hidden="true"></i>
          <p>Outward Transaction</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/outwardtransactionapprove')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fas fa-edit" aria-hidden="true"></i>
          <p>Outward Approve</p>
        </a>
      </li>

      <li class="nav-header">Reports</li>
      <li class="nav-item">
        <a href="{{url('/inward')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fa fa-folder" aria-hidden="true"></i>
          <p>Inward</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/outwardtransactionreport')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fas fa-folder" aria-hidden="true"></i>
          <p>Outward</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/totalinward')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fa fa-folder" aria-hidden="true"></i>
          <p>Total Inward</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/totaloutward')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fas fa-folder" aria-hidden="true"></i>
          <p>Total Outward</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/totalinwardoutward')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fa fa-folder" aria-hidden="true"></i>
          <p>Total Inward + Outward</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/inward_customer_list')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fa fa-folder" aria-hidden="true"></i>
          <p>Inward Customer List</p>
        </a>
      </li>


      <li class="nav-item">
        <a href="{{url('/outward_customer_list')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fa fa-folder" aria-hidden="true"></i>
          <p>Outward Customer List</p>
        </a>
      </li>


      {{-- Setup Data --}}
      <li class="nav-header">System Control</li>
      <li class="nav-item">
        <a href="{{url('/company')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fa fa-user" aria-hidden="true"></i>
          <p>Company</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/branch')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fas fa-edit" aria-hidden="true"></i>
          <p>Branch</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/country')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fa fa-user" aria-hidden="true"></i>
          <p>Country</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/currency')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fas fa-edit" aria-hidden="true"></i>
          <p>Currency</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/purposeoftrans')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fas fa-edit" aria-hidden="true"></i>
          <p>Purpose of Trans</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/transmaxlimit')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fas fa-edit" aria-hidden="true"></i>
          <p>Trans-max-limit</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/blacklist')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fas fa-edit" aria-hidden="true"></i>
          <p>Black List</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/exchangerate')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fas fa-edit" aria-hidden="true"></i>
          <p>Exchange Rate</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/user')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fa fa-user" aria-hidden="true"></i>
          <p>User</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{url('/usermanual')}}" class="nav-link {{request()->is('') ? 'active' : ''}}">
          <i class="fa fa-user" aria-hidden="true"></i>
          <p>User Manual</p>
        </a>
      </li>
   
  </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
