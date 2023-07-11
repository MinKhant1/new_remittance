@extends('admin_layout.admin')
@section('content')
    @if (Session::has('status'))
        <div class="alert alert-success" style="margin-left: 15rem;">
            {{ Session::get('status') }}
        </div>
    @endif

    <div class="alert alert-danger" style="margin-left: 15rem;" id="daily_max_warning" hidden>
        Daily Transaction Max Limit Excedded
      </div>
      <div class="alert alert-danger" style="margin-left: 15rem;" id="monthly_max_warning" hidden>
        Monthly Transaction Max Limit Excedded
      </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger" style="margin-left: 15rem">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::open([
        'action' => 'App\Http\Controllers\OutwardTransactionController@saveoutwardtransaction',
        'method' => 'POST',
        'enctype' => 'multipart/form-data',
    ]) !!}
    {{ csrf_field() }}
    <!--  General -->
    <div class="form-row" style="margin: 2% 2% 2% 18%">
        <div class="col-12">
            <p class="text-bold" style="color:black; font-size:25px">Add Outward Transaction </p>
          </div>
        <div class="col-2">
            <label for="Date" class="mr-sm-2">Date:</label>
            {{-- <input type="date" class="form-control mb-2 mr-sm-2" placeholder="Enter Date" id="date" name="date" value="2013-01-08"> --}}
            <input type="text" class="form-control mb-2 mr-sm-2" placeholder="" id="date" name="date"
                value="{{ now() }}" readonly>

        </div>

        <div class="col-2">
            <label for="exampleFormControlSelect1">Select Branch</label>
            <select class="form-control" id="exampleFormControlSelect1" name="branch_id">
                <option value="blank"></option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->country }},{{ $branch->prefer_currency }},{{ $branch->branch_code }}">
                        {{ $branch->branch_name }}</option>
                @endforeach
            </select>
        </div>
 <input type="hidden" name="exchange_rate_input_usd" id="exchange_rate_input_usd" value="2100">
 
        <div class="" hidden>
            <label for="receiver_name" class="mr-sm-2">Hidden Branch:</label>
            <input type="text" class="form-control mb-2 mr-sm-2" placeholder="" id="hidden_branch_id"
                name="hidden_branch_id" value="">
        </div>


    @if($outwardtransaction != null)
    
    <div class="col-3" style="margin-left: 20%">
      <label for="sr_id" class="mr-sm-2">Transaction No:</label>
      <input type="text" class="form-control mb-2 mr-sm-2"  readonly placeholder="Transaction No" id="sr_id" name="sr_id" value="{{$outwardtransaction+1}}">
    </div>  
    
    @else
      
   <div class="col-3" style="margin-left: 20%">
    <label for="sr_id" class="mr-sm-2">Transaction No:</label>
    <input type="text" class="form-control mb-2 mr-sm-2" readonly placeholder="Transaction No" id="sr_id" name="sr_id" value="1">
  </div>
  @endif
  
  <div class="col-12">
    <p class="text-bold" style="color:blue; font-size:20px">SENDER INFORMATION : </p>
</div>


<div class="col-4">
    <label for="receiver_name" class="mr-sm-2">Sender Name:</label>
    <input type="text" class="form-control mb-2 mr-sm-2" placeholder="" id="sender_name" name="sender_name" value="{{old('sender_name')}}">
  </div>

        <div class="col-2">
            <label for="nrc_code" class="mr-sm-2">Sender NRC:</label>
            <select class="form-control" id="nrc_code" name="nrc_code" onchange="populate(this.id, 'nrc_city')">
                <option value=""></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
            </select>
        </div>

        <div class="col-2">
            <label for="nrc_city" class="mr-sm-2" style="margin-top: 9%"></label>
            <select class="form-control" id="nrc_city" name="nrc_city">
                <option value=""></option>
            </select>
        </div>

        <div class="col-2">
            <label for="nrc_citizen" class="mr-sm-2" style="margin-top: 9%"></label>
            <select class="form-control" name="nrc_citizen" id="nrc_citizen" onchange="updatFullNrc()">
                <option value=""></option>
                <option value="C">C</option>
                <option value="AC">AC</option>
                <option value="NC">NC</option>
                <option value="V">V</option>
                <option value="M">M</option>
                <option value="N">N</option>
                <option value="P">P</option>
                <option value="E">E</option>
            </select>
        </div>

        <div class="col-2">
            <label for="nrc_number" class="mr-sm-2" style="margin-top: 9%"></label>
            <input type="text" class="form-control mb-2 mr-sm-2" placeholder="" id="nrc_number" name="nrc_number"
                value="{{ old('nrc_number') }}" onchange="updatFullNrc()">
        </div>

        <div class="col-2">
            <label for="receiver_address" class="mr-sm-2">Sender Address:</label>
            <input type="text" class="form-control mb-2 mr-sm-2" placeholder="" id="sender_address"
                name="sender_address" value="{{ old('sender_address') }}">
        </div>

        <div class="col-2">
            <label for="receiver_phno" class="mr-sm-2">Sender Phone:</label>
            <input type="text" class="form-control mb-2 mr-sm-2" placeholder="" id="sender_phno" name="sender_phno"
                value="{{ old('sender_phno') }}">
        </div>

        <div class="col-2">
            <label for="purpose_of_transaction">Purpose of Transaction</label>
            <select class="form-control" id="purpose_of_transaction" name="purpose_of_transaction">
                <option value="blank"></option>
                @foreach ($purposeOfTrans as $purposeOfTran)
                    <option value="{{ $purposeOfTran->purpose_name }}">{{ $purposeOfTran->purpose_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-2">
            <label for="state_division" class="mr-sm-2">State & Divison:</label>
            <select class="form-control" id="state_division" name="state_division">
                <option value=""></option>
                <option value="Ayeyarwady Region">Ayeyarwady Region</option>
                <option value="Bago Region">Bago Region</option>
                <option value="Chin State">Chin State</option>
                <option value="Kachin State">Kachin State</option>
                <option value="Kayah State">Kayah State</option>
                <option value="Kayin State">Kayin State</option>
                <option value="Magway Region">Magway Region</option>
                <option value="Mandalay Region">Mandalay Region</option>
                <option value="Mon State">Mon State</option>
                <option value="Naypyidaw Union Territory">Naypyidaw Union Territory</option>
                <option value="Rakhine State">Rakhine State</option>
                <option value="Sagaing Region">Sagaing Region</option>
                <option value="Shan State">Shan State</option>
                <option value="Tanintharyi Region">Tanintharyi Region</option>
                <option value="Yangon Region">Yangon Region</option>

            </select>
        </div>

        <div class="col-2">
            <label for="withdraw_point" class="mr-sm-2">Deposit Point:</label>
            <input type="text" class="form-control mb-2 mr-sm-2" placeholder="" id="deposit_point"
                name="deposit_point">
        </div>

        <div class="col-6">
            <label for="remark_withdraw_point" class="mr-sm-2">Remark for Deposit Point:</label>
            <textarea name="remark_deposit_point" class="form-control mb-2 mr-sm-2" id="remark_deposit_point" rows="5"
                cols="40" name="remark_deposit_point">{{ old('remark_deposit_point') }}</textarea>
        </div>

        <div class="col-12">
            <p class="text-bold" style="color:blue; font-size:20px">RECEIVER INFORMATION : </p>
        </div>

        <div class="col-3">
            <label for="sender_name" class="mr-sm-2">Receiver Name:</label>
            <input type="text" class="form-control mb-2 mr-sm-2" placeholder="" id="receiver_name"
                name="receiver_name" value="{{ old('receiver_name') }}">
        </div>

        <div class="col-3">
            <label for="nrc_id" class="mr-sm-2">NRC/Passport ID:</label>
            <input type="text" class="form-control mb-2 mr-sm-2" placeholder="" id="receiver_nrc_passport"
                name="receiver_nrc_passport" value="{{ old('receiver_nrc_passport') }}">
        </div>

        <div class="col-2">
            <label for="sender_country" class="mr-sm-2">Country</label>
            <input type="text" class="form-control mb-2 mr-sm-2" readonly placeholder="" id="receiver_country"
                name="receiver_country" value="">
        </div>

        <div class="col-4">
        </div>

 

        <div class="col-3">
            <label for="mmk" class="mr-sm-2">MMK</label>
            <input type="text" class="form-control mb-2 mr-sm-2" id="mmk" name="mmk_amount" value=""
                oninput="changecurrencyvaluemmk({{ $exchange_rates }})">
        </div>

        <div class="col-3">
            <label for="equivalent_usd" class="mr-sm-2" id="usdlabel">USD </label>
            <input type="text" class="form-control mb-2 mr-sm-2" id="usd" name="equivalent_usd" value=""
                oninput="changecurrencyvalueusd({{ $exchange_rates }})">
        </div>

        <div class="col-4">
            <label for="file" class="mr-sm-2">Upload File</label>
            <input type="file" class="form-control mb-2 mr-sm-2" id="file" name="file">
        </div>

        <div class="col-12">
            {!! Form::submit('Save', ['class' => 'btn btn-success','id'=>'savebut']) !!}
        </div>

    </div>

    {!! Form::close() !!}

@endsection

@section('scripts')
    <script src="cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="raw.githubusercontent.com/andiio/selectToAutocomplete/master/jquery-ui-autocomplete.js"></script>
    <script src="raw.githubusercontent.com/andiio/selectToAutocomplete/master/jquery.select-to-autocomplete.js"></script>
    <script src="raw.githubusercontent.com/andiio/selectToAutocomplete/master/jquery.select-to-autocomplete.min.js">
    </script>

    <script>
        (function($) {
            function floatLabel(inputType) {
                $(inputType).each(function() {
                    var $this = $(this);
                    // on focus add cladd active to label
                    $this.focus(function() {
                        $this.next().addClass("active");
                    });
                    //on blur check field and remove class if needed
                    $this.blur(function() {
                        if ($this.val() === '' || $this.val() === 'blank') {
                            $this.next().removeClass();
                        }
                    });
                });
            }
            // just add a class of "floatLabel to the input field!"
            floatLabel(".floatLabel");
        })(jQuery);

        function populate(s1, s2) {

            var s1 = document.getElementById('nrc_code');
            var s2 = document.getElementById('nrc_city');

            s2.innerHTML = "";

            if (s1.value == '1') {
                var optionArray = ['AhGaYa', 'BaMaNa', 'KhaPhaNa', 'DaPhaYa', 'HaPaNa',
                    'KaMaNa', 'KhaLaPha', 'LaGaNa', 'MaKhaBa', 'MaSaNa',
                    'MaNyaNa', 'MaKaTa', 'MaMaNa', 'MaKaNa', 'NaMaNa', 'PhaKaNa', 'PaTaAh', 'YaKaNa', 'SaBaNa',
                    'SaLaNa', 'SaPaBa', 'TaNaNa', 'WaMaNa', 'KaMaTa', 'KaPaTa', 'MaLaNa', 'PaNaDa', 'PaWaNa', 'SaDaNa',
                    'YaBaYa'
                ];
            } else if (s1.value == '2') {
                var optionArray = ['BaLaKha', 'DaMaSa', 'LaKaNa', 'MaSaNa', 'PhaSaNa', 'PhaYaSa', 'YaTaNa', 'MaSaNa',
                    'YaThaNa', 'MaSaNa', 'YaThaNa'
                ];
            } else if (s1.value == '3') {
                var optionArray = ['LaBaNa', 'KaKaYa', 'KaSaKa', 'KaDaNa', 'MaWaTa', 'PhaAhNa', 'BaAhNa', 'PhaPaNa',
                    'ThaTaNa', 'BaGaLa', 'BaThaSa', 'KaMaMa', 'LaThaNa', 'SaKaLa', 'ThaTaKa', 'WaLaMa', 'YaYaTha'
                ];
            } else if (s1.value == '4') {
                var optionArray = ['HaKhaNa', 'HtaTaLa', 'KaPaLa', 'MaTaPa', 'MaTaNa', 'PhaLaNa', 'PaLaWa', 'TaTaNa',
                    'TaZaNa', 'KaKhaNa', 'SaMaNa', 'YaKhaDa', 'YaZaNa'
                ];
            } else if (s1.value == '5') {
                var optionArray = ['AhYaTa', 'BaMaNa', 'BaTaLa', 'KhaOuNa', 'DaPaYa', 'HaMaLa', 'HtaKhaNa', 'AhTaNa',
                    'KaNaNa', 'KaThaNa', 'KaLaHta', 'KaLaWa', 'KaBaLa', 'KaLaTa', 'KhaTaNa', 'KhaOuTa', 'KaLaNa',
                    'MaLaNa', 'MaKaNa', 'MaYaNa', 'MaMaNa', 'NaYaNa', 'NgaZaNa', 'PaLaNa', 'PhaPaNa', 'PaLanBa',
                    'SaKaNa', 'SaLaKa', 'YaBaNa', 'TaMaNa', 'TaSaNa', 'WaLaNa', 'YaOuNa',
                    'YaMaPa', 'YaThaKa', 'DaHaNa', 'SaMaYa', 'HtaPaKha', 'KaMaNa', 'KhaPaNa', 'LaHaNa', 'LaYaNa',
                    'MaMaNa', 'MaPaLa', 'MaThaNa', 'PaSaNa', 'KaLaAh'
                ];
            } else if (s1.value == '6') {
                var optionArray = ['BaPaNa', 'HtaWaNa', 'KaLaAh', 'KhaMaKa', 'KaThaNa', 'KaSaNa',
                    'KaYaYa', 'LaLaNa', 'MaMaNa', 'MaAhNa', 'MaAhYa', 'NgaYaKa',
                    'PaKaMa', 'PaLaNa', 'PaLaTa', 'TaNaTha', 'TaThaYa', 'ThaYaKha',
                    'YaPhaNa',
                ];
            } else if (s1.value == '7') {
                var optionArray = ['AhPhaNa', 'AhPhaNa', 'AhTaNa', 'DaOuNa', 'HtaTaPa', 'KaTaTa',
                    'KaPaKa', 'KaKaNa', 'KaTaKha', 'KaKaNa', 'KaWaNa', 'LaPATa', 'MaDaNa', 'MaLaNa',
                    'MaNyaNa', 'NaTaLa', 'NyaLaPa', 'PaNaKa', 'PaKhaNa', 'PaTaNa',
                    'PaKhaTa', 'PaTaTa', 'PhaMaNa', 'PaMaNa', 'PaTaLa', 'PaTaSa', 'YaKaNa',
                    'YaTaNa', 'TaNgaNa', 'ThaNaPa', 'ThaKaNa', 'ThaWaTa', 'ThaSaNa',
                    'WaMaNa', 'YaTaYa', 'ZaKaNa'
                ];
            } else if (s1.value == '8') {
                var optionArray = ['AhLaNa', 'KhaMaNa', 'GaGaNa', 'SaPhaNa', 'SaPaWa', 'HtaLaNa',
                    'KaMaNa', 'MaKaNa', 'MaBaNa', 'MaLaNa', 'MaTaNa', 'MaMaNa', 'MaHtaNa', 'MaThaNa',
                    'NaMaNa', 'NgaPhaNa', 'PaKhaKa', 'PaMaNa', 'PaPhaNa', 'SaLaNa',
                    'SaTaYa', 'SaKaNa', 'TaTaKa', 'ThaYaNa', 'SaMaNa', 'YaNaKha', 'YaSaKa',
                    'KaHtaNa'
                ];
            } else if (s1.value == '9') {
                var optionArray = ['DaKhaTha', 'LaWaNa', 'OuTaTha', 'PaBaTha', 'PaMaNa', 'TaKaNa',
                    'ZaBaTha', 'ZaYaTha', 'AhMaYa', 'AhMaZa', 'KhaAhZa', 'KhaMaSa', 'KaPaTa', 'KaSaNa',
                    'MaLaNa', 'MaHaMa', 'MaNaMa', 'MaNaTa', 'MaYaMa', 'MaYaTa',
                    'MaTaYa', 'MaMaNa', 'MaHtaLa', 'MaKaNa', 'MaKhaNa', 'MaThaNa', 'NaHtaKa',
                    'NgaTaYa', 'NyaOuNa', 'PaLaNa', 'PaThaKa', 'PaBaNa', 'PaKaKha',
                    'PaOuLa', 'PaMaNa', 'ZaKaNa', 'SaKaNa', 'TaKaNa', 'TaTaOu', 'TaThaNa', 'ThaPaKa',
                    'ThaSaNa', 'WaTaNa', 'YaMaTha', 'NgaZaNa', 'PaBaNa', 'OoTaYa', 'KhAaHsa'
                ];
            } else if (s1.value == '10') {
                var optionArray = ['BaLaNa', 'KhaSaNa', 'KaMaYa', 'KaHtaNa', 'MaLaMa', 'MaDaNa', 'PaMaNa',
                    'ThaPhaYa', 'ThaHtaNa', 'KhaZaNa', 'LaMaNa', 'YaMaNa', 'KaKhaMa'
                ];
            } else if (s1.value == '11') {
                var optionArray = ['AaMaNa', 'BaThaTa', 'GaMaNa', 'KaPhaNa', 'KaTaNa', 'MaAhNa', 'MaTaNa', 'MaPaNa',
                    'MaOuNa', 'MaPaTa',
                    'PaTaNa', 'PaNaKa', 'SaTaNa', 'TaKaNa', 'ThaTaNa', 'YaBaNa', 'YaThaTa'
                ];
            } else if (s1.value == '12') {
                var optionArray = ['AaLaNa', 'BaHaNa', 'BaTaHta', 'KaKaKa', 'DaGaNa', 'DaGaYa', 'DaGaMa', 'DaSaKa',
                    'DaGaTa', 'DaLaNa',
                    'DaPaNa', 'LaMaNa', 'LaThaYa', 'LaKaNa', 'MaBaNa', 'HtaTaPa', 'AhSaNa', 'KaMaYa', 'KaMaNa',
                    'KhaYaNa', 'KaKhaKa', 'KaTaTa', 'KaTaNa', 'KaMaTa', 'LaMata', 'LaThaNa', 'MaYaKa', 'MaGaTa',
                    'MaGaDa', 'OuKaMa', 'PaBaTa', 'PaZaDa', 'SaKhaNa', 'SaKakha', 'SaKaNa', 'YaPaKa', 'YaPaTha',
                    'OuKaTa', 'TaKaNa', 'TaMaNa', 'ThaKaTa', 'ThaLaNa', 'ThaGaKa', 'ThaKhaNa', 'TaTaNa', 'YaKaNa',
                    'TaTaHta'
                ];
            } else if (s1.value == '13') {
                var optionArray = ['KhaYaHa', 'HaPaTa', 'HaPaNa', 'KaLaNa', 'KaLaTa', 'KaHaNa', 'KaThaNa', 'KaTaTa',
                    'KaTaNa', 'KaMaNa',
                    'KaKhaNa', 'LaYaNa', 'LaKaNa', 'LaKhaTa', 'LaKhaNa', 'LaLaNa', 'MaBaNa', 'MaKaNa', 'MaKhaNa',
                    'MaPHaNa', 'NaKhaNa', 'NaSaNa', 'NaPaNa', 'NaKhaTa', 'NyaYaNa', 'PhaKhaNa', 'PaLaNa', 'PaTaYa',
                    'SaSaNa', 'YaNyaNa', 'TaYaNa', 'TaMaNya', 'TaKhaLa', 'TaLaNa', 'TaKaNa', 'ThaNaNa', 'ThaPaNa',
                    'YaNgaNa', 'YaSaNa', 'TaMaNa', 'ThaKaTa', 'ThaLaNa', 'ThaGaKa', 'ThaKhaNa', 'TaTaNa', 'YaKaNa',
                    'TaTaHta', 'AhPaNa', 'AhTaNa', 'AhTaYa', 'HaHaNa', 'HaMaNa', 'KaLaHta', 'KaLaNa', 'MaHtaNa',
                    'MaKhaTa', 'MaNgaNa', 'MaPhaHta', 'NaTaYa', 'PaPaKa', 'PaWaNa', 'TaTaNa'
                ];
            } else if (s1.value == '14') {
                var optionArray = ['BaKaLa', 'DaNaPha', 'DaDaYa', 'PaThaYa', 'AhMaNa', 'HaKaKa', 'HaThaTa', 'AhGaPa',
                    'KaNaNa', 'KaLaNa',
                    'KaKhaNa', 'KaKaNa', 'KaPaNa', 'LaPaTa', 'LaMaNa', 'MaAhPa', 'MaMaKa', 'MaAhaNa', 'MaMaNa',
                    'NgaPaTa', 'NgaThaKha', 'NyaTaNa', 'PaTaNa', 'PhaPaNa', 'ThaPaNa', 'WaKhaMa', 'PaThaNa', 'YaKaNa',
                    'ZaLaNa', 'KaKaHta', 'AhMaTa', 'NgaYaKa', 'PaSaLa', 'YaThaYa'
                ];
            }


            for (var option in optionArray) {
                var newoption = document.createElement("option");

                newoption.value = optionArray[option];
                newoption.innerHTML = optionArray[option];
                s2.options.add(newoption);
            }
        }

        

        function changecurrencyvaluemmk(exchange_rates) {


            var mmk_input = document.getElementById("mmk");
            var usd_input = document.getElementById("usd");


            //mmk_input.value=0;
            usd_input.value = 0;


            var mmk_to_usd;



            var mmk_value;
            var usd_value;
            mmk_value = mmk_input.value;




            //find usd rate
            for (i = 0; i < exchange_rates.length; i++) {

                if (exchange_rates[i].currency_code == 'USD') {
                    mmk_to_usd = exchange_rates[i].exchange_rate;
                    break;
                }
            }






            usd_value = mmk_value * (1 / mmk_to_usd);
            usd_input.value = usd_value.toFixed(2);




        }


        function changecurrencyvalueusd(exchange_rates) {



            var mmk_input = document.getElementById("mmk");
            var usd_input = document.getElementById("usd");


            mmk_input.value = 0;
            //  usd_input.value=0;



            var mmk_to_usd;


            var usd_value = usd_input.value;
            var mmk_value;


            //find usd rate
            for (i = 0; i < exchange_rates.length; i++) {

                if (exchange_rates[i].currency_code == 'USD') {
                    mmk_to_usd = exchange_rates[i].exchange_rate;
                    break;
                }
            }

            mmk_value = usd_value * mmk_to_usd;
            mmk_input.value = mmk_value;

        }






       

    function changeUSDFormValue()
    {
        let exchange_rates=@json($exchange_rates);
        let usdLabel=document.getElementById('usdlabel');
      
        let input=document.getElementById('exchange_rate_input_usd');

                for (i = 0; i < exchange_rates.length; i++) {

        if (exchange_rates[i].currency_code == 'USD') {
            mmk_to_usd = exchange_rates[i].exchange_rate;
            break;
        }
        }
     
        usdLabel.innerText="USD (Rate:"+mmk_to_usd+")";
      input.value=mmk_to_usd;


    }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <script>
      jQuery(document).ready(function()
      {
        jQuery('#exampleFormControlSelect1').change(function()
        {
           let cid =jQuery(this).val();
           let str = cid;
           let makeStr = str.toString();
           let splitStr = makeStr.split(',');
          let country = document.getElementById('receiver_country');
          //  let currency = document.getElementById('prefer_currency');
            country.value = splitStr[0];
          //  currency.value = splitStr[1];
            let hiddenbranchid=document.getElementById('hidden_branch_id');
          hiddenbranchid.value=splitStr[2];
       
    
        });
      });
    </script>
    <script>
        jQuery(document).ready(
            function() {
                var s1 = document.getElementById('nrc_code');
                var s2 = document.getElementById('nrc_city');
                //nrc_code
                var nrcCodeParent = document.getElementById('nrc_code');
                var oldNrcCode = {{ old('nrc_code') }};
                if (oldNrcCode != null) {
                    var nrcChildren = nrcCodeParent.options;
                    for (let i = 0; i < nrcChildren.length; i++) {
                        if (i == oldNrcCode) {
                            nrcChildren[i].selected = true;
                            break;
                        }
                        //purpose of transaction
                    }

                }
                populate(s1, s2);
            }
        )
    </script>
    <script>
        changeUSDFormValue();
    </script>

<script>
    usdValue=0;
    var daily_warning=document.getElementById('daily_max_warning');
    var monthly_warning=document.getElementById('monthly_max_warning');
     $(document).ready(function(){
      $('#usd').change(function(){
         usdValue=$('#usd').val();
         disableSave(usdValue);
      });
      $('#mmk').change(function(){
        usdValue=$('#usd').val();
        disableSave(usdValue);
      });
     
     }
     )
  </script>
  
  
  {{-- Check Max Limit --}}
  <script>
   var par_transaction=@json($par_transaction);
   var par_month_transaction=@json($par_month_transaction);
   var sum_usd_grouped_by_nrc=@json($sum_usd_grouped_by_nrc);
   //console.log(sum_usd_grouped_by_nrc);
   var fullNrc;
   // sender_nrc=document.getElementById('nrc_id').value;

 
   function disableSave(value)
   {
   // console.log('dis');
      
      saveButton=document.getElementById('savebut');
      if(value>Number(par_transaction))
      {
        saveButton.disabled = true;
        daily_warning.hidden=false;
       
      }
      else
      {
       var sender_nrc=fullNrc;
        console.log('dis');
     let total=Number(getUSDByNRC(sender_nrc))+Number(value);
  
       if( Number(total)>Number(par_month_transaction))
       {
        saveButton.disabled = true;
        monthly_warning.hidden=false;
       }
       else
       {
         saveButton.disabled = false;
         daily_warning.hidden=true;
         monthly_warning.hidden=true;
         
       }
      }
      
  
    }
  
   
  </script>
  
  <script>
     function getUSDByNRC(nrc)
    {
      let usdVal=0;
      console.log(sum_usd_grouped_by_nrc);
      sum_usd_grouped_by_nrc.forEach(element => {
      // console.log('called');
        if(element.id==nrc)
        {
          usdVal=element.usd;
          console.log(usdVal);
        }
        
        
      });

  
     return usdVal;
    }
  </script>
  
  <script>
    function updatFullNrc()
    {
        nrc_code=document.getElementById('nrc_code').value;
        nrc_city=document.getElementById('nrc_city').value;
        nrc_citizen=document.getElementById('nrc_citizen').value;
        nrc_number=document.getElementById('nrc_number').value;
        // $nrc_code . '/' . $nrc_city . '(' . $nrc_citizen . ')' . $nrc_number;

        if(!nrc_code||!nrc_city||!nrc_citizen)
        {
            fullNrc=nrc_number;
            
        }
        else
        {
            fullNrc=nrc_code+'/'+nrc_city+'('+nrc_citizen+')'+nrc_number;
        }
        //console.log(fullNrc);

    }
  </script>
  
@endsection
