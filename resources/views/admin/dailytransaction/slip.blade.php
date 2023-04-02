@extends('admin_layout.admin')


@section('content')

@if(Session::has('outward'))
<div class="container">
    <div class="row" style="margin-left: 10%; margin-top: 3%">
        <div class="col-8">
            <h3 class="text-bold text-success">MoneyKing Co.Ltd</h3>
        </div>

        <div class="col-8" style="margin-top: 1%">
            <p>Slip No.{{Session::get('outward')->tran_no}}<span style="padding: 10%">{{Session::get('outward')->created_at->format('Y-m-d')}}</span></p>
        </div>

        <div class="col-8" style="margin-top: 3%">         
            <p class="text-bold">Sender: {{Session::get('outward')->sender_name}}</p>
            <p>{{Session::get('outward')->sender_nrcpassport}}</p>
            <p>{{Session::get('outward')->sender_address}}, {{Session::get('outward')->sender_phno}} </p>
        </div>

        <div class="col-8" style="margin-top: 3%">         
            <p class="text-bold">Receiver: {{Session::get('outward')->receiver_name}}</p>
            <p>Receiver: {{Session::get('outward')->receiver_country}}</p>
        </div>

        <div class="col-8" style="margin-top: 3%">         
            <p class="text-bold">Transfer: {{Session::get('outward')->deposit_point}}</p>
        </div>

        <div class="col-9" style="margin-top: 3%">         
            <p>Thanks</p>
        </div>

        <div class="col-3" style="margin-top: 3%">         
            <a href="/viewpdf" class="btn btn-success"><i class="nav-icon fas fa-print">Print Slip</i></a></td>
        </div>
    </div>
</div>
@endif

@endsection