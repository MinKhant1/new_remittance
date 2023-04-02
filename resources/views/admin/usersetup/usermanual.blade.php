@extends('admin_layout.admin')


@section('content')
<style>
  .download
  {
    justify-content: center;
    align-items: center;
    margin-left: 25%;
    margin-top: 5px;
    text-decoration: none;
    background-color: white;
  }
  .manual{
    width: 20%;
    height: 40px;
    margin-left: 43%;
    margin-top: 25px;
  }

</style>

<div id="viewer" style="width: 65%; height: 600px; margin-left: 375px; margin-top: 30px"></div>
<script type="text/javascript" src="https://cloudpdf.io/viewer.min.js"></script>
  <script>
    const config = {
      documentId: 'dc48f232-0dbc-430e-82f8-2709ced18732',
      darkMode: true,
    };
    CloudPDF(config, document.getElementById('viewer')).then((instance) => {

    });
  </script>

<div class="manual">
    <div class="download">
      <a href="{{asset('ERS.pdf')}}" download><p>Click to Download&emsp;<i class="fa fa-download" aria-hidden="true"></i></p></a>
    </div>
  </div>

@endsection


