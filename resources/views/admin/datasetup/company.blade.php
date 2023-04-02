@extends('admin_layout.admin')


@section('content')

<style>
body {
   // background: #67B26F;  /* fallback for old browsers */
    //background: -webkit-linear-gradient(to right, #4ca2cd, #67B26F);  /* Chrome 10-25, Safari 5.1-6 */
   // background: linear-gradient(to right, #4ca2cd, #67B26F); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    padding: 0;
    margin: 0;
    font-family: 'Lato', sans-serif;
    color: #000;
}
.student-profile .card {
    border-radius: 10px;
}
.student-profile .card .card-header .profile_img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    margin: 10px auto;
    border: 10px solid #ccc;
    border-radius: 50%;
}
.student-profile .card h3 {
    font-size: 20px;
    font-weight: 700;
}
.student-profile .card p {
    font-size: 16px;
    color: #000;
}
.student-profile .table th,
.student-profile .table td {
    font-size: 14px;
    padding: 5px 10px;
    color: #000;
}

</style>

<body>
  <head>
  <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
<!-- Bootstrap CSS -->
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
<!-- Font Awesome CSS -->
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css'>
</head>
</body>
<div class="student-profile" >
  <div class="container">
    <div class="row">
      <div class="col-lg-10 md-8 mx-auto my-auto" style="padding-top: 5%">
        <div class="card shadow-sm" style="padding: 10%">
          <div class="card-header bg-transparent text-center" style="padding-top: 2%">

            @if ($company)
                
            <img class="profile_img" src="{{asset('/storage/image/company/'.$company->image)}}" alt="company image">
            @else
            <img class="profile_img" src="https://www.pngkey.com/png/full/115-1150152_default-profile-picture-avatar-png-green.png" alt="company Logo">
            @endif

            <div style="display: flex; flex-direction:right;align-items: center;
            justify-content: center;" >
            
            @if ($company)
                
            <h3>{{$company->company_name}}</h3>
            @else
            <h3>Company Name</h3>
            @endif

            @if ($company)
            <a style="margin-left: 3%" href="{{url('/editcompany/'. 1 )}}" class="btn btn-primary"><i class="nav-icon fas fa-edit"></i></a></td>
            @else
            <a style="margin-left: 3%" href="{{url('/addcompany')}}" class="btn btn-primary"><i class="fas fa-plus-circle"></i></a></td>
            @endif
            </div>
          </div>
          <div class="card-body mx-auto" style="padding-top: 5%">
            @if ($company)
            
            <p hidden class="mb-0" id="companycode" style="color:red"><strong class="pr-1">Company Code:</strong>{{$company->company_code}}</p>
            <p class="mb-0"><strong class="pr-1">Phone Number:</strong>{{$company->company_phno}}</p>
            <p class="mb-0"><strong class="pr-1">Company Address:</strong>{{$company->company_address}}</p>
          
            @else
            <p hidden class="mb-0" id="companycode"><strong class="pr-1">Company Code:</strong>company code</p>
            <p class="mb-0"><strong class="pr-1">Phone Number:</strong></p>
            <p class="mb-0"><strong class="pr-1">Address:</strong></p>
            @endif
            {{-- <p class="mb-0"><strong class="pr-1">Section:</strong>A</p> --}}
          </div>
        </div>
      </div>
  
        
        
        </div>
      </div>
    </div>
  </div>
</div>

@endsection


<script>
   var toggleCode=false;
document.addEventListener("keydown",e=>
{
 
var keyField=document.getElementById('companycode');
  e.preventDefault();
  if(e.key.toLowerCase()==="d" && e.shiftKey && e.altKey )
  { 
    toggleCode=!toggleCode;
    keyField.hidden=toggleCode;
   


  }

})

</script>