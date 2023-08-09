<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExport;
use App\Exports\ReportsExportText30;
use App\Exports\ReportsExport_TotalInward;
use App\Exports\Total_inward;
use App\Models\Branch;
use App\Models\Dates;
use App\Models\ExchangeRate;
use App\Models\Inwards;
use App\Models\InwardTransaction;
use App\Models\PurposeOfTrans;
use App\Models\TotalInward;
use App\Models\blacklists;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\Storage;

class InwardTransactionController extends Controller
{

    public function inwardtransaction()
    {
        if (auth()->user()->type == 'editor' && auth()->user()->inward_trans == 1)
        {
            $inwardtransactions = Inwards::select("*")
            ->whereDate('created_at', Carbon::today())
            ->get();
            $blacklists = blacklists::All();

           $is_text30_valid= $this->isText30_valid(Carbon::today());
            return view('admin.dailytransaction.inwardtransaction')->with('inward_transactions', $inwardtransactions)->with('blacklists', $blacklists)
                                                                   ->with('is_text30_valid', $is_text30_valid);

        }
        else
        {
          return back()->with('status', 'You do not have access');
        }

    }


    public function addinwardtransaction()
    {
         $inwardtransactions = Inwards::all();

         if($inwardtransactions != null)
         {
             $inwardtransaction = Inwards::latest()->value('sr_id');
         }
         else
         {
             $inwardtransaction = 1;
         }

        $purposeOfTrans = PurposeOfTrans::all();
        $branches = Branch::All();
        $exhange_rates=ExchangeRate::all();

        // dd($exhange_rates);
       //dd($inwardtransaction);

        $usd = DB::table('exchange_rates')->where('currency_code', 'USD')
            ->value('exchange_rate');

        $thb = DB::table('exchange_rates')->where('currency_code', 'THB')
            ->value('exchange_rate');



        return view('admin.dailytransaction.addinwardtransaction')
            ->with('purposeOfTrans', $purposeOfTrans)->with('branches', $branches)
            ->with('exchange_rates',$exhange_rates)
            ->with('inwardtransaction',$inwardtransaction)
            ->with('usd', $usd)->with('thb', $thb)
            ->with('is_text30_valid',$this->isText30_valid(Carbon::today()));


    }

    public function saveinwardtransaction(Request $request)
    {
         $this->validate($request, ['sr_id' => 'required',
                             'receiver_name'=>'required',
                             'branch_id'=>'required',
                             'nrc_number'=>'required',
                             'receiver_address'=>'required',
                             'receiver_phno'=>'required',
                             'purpose_of_transaction'=>'required',
                             'withdraw_point'=>'required',
                             'remark_withdraw_point'=>'required',
                             'sender_name'=>'required',
                             'sender_nrc_passport'=> 'required',
                             'sender_country'=>'required',
                             'prefer_currency'=>'required',
                             'amount'=>'required',
                             'equivalent_usd'=>'required',
                             'mmk_amount'=>'required',
                             'state_division'=>'required',
                             'exchange_rate_input'=>'required',
                             'exchange_rate_input_usd'=>'required'

                            ]);
    if($request->hasfile('file'))
    {
     $fileName = $request->file('file')->getClientOriginalName();

     $ext = $request->file('file')->getClientOriginalExtension();

     $fileName = pathinfo($fileName, PATHINFO_FILENAME);

     $fileNameToStore = $fileName.'_'.time().'.'.$ext;

     $path = Storage::putFileAs('public/files', $request->file('file'), $fileNameToStore);
    }
    else
    {
        $fileNameToStore = null;
    }

    $nrc_code =  $request->input('nrc_code');
    $nrc_city =  $request->input('nrc_city');
    $nrc_citizen =  $request->input('nrc_citizen');
    $nrc_number =  $request->input('nrc_number');

    if($nrc_code===null && $nrc_citizen===null && $nrc_city===null)
    {
        $nrc=$nrc_number;
    }
    else
    {
        $nrc = $nrc_code.'/'.$nrc_city.'('.$nrc_citizen.')'.$nrc_number;

    }

        $blacklist_user = blacklists::where('nrc_passportno', '=', $nrc)->first();

         $inwardtransaction = new Inwards();
         $inwardtransaction->sr_id = $request->input('sr_id');
         $inwardtransaction->branch_id=$request->input('hidden_branch_id');
         $inwardtransaction->receiver_name = $request->input('receiver_name');
         $inwardtransaction->receiver_nrc_passport = $nrc;
         $inwardtransaction->receiver_address_ph =$request->input('receiver_address').'/'.$request->input('receiver_phno');
         $inwardtransaction->purpose= $request->input('purpose_of_transaction');
         $inwardtransaction->withdraw_point = $request->input('withdraw_point');
         $inwardtransaction->remark_for_withdraw_point = $request->input('remark_withdraw_point');
         $inwardtransaction->sender_name = $request->input('sender_name');
         $inwardtransaction->sender_nrc_passport = $request->input('sender_nrc_passport');
         $inwardtransaction->sender_country_code = $request->input('sender_country');
         $inwardtransaction->currency_code = $request->input('prefer_currency');
         $inwardtransaction->amount = $request->input('amount');
         $inwardtransaction->equivalent_usd = $request->input('equivalent_usd');
         $inwardtransaction->txd_date_time=$request->input('date');
         $inwardtransaction->amount_mmk = $request->input('mmk_amount');
         $inwardtransaction->txd_date_time = $request->input('date');
         $inwardtransaction->state_division=$request->input('state_division');
         $inwardtransaction->file = $fileNameToStore;
         $inwardtransaction->status = 0;
         $inwardtransaction->exchange_rate=$request->input('exchange_rate_input');
         $inwardtransaction->exchange_rate_usd=$request->input('exchange_rate_input_usd');

         if ($this->isText30_valid_today()) {
          $inwardtransaction->mmk_allowance=$request->input('mmk_allowance');
          $inwardtransaction->total_mmk_amount=$request->input('total_mmk_amount');
         }

        if($blacklist_user == null)
        {

            $inwardtransaction->save();

            return back()->with('status', 'Inward Transaction has been added!');
        }
        else
        {
            return back()->with('status', 'Blacklist User');
        }


    }


    public function editinwardtransaction($id)
    {
        $inwardtransaction = Inwards::find($id);
        $purposeOfTrans = PurposeOfTrans::all();
        $branches = Branch::All();
        $exchange_rates=ExchangeRate::all();

        $usd = DB::table('exchange_rates')->where('currency_code', 'USD')
            ->value('exchange_rate');

        $thb = DB::table('exchange_rates')->where('currency_code', 'THB')
            ->value('exchange_rate');

        return view('admin.dailytransaction.editinwardtransaction')->with('purposeOfTrans', $purposeOfTrans)->with('branches', $branches)
            ->with('inward_transaction', $inwardtransaction)
            ->with('usd', $usd)->with('thb', $thb)
            ->with('exchange_rates',$exchange_rates)
            ->with('is_text30_valid',$this->isText30_valid($inwardtransaction->created_at));;

    }

    public function updateinwardtransaction(Request $request)
    {
        $this->validate($request, ['sr_id' => 'required',
                             'receiver_name'=>'required',
                             'branch_id'=>'required',
                             'nrc_number'=>'required',
                             'receiver_address'=>'required',
                             'receiver_phno'=>'required',
                             'purpose_of_transaction'=>'required',
                             'withdraw_point'=>'required',
                             'remark_withdraw_point'=>'required',
                             'sender_name'=>'required',
                             'sender_nrc_passport'=> 'required',
                             'sender_country'=>'required',
                             'prefer_currency'=>'required',
                             'amount'=>'required',
                             'equivalent_usd'=>'required',
                             'mmk_amount'=>'required',
                             'state_division'=>'required'
                            ]);

    if($request->hasfile('file'))
    {
     $fileName = $request->file('file')->getClientOriginalName();

     $ext = $request->file('file')->getClientOriginalExtension();

     $fileName = pathinfo($fileName, PATHINFO_FILENAME);

     $fileNameToStore = $fileName.'_'.time().'.'.$ext;

     $path = Storage::putFileAs('public/files', $request->file('file'), $fileNameToStore);
    }
    else
    {
        $fileNameToStore = null;
    }

        $nrc_code = $request->input('nrc_code');
        $nrc_city = $request->input('nrc_city');
        $nrc_citizen = $request->input('nrc_citizen');
        $nrc_number = $request->input('nrc_number');

        if($nrc_code==null && $nrc_citizen==null && $nrc_city==null)
        {
            $nrc=$nrc_number;
        }
        else
        {
            $nrc = $nrc_code.'/'.$nrc_city.'('.$nrc_citizen.')'.$nrc_number;

        }

        $inwardtransaction = Inwards::find($request->input('id'));

        $inwardtransaction->sr_id = $request->input('sr_id');
         $inwardtransaction->branch_id=$request->input('hidden_branch_id');
         $inwardtransaction->receiver_name = $request->input('receiver_name');
         $inwardtransaction->receiver_nrc_passport = $nrc;
         $inwardtransaction->receiver_address_ph =$request->input('receiver_address').'/'.$request->input('receiver_phno');
         $inwardtransaction->purpose= $request->input('purpose_of_transaction');
         $inwardtransaction->withdraw_point = $request->input('withdraw_point');
         $inwardtransaction->remark_for_withdraw_point = $request->input('remark_withdraw_point');
         $inwardtransaction->sender_name = $request->input('sender_name');
         $inwardtransaction->sender_nrc_passport = $request->input('sender_nrc_passport');
         $inwardtransaction->sender_country_code = $request->input('sender_country');
         $inwardtransaction->currency_code = $request->input('prefer_currency');
         $inwardtransaction->amount = $request->input('amount');
         $inwardtransaction->equivalent_usd = $request->input('equivalent_usd');
         $inwardtransaction->txd_date_time=$request->input('date');
         $inwardtransaction->amount_mmk = $request->input('mmk_amount');
         $inwardtransaction->txd_date_time = $request->input('date');
         $inwardtransaction->state_division=$request->input('state_division');
         $inwardtransaction->file = $fileNameToStore;
        $inwardtransaction->status = 0;
        if ($this->isText30_valid_today()) {
            $inwardtransaction->mmk_allowance=$request->input('mmk_allowance');
            $inwardtransaction->total_mmk_amount=$request->input('total_mmk_amount');
           }
        $inwardtransaction->update();

        return back()->with('status', 'Inward Transaction has been updated!');
    }

    public function deleteinwardtransaction($id)
    {
        $inwardtransactions = Inwards::find($id);

        $inwardtransactions->delete();

        return back()->with('status', 'Inward Transaction has been deleted!');
    }

    public function approveinwardtransaction()
    {
        if (auth()->user()->type == 'editor' && auth()->user()->inward_approve == 1)
        {
            $inwardtransactions = Inwards::All()->where('status', 0);
          
            return view('admin.dailytransaction.approveinwardtransaction')->with('inwardtransactions', $inwardtransactions);

        }
        else
        {
          return back()->with('status', 'You do not have access');
        }

    }

    public function inward()
    {
        if (auth()->user()->type == 'editor' && auth()->user()->inward == 1)
        {
         $inwardtransactions = Inwards::All()->where('status',1)->groupBy(function($data)
        {
            return $data->created_at->format('Y-m-d');
        })->paginate(3);

        $ttlamount=Inwards::where('status',1)->sum('amount');
        $ttlusd=Inwards::where('status',1)->sum('equivalent_usd');
        $ttlmmk=Inwards::where('status',1)->sum('amount_mmk');

            $branches=Branch::all();

            if ($this->isText30_valid(Carbon::today())) {

                $excel_query=Inwards::select('sr_id','branch_id','receiver_name','receiver_nrc_passport','receiver_address_ph','purpose','withdraw_point','sender_name','sender_country_code','currency_code','amount','equivalent_usd','amount_mmk','mmk_allowance','total_mmk_amount','exchange_rate','exchange_rate_usd','txd_date_time','status','created_at')->get()->where('status',1)->groupBy(function($data)
                {
                    return $data->created_at->format('Y-m-d');
                });
            }
            else
            {
                $excel_query=Inwards::select('sr_id','branch_id','receiver_name','receiver_nrc_passport','receiver_address_ph','purpose','withdraw_point','sender_name','sender_country_code','currency_code','amount','equivalent_usd','amount_mmk','exchange_rate','exchange_rate_usd','txd_date_time','status','created_at')->get()->where('status',1)->groupBy(function($data)
                {
                    return $data->created_at->format('Y-m-d');
                });
            }
          

            $grandtotalusd=0;
            $grandtotalmmk=0;
            $index=0;






            $total_currency_codes=array();
            $total_collection=collect();


            foreach ($inwardtransactions as $key=>$dated_transactions) {

                $currency_codes_in_date=array();

                foreach ($dated_transactions as $transaction) {
                    if(!in_array($transaction->currency_code,$total_currency_codes))
                    {
                        array_push($total_currency_codes,$transaction->currency_code);
                    }
                }


                foreach ($dated_transactions as $transaction) {
                    if(!in_array($transaction->currency_code,$currency_codes_in_date))
                    {
                        array_push($currency_codes_in_date,$transaction->currency_code);
                    }
                }

                $sub_total_collection=collect();
                foreach ($currency_codes_in_date as $currency_code) {
                 $subtotal_amount= $dated_transactions->where('currency_code',$currency_code)->sum('amount');
                 $sub_equsd= $dated_transactions->where('currency_code',$currency_code)->sum('equivalent_usd');
                 $sub_eqmmk= $dated_transactions->where('currency_code',$currency_code)->sum('amount_mmk');

                 $mmk_allowance= $dated_transactions->where('currency_code',$currency_code)->sum('mmk_allowance');
                 $total_mmk_amount= $dated_transactions->where('currency_code',$currency_code)->sum('total_mmk_amount');
                
                 $sub_total_collection->put($currency_code,['amount'=>$subtotal_amount,'mmk_allowance'=>$mmk_allowance,'total_mmk_amount'=>$total_mmk_amount,'equivalent_usd'=>$sub_equsd,'amount_mmk'=>$sub_eqmmk]);
                 $dated_transactions->put('subtotal',$sub_total_collection);
                }


            }


            foreach ($total_currency_codes as $currency_code) {

                $total_amounts=Inwards::where('status',1)->where('currency_code',$currency_code)->sum('amount');
                $total_equsd=Inwards::where('status',1)->where('currency_code',$currency_code)->sum('equivalent_usd');
                $total_eqmmk=Inwards::where('status',1)->where('currency_code',$currency_code)->sum('amount_mmk');
                $total_collection->put($currency_code,['amount'=>$total_amounts,'equivalent_usd'=>$total_equsd,'amount_mmk'=>$total_eqmmk]);


            }
            foreach ($excel_query as $query => $collection)
            {
                $subtotal_amount_array=array();
                $subtotalamk=0;
                $subtotalusd=0;
                $subtotalmmk=0;



                for ($i=0; $i <=count($collection) ; $i++) {

                    if($i==count($collection))
                    {
                        $collection->put($i,collect());
                        $collection[$i]->put('sr_id','');
                        $collection[$i]->put('receiver_name','SubTotal');

                        $increment=0;
                        foreach ($subtotal_amount_array as $key => $value)
                        {

                            $collection[$i]->put('key'.$increment++,$key);
                            $collection[$i]->put($key,$value);
                        }

                        $collection[$i]->put('a','Equivalent USD');
                        $collection[$i]->put('equivalent_usd',$subtotalusd);
                        $collection[$i]->put('b','MMK amount');
                        $collection[$i]->put('amount_mmk',$subtotalmmk);
                        $collection[$i]->put('exchange_rate','');
                        $collection[$i]->put('exchange_rate_usd','');
                        break;


                    }
                    else
                    {
                        if(!array_key_exists($collection[$i]->currency_code,$subtotal_amount_array))
                        {
                            $subtotal_amount_array[$collection[$i]->currency_code]=$collection[$i]->amount;

                        }
                        else
                        {
                            $subtotal_amount_array[$collection[$i]->currency_code]+=$collection[$i]->amount;
                        }
                        $subtotalamk+=$collection[$i]->amount;
                        $subtotalusd+=$collection[$i]->equivalent_usd;
                        $subtotalmmk+=$collection[$i]->amount_mmk;
                        $grandtotalmmk+=$collection[$i]->amount_mmk;
                        $grandtotalusd+=$collection[$i]->equivalent_usd;

                        unset($collection[$i]->created_at);
                        unset($collection[$i]->status);

                    }



                }
                $index++;
                $sec_index=0;
                if($index==count($excel_query))
                {

                  foreach ($total_collection as $key => $value) {
                    $sec_index++;
                    $collection->put($index+$sec_index,collect());
                    $collection[$index+$sec_index]->put('sr_id','');
                    $collection[$index+$sec_index]->put('receiver_name','GrandTotal '.$key);

                    $collection[$index+$sec_index]->put('a','Amount');
                    $collection[$index+$sec_index]->put('equivalent_usd',$value['amount']);
                    $collection[$index+$sec_index]->put('b','Equivalent USD');
                    $collection[$index+$sec_index]->put('usd',$value['equivalent_usd']);
                    $collection[$index+$sec_index]->put('c','MMK amount');
                    $collection[$index+$sec_index]->put('amount_mmk',$value['amount_mmk']);


                  }

                    $collection->put($index+$sec_index+1,collect());
                    $collection[$index+$sec_index+1]->put('sr_id','');
                    $collection[$index+$sec_index+1]->put('receiver_name','GrandTotal');
                    $collection[$index+$sec_index+1]->put('a','');
                    $collection[$index+$sec_index+1]->put('b','');
                    $collection[$index+$sec_index+1]->put('c','Equivalent USD');
                    $collection[$index+$sec_index+1]->put('equivalent_usd',$grandtotalusd);
                    $collection[$index+$sec_index+1]->put('d','MMK amount');
                    $collection[$index+$sec_index+1]->put('amount_mmk',$grandtotalmmk);
                    $collection[$index+$sec_index+1]->put('exchange_rate','');
                    $collection[$index+$sec_index+1]->put('exchange_rate_usd','');

                }





            }





            session()->put('query', $excel_query);

            return view('admin.reports.inward')
                ->with('inward_transactions', $inwardtransactions)
                ->with('ttlamount',$ttlamount)
                ->with('ttlusd',$ttlusd)
                ->with('ttlmmk',$ttlmmk)
                ->with('branches',$branches)
                ->with('total_collection',$total_collection)
                ->with('is_text30_valid',$this->isText30_valid_today());

        }
        else
        {
          return back()->with('status', 'You do not have access');
        }

    }

    public function inwardwithbranch(Request $request)
    {
        $branch_id=$request->input('branch_id');
        $branches=Branch::all();

        $inwardtransactions = DB::table('inwards')->select()->where('branch_id',$branch_id)
            ->get();

            session()->put('query', $inwardtransactions);


        return view('admin.reports.inward')
        ->with('inward_transactions', $inwardtransactions)
        ->with('branches',$branches);


    }



    public function searchinward(Request $request)
    {
        $branches=Branch::all();
        $startdate = $request->input('startdate').' 00:00:00';
        $enddate = $request->input('enddate').' 24:00:00';
        $branch_id=null;
        $state_division=null;
        if($request->input('branch_id')!=null)
        {
            $branch_id=$request->input('branch_id');

        }
        if($request->input('state_division')!=null)
        {
            $state_division=$request->input('state_division');
        }

    if(!is_null($branch_id) && !is_null($state_division))
    {

        $query = Inwards::All()->where('status',1)
                    ->where('branch_id',$branch_id)
                    ->where('state_division',$state_division)
                    ->where('created_at', '>=', $startdate)
                    ->where('created_at', '<=', $enddate)->groupBy(function($data)
                                {
                                    return $data->created_at->format('Y-m-d');
                                })->paginate(1000);






        $ttlamount=Inwards::where('status',1)->where('branch_id',$branch_id)->where('state_division',$state_division)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount');

        $ttlusd=Inwards::where('status',1)->where('branch_id',$branch_id)->where('state_division',$state_division)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('equivalent_usd');

        $ttlmmk=Inwards::where('status',1)->where('branch_id',$branch_id)->where('state_division',$state_division)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount_mmk');


        $excel_query=Inwards::select('sr_id','branch_id','receiver_name','state_division','receiver_nrc_passport','receiver_address_ph','purpose','withdraw_point','sender_name','sender_country_code','currency_code','amount','equivalent_usd','amount_mmk','exchange_rate','exchange_rate_usd','txd_date_time','status','created_at')->get()->where('status',1)->where('branch_id',$branch_id)->where('state_division',$state_division)->where('created_at', '>=', $startdate)
        ->where('created_at', '<=', $enddate)->groupBy(function($data)
            {
                return $data->created_at->format('Y-m-d');
            });
            // dd($excel_query);




            $total_currency_codes=array();
            $total_collection=collect();
            foreach ($query as $key=>$dated_transactions) {

                $currency_codes_in_date=array();

                foreach ($dated_transactions as $transaction) {
                    if(!in_array($transaction->currency_code,$total_currency_codes))
                    {
                        array_push($total_currency_codes,$transaction->currency_code);
                    }
                }


                foreach ($dated_transactions as $transaction) {
                    if(!in_array($transaction->currency_code,$currency_codes_in_date))
                    {
                        array_push($currency_codes_in_date,$transaction->currency_code);
                    }
                }

                $sub_total_collection=collect();
                foreach ($currency_codes_in_date as $currency_code) {
                 $subtotal_amount= $dated_transactions->where('currency_code',$currency_code)->sum('amount');
                 $sub_equsd= $dated_transactions->where('currency_code',$currency_code)->sum('equivalent_usd');
                 $sub_eqmmk= $dated_transactions->where('currency_code',$currency_code)->sum('amount_mmk');
                 $mmk_allowance= $dated_transactions->where('currency_code',$currency_code)->sum('mmk_allowance');
                 $total_mmk_amount= $dated_transactions->where('currency_code',$currency_code)->sum('total_mmk_amount');
                
                 $sub_total_collection->put($currency_code,['amount'=>$subtotal_amount,'mmk_allowance'=>$mmk_allowance,'total_mmk_amount'=>$total_mmk_amount,'equivalent_usd'=>$sub_equsd,'amount_mmk'=>$sub_eqmmk]);
                 $dated_transactions->put('subtotal',$sub_total_collection);
                }


            }

            foreach ($total_currency_codes as $currency_code) {

                $total_amounts=Inwards::where('status',1)->where('state_division',$state_division)->where('branch_id',$branch_id)->where('currency_code',$currency_code)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount');
                $total_equsd=Inwards::where('status',1)->where('state_division',$state_division)->where('branch_id',$branch_id)->where('currency_code',$currency_code)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('equivalent_usd');
                $total_eqmmk=Inwards::where('status',1)->where('state_division',$state_division)->where('branch_id',$branch_id)->where('currency_code',$currency_code)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount_mmk');
                $total_collection->put($currency_code,['amount'=>$total_amounts,'equivalent_usd'=>$total_equsd,'amount_mmk'=>$total_eqmmk]);


            }
            $grandtotalusd=0;
            $grandtotalmmk=0;
            $index=0;
            foreach ($excel_query as $eq => $collection)
            {
                $subtotal_amount_array=array();
                $subtotalamk=0;
                $subtotalusd=0;
                $subtotalmmk=0;



                for ($i=0; $i <=count($collection) ; $i++) {

                    if($i==count($collection))
                    {
                        $collection->put($i,collect());
                        $collection[$i]->put('sr_id','');
                        $collection[$i]->put('receiver_name','SubTotal');

                        $increment=0;
                        foreach ($subtotal_amount_array as $key => $value)
                        {

                            $collection[$i]->put('key'.$increment++,$key);
                            $collection[$i]->put($key,$value);
                        }

                        $collection[$i]->put('a','Equivalent USD');
                        $collection[$i]->put('equivalent_usd',$subtotalusd);
                        $collection[$i]->put('b','MMK amount');
                        $collection[$i]->put('amount_mmk',$subtotalmmk);
                        $collection[$i]->put('exchange_rate','');
                        $collection[$i]->put('exchange_rate_usd','');
                        break;


                    }
                    else
                    {
                        if(!array_key_exists($collection[$i]->currency_code,$subtotal_amount_array))
                        {
                            $subtotal_amount_array[$collection[$i]->currency_code]=$collection[$i]->amount;

                        }
                        else
                        {
                            $subtotal_amount_array[$collection[$i]->currency_code]+=$collection[$i]->amount;
                        }
                        $subtotalamk+=$collection[$i]->amount;
                        $subtotalusd+=$collection[$i]->equivalent_usd;
                        $subtotalmmk+=$collection[$i]->amount_mmk;
                        $grandtotalmmk+=$collection[$i]->amount_mmk;
                        $grandtotalusd+=$collection[$i]->equivalent_usd;

                        unset($collection[$i]->created_at);
                        unset($collection[$i]->status);
                        unset($collection[$i]->state_division);

                    }



                }
                $index++;


            }
            $sec_index=0;
            $place=0;

              foreach ($total_collection as $key => $value) {
                $sec_index++;
                $place=count($excel_query)+$sec_index;

                $excel_query->put($place,collect());
                $excel_query[$place]->put('sr_id','');
                $excel_query[$place]->put('receiver_name','GrandTotal '.$key);

                $excel_query[$place]->put('a','Amount');
                $excel_query[$place]->put('equivalent_usd',$value['amount']);
                $excel_query[$place]->put('b','Equivalent USD');
                $excel_query[$place]->put('usd',$value['equivalent_usd']);
                $excel_query[$place]->put('c','MMK amount');
                $excel_query[$place]->put('amount_mmk',$value['amount_mmk']);


              }

                $excel_query->put($place+1,collect());
                $excel_query[$place+1]->put('sr_id','');
                $excel_query[$place+1]->put('receiver_name','GrandTotal');
                $excel_query[$place+1]->put('a','');
                $excel_query[$place+1]->put('b','');
                $excel_query[$place+1]->put('c','Equivalent USD');
                $excel_query[$place+1]->put('equivalent_usd',$grandtotalusd);
                $excel_query[$place+1]->put('d','MMK amount');
                $excel_query[$place+1]->put('amount_mmk',$grandtotalmmk);
                $excel_query[$place+1]->put('exchange_rate','');
                $excel_query[$place+1]->put('exchange_rate_usd','');





    }else if(!is_null($branch_id) && is_null($state_division))
    {

                    $query = Inwards::All()->where('status',1)
                    ->where('branch_id',$branch_id)->where('created_at', '>=', $startdate)
                    ->where('created_at', '<=', $enddate)->groupBy(function($data)
                                {
                                    return $data->created_at->format('Y-m-d');
                                })->paginate(1000);

        $ttlamount=Inwards::where('status',1)->where('branch_id',$branch_id)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount');
        $ttlusd=Inwards::where('status',1)->where('branch_id',$branch_id)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('equivalent_usd');
        $ttlmmk=Inwards::where('status',1)->where('branch_id',$branch_id)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount_mmk');


        $excel_query=Inwards::select('sr_id','branch_id','receiver_name','state_division','receiver_nrc_passport','receiver_address_ph','purpose','withdraw_point','sender_name','sender_country_code','currency_code','amount','equivalent_usd','amount_mmk','exchange_rate','exchange_rate_usd','txd_date_time','status','created_at')->get()->where('status',1)->where('branch_id',$branch_id)->where('created_at', '>=', $startdate)
        ->where('created_at', '<=', $enddate)->groupBy(function($data)
            {
                return $data->created_at->format('Y-m-d');
            });
            // dd($excel_query);




            $total_currency_codes=array();
            $total_collection=collect();
            foreach ($query as $key=>$dated_transactions) {

                $currency_codes_in_date=array();

                foreach ($dated_transactions as $transaction) {
                    if(!in_array($transaction->currency_code,$total_currency_codes))
                    {
                        array_push($total_currency_codes,$transaction->currency_code);
                    }
                }


                foreach ($dated_transactions as $transaction) {
                    if(!in_array($transaction->currency_code,$currency_codes_in_date))
                    {
                        array_push($currency_codes_in_date,$transaction->currency_code);
                    }
                }

                $sub_total_collection=collect();
                foreach ($currency_codes_in_date as $currency_code) {
                 $subtotal_amount= $dated_transactions->where('currency_code',$currency_code)->sum('amount');
                 $sub_equsd= $dated_transactions->where('currency_code',$currency_code)->sum('equivalent_usd');
                 $sub_eqmmk= $dated_transactions->where('currency_code',$currency_code)->sum('amount_mmk');
                 $mmk_allowance= $dated_transactions->where('currency_code',$currency_code)->sum('mmk_allowance');
                 $total_mmk_amount= $dated_transactions->where('currency_code',$currency_code)->sum('total_mmk_amount');
                
                 $sub_total_collection->put($currency_code,['amount'=>$subtotal_amount,'mmk_allowance'=>$mmk_allowance,'total_mmk_amount'=>$total_mmk_amount,'equivalent_usd'=>$sub_equsd,'amount_mmk'=>$sub_eqmmk]);
                 $dated_transactions->put('subtotal',$sub_total_collection);
                }


            }

            foreach ($total_currency_codes as $currency_code) {

                $total_amounts=Inwards::where('status',1)->where('branch_id',$branch_id)->where('currency_code',$currency_code)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount');
                $total_equsd=Inwards::where('status',1)->where('branch_id',$branch_id)->where('currency_code',$currency_code)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('equivalent_usd');
                $total_eqmmk=Inwards::where('status',1)->where('branch_id',$branch_id)->where('currency_code',$currency_code)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount_mmk');
                $total_collection->put($currency_code,['amount'=>$total_amounts,'equivalent_usd'=>$total_equsd,'amount_mmk'=>$total_eqmmk]);


            }
            $grandtotalusd=0;
            $grandtotalmmk=0;
            $index=0;
            foreach ($excel_query as $eq => $collection)
            {
                $subtotal_amount_array=array();
                $subtotalamk=0;
                $subtotalusd=0;
                $subtotalmmk=0;



                for ($i=0; $i <=count($collection) ; $i++) {

                    if($i==count($collection))
                    {
                        $collection->put($i,collect());
                        $collection[$i]->put('sr_id','');
                        $collection[$i]->put('receiver_name','SubTotal');

                        $increment=0;
                        foreach ($subtotal_amount_array as $key => $value)
                        {

                            $collection[$i]->put('key'.$increment++,$key);
                            $collection[$i]->put($key,$value);
                        }

                        $collection[$i]->put('a','Equivalent USD');
                        $collection[$i]->put('equivalent_usd',$subtotalusd);
                        $collection[$i]->put('b','MMK amount');
                        $collection[$i]->put('amount_mmk',$subtotalmmk);
                        $collection[$i]->put('exchange_rate','');
                        $collection[$i]->put('exchange_rate_usd','');
                        break;


                    }
                    else
                    {
                        if(!array_key_exists($collection[$i]->currency_code,$subtotal_amount_array))
                        {
                            $subtotal_amount_array[$collection[$i]->currency_code]=$collection[$i]->amount;

                        }
                        else
                        {
                            $subtotal_amount_array[$collection[$i]->currency_code]+=$collection[$i]->amount;
                        }
                        $subtotalamk+=$collection[$i]->amount;
                        $subtotalusd+=$collection[$i]->equivalent_usd;
                        $subtotalmmk+=$collection[$i]->amount_mmk;
                        $grandtotalmmk+=$collection[$i]->amount_mmk;
                        $grandtotalusd+=$collection[$i]->equivalent_usd;

                        unset($collection[$i]->created_at);
                        unset($collection[$i]->status);
                        unset($collection[$i]->state_division);


                    }



                }
                $index++;



            }
            $sec_index=0;
            $place=0;

              foreach ($total_collection as $key => $value) {
                $sec_index++;
                $place=count($excel_query)+$sec_index;

                $excel_query->put($place,collect());
                $excel_query[$place]->put('sr_id','');
                $excel_query[$place]->put('receiver_name','GrandTotal '.$key);

                $excel_query[$place]->put('a','Amount');
                $excel_query[$place]->put('equivalent_usd',$value['amount']);
                $excel_query[$place]->put('b','Equivalent USD');
                $excel_query[$place]->put('usd',$value['equivalent_usd']);
                $excel_query[$place]->put('c','MMK amount');
                $excel_query[$place]->put('amount_mmk',$value['amount_mmk']);


              }

                $excel_query->put($place+1,collect());
                $excel_query[$place+1]->put('sr_id','');
                $excel_query[$place+1]->put('receiver_name','GrandTotal');
                $excel_query[$place+1]->put('a','');
                $excel_query[$place+1]->put('b','');
                $excel_query[$place+1]->put('c','Equivalent USD');
                $excel_query[$place+1]->put('equivalent_usd',$grandtotalusd);
                $excel_query[$place+1]->put('d','MMK amount');
                $excel_query[$place+1]->put('amount_mmk',$grandtotalmmk);
                $excel_query[$place+1]->put('exchange_rate','');
                $excel_query[$place+1]->put('exchange_rate_usd','');

        //     $test=Inwards::whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->get();
        //    dd($test);

    }
    else if( !is_null($state_division) && is_null($branch_id))
            {

                $query = Inwards::All()->where('status',1)->where('state_division',$state_division)->where('created_at', '>=', $startdate)
        ->where('created_at', '<=', $enddate)->groupBy(function($data)
                {
                    return $data->created_at->format('Y-m-d');
                })->paginate(1000);

                $ttlamount=Inwards::where('status',1)->where('state_division',$state_division)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount');
                $ttlusd=Inwards::where('status',1)->where('state_division',$state_division)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('equivalent_usd');
                $ttlmmk=Inwards::where('status',1)->where('state_division',$state_division)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount_mmk');


        $excel_query=Inwards::select('sr_id','branch_id','receiver_name','state_division','receiver_nrc_passport','receiver_address_ph','purpose','withdraw_point','sender_name','sender_country_code','currency_code','amount','equivalent_usd','amount_mmk','exchange_rate','exchange_rate_usd','txd_date_time','status','created_at')->get()->where('status',1)->where('state_division',$state_division)->where('created_at', '>=', $startdate)
        ->where('created_at', '<=', $enddate)->groupBy(function($data)
            {
                return $data->created_at->format('Y-m-d');
            });
            // dd($excel_query);




            $total_currency_codes=array();
            $total_collection=collect();
            foreach ($query as $key=>$dated_transactions) {

                $currency_codes_in_date=array();

                foreach ($dated_transactions as $transaction) {
                    if(!in_array($transaction->currency_code,$total_currency_codes))
                    {
                        array_push($total_currency_codes,$transaction->currency_code);
                    }
                }


                foreach ($dated_transactions as $transaction) {
                    if(!in_array($transaction->currency_code,$currency_codes_in_date))
                    {
                        array_push($currency_codes_in_date,$transaction->currency_code);
                    }
                }

                $sub_total_collection=collect();
                foreach ($currency_codes_in_date as $currency_code) {
                 $subtotal_amount= $dated_transactions->where('currency_code',$currency_code)->sum('amount');
                 $sub_equsd= $dated_transactions->where('currency_code',$currency_code)->sum('equivalent_usd');
                 $sub_eqmmk= $dated_transactions->where('currency_code',$currency_code)->sum('amount_mmk');
                 $mmk_allowance= $dated_transactions->where('currency_code',$currency_code)->sum('mmk_allowance');
                 $total_mmk_amount= $dated_transactions->where('currency_code',$currency_code)->sum('total_mmk_amount');
                
                 $sub_total_collection->put($currency_code,['amount'=>$subtotal_amount,'mmk_allowance'=>$mmk_allowance,'total_mmk_amount'=>$total_mmk_amount,'equivalent_usd'=>$sub_equsd,'amount_mmk'=>$sub_eqmmk]);
                 $dated_transactions->put('subtotal',$sub_total_collection);
                }


            }

            foreach ($total_currency_codes as $currency_code) {

                $total_amounts=Inwards::where('status',1)->where('state_division',$state_division)->where('currency_code',$currency_code)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount');
                $total_equsd=Inwards::where('status',1)->where('state_division',$state_division)->where('currency_code',$currency_code)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('equivalent_usd');
                $total_eqmmk=Inwards::where('status',1)->where('state_division',$state_division)->where('currency_code',$currency_code)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount_mmk');
                $total_collection->put($currency_code,['amount'=>$total_amounts,'equivalent_usd'=>$total_equsd,'amount_mmk'=>$total_eqmmk]);


            }
            $grandtotalusd=0;
            $grandtotalmmk=0;
            $index=0;

            foreach ($excel_query as  $eq=>$collection)
            {
                $subtotal_amount_array=array();
                $subtotalamk=0;
                $subtotalusd=0;
                $subtotalmmk=0;



                for ($i=0; $i <=count($collection) ; $i++) {

                    if($i==count($collection))
                    {
                        $collection->put($i,collect());
                        $collection[$i]->put('sr_id','');
                        $collection[$i]->put('receiver_name','SubTotal');

                        $increment=0;
                        foreach ($subtotal_amount_array as $key => $value)
                        {

                            $collection[$i]->put('key'.$increment++,$key);
                            $collection[$i]->put($key,$value);
                        }

                        $collection[$i]->put('a','Equivalent USD');
                        $collection[$i]->put('equivalent_usd',$subtotalusd);
                        $collection[$i]->put('b','MMK amount');
                        $collection[$i]->put('amount_mmk',$subtotalmmk);
                        $collection[$i]->put('exchange_rate','');
                        $collection[$i]->put('exchange_rate_usd','');
                        break;


                    }
                    else
                    {
                        if(!array_key_exists($collection[$i]->currency_code,$subtotal_amount_array))
                        {
                            $subtotal_amount_array[$collection[$i]->currency_code]=$collection[$i]->amount;

                        }
                        else
                        {
                            $subtotal_amount_array[$collection[$i]->currency_code]+=$collection[$i]->amount;
                        }
                        $subtotalamk+=$collection[$i]->amount;
                        $subtotalusd+=$collection[$i]->equivalent_usd;
                        $subtotalmmk+=$collection[$i]->amount_mmk;
                        $grandtotalmmk+=$collection[$i]->amount_mmk;
                        $grandtotalusd+=$collection[$i]->equivalent_usd;

                        unset($collection[$i]->created_at);
                        unset($collection[$i]->status);
                        unset($collection[$i]->state_division);


                    }



                }
                $index++;

            }
            $sec_index=0;
            $place=0;

              foreach ($total_collection as $key => $value) {
                $sec_index++;
                $place=count($excel_query)+$sec_index;

                $excel_query->put($place,collect());
                $excel_query[$place]->put('sr_id','');
                $excel_query[$place]->put('receiver_name','GrandTotal '.$key);

                $excel_query[$place]->put('a','Amount');
                $excel_query[$place]->put('equivalent_usd',$value['amount']);
                $excel_query[$place]->put('b','Equivalent USD');
                $excel_query[$place]->put('usd',$value['equivalent_usd']);
                $excel_query[$place]->put('c','MMK amount');
                $excel_query[$place]->put('amount_mmk',$value['amount_mmk']);


              }

                $excel_query->put($place+1,collect());
                $excel_query[$place+1]->put('sr_id','');
                $excel_query[$place+1]->put('receiver_name','GrandTotal');
                $excel_query[$place+1]->put('a','');
                $excel_query[$place+1]->put('b','');
                $excel_query[$place+1]->put('c','Equivalent USD');
                $excel_query[$place+1]->put('equivalent_usd',$grandtotalusd);
                $excel_query[$place+1]->put('d','MMK amount');
                $excel_query[$place+1]->put('amount_mmk',$grandtotalmmk);
                $excel_query[$place+1]->put('exchange_rate','');
                $excel_query[$place+1]->put('exchange_rate_usd','');




    }
    else
    {
        $query = Inwards::All()->where('status',1)->where('created_at', '>=', $startdate)
        ->where('created_at', '<=', $enddate)->groupBy(function($data)
                {
                    return $data->created_at->format('Y-m-d');
                })->paginate(1000);

                $ttlamount=Inwards::where('status',1)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount');
                $ttlusd=Inwards::where('status',1)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('equivalent_usd');
                $ttlmmk=Inwards::where('status',1)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount_mmk');


       $excel_query=Inwards::select('sr_id','branch_id','receiver_name','state_division','receiver_nrc_passport','receiver_address_ph','purpose','withdraw_point','sender_name','sender_country_code','currency_code','amount','equivalent_usd','amount_mmk','exchange_rate','exchange_rate_usd','txd_date_time','status','created_at')->get()->where('status',1)->where('created_at', '>=', $startdate)
       ->where('created_at', '<=', $enddate)->groupBy(function($data)
           {
               return $data->created_at->format('Y-m-d');
           });
        //    dd($startdate, $enddate);




           $total_currency_codes=array();
           $total_collection=collect();
           foreach ($query as $key=>$dated_transactions) {

            $currency_codes_in_date=array();

            foreach ($dated_transactions as $transaction) {
                if(!in_array($transaction->currency_code,$total_currency_codes))
                {
                    array_push($total_currency_codes,$transaction->currency_code);
                }
            }


            foreach ($dated_transactions as $transaction) {
                if(!in_array($transaction->currency_code,$currency_codes_in_date))
                {
                    array_push($currency_codes_in_date,$transaction->currency_code);
                }
            }

            $sub_total_collection=collect();
            foreach ($currency_codes_in_date as $currency_code) {
             $subtotal_amount= $dated_transactions->where('currency_code',$currency_code)->sum('amount');
             $sub_equsd= $dated_transactions->where('currency_code',$currency_code)->sum('equivalent_usd');
             $sub_eqmmk= $dated_transactions->where('currency_code',$currency_code)->sum('amount_mmk');
             $mmk_allowance= $dated_transactions->where('currency_code',$currency_code)->sum('mmk_allowance');
                 $total_mmk_amount= $dated_transactions->where('currency_code',$currency_code)->sum('total_mmk_amount');
                
                 $sub_total_collection->put($currency_code,['amount'=>$subtotal_amount,'mmk_allowance'=>$mmk_allowance,'total_mmk_amount'=>$total_mmk_amount,'equivalent_usd'=>$sub_equsd,'amount_mmk'=>$sub_eqmmk]);
             $dated_transactions->put('subtotal',$sub_total_collection);
            }


        }
        foreach ($total_currency_codes as $currency_code) {

            $total_amounts=Inwards::where('status',1)->where('currency_code',$currency_code)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount');
            $total_equsd=Inwards::where('status',1)->where('currency_code',$currency_code)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('equivalent_usd');
            $total_eqmmk=Inwards::where('status',1)->where('currency_code',$currency_code)->whereBetween('created_at', [Carbon::parse($startdate)->toDateString(),Carbon::parse($enddate)->toDateString()])->sum('amount_mmk');
            $total_collection->put($currency_code,['amount'=>$total_amounts,'equivalent_usd'=>$total_equsd,'amount_mmk'=>$total_eqmmk]);


        }
        $grandtotalusd=0;
        $grandtotalmmk=0;
        $index=0;
        foreach ($excel_query as $eq => $collection)
        {
            $subtotal_amount_array=array();
            $subtotalamk=0;
            $subtotalusd=0;
            $subtotalmmk=0;



            for ($i=0; $i <=count($collection) ; $i++) {

                if($i==count($collection))
                {
                    $collection->put($i,collect());
                    $collection[$i]->put('sr_id','');
                    $collection[$i]->put('receiver_name','SubTotal');

                    $increment=0;
                    foreach ($subtotal_amount_array as $key => $value)
                    {

                        $collection[$i]->put('key'.$increment++,$key);
                        $collection[$i]->put($key,$value);
                    }

                    $collection[$i]->put('a','Equivalent USD');
                    $collection[$i]->put('equivalent_usd',$subtotalusd);
                    $collection[$i]->put('b','MMK amount');
                    $collection[$i]->put('amount_mmk',$subtotalmmk);
                    $collection[$i]->put('exchange_rate','');
                    $collection[$i]->put('exchange_rate_usd','');
                    break;


                }
                else
                {
                    if(!array_key_exists($collection[$i]->currency_code,$subtotal_amount_array))
                    {
                        $subtotal_amount_array[$collection[$i]->currency_code]=$collection[$i]->amount;

                    }
                    else
                    {
                        $subtotal_amount_array[$collection[$i]->currency_code]+=$collection[$i]->amount;
                    }
                    $subtotalamk+=$collection[$i]->amount;
                    $subtotalusd+=$collection[$i]->equivalent_usd;
                    $subtotalmmk+=$collection[$i]->amount_mmk;
                    $grandtotalmmk+=$collection[$i]->amount_mmk;
                    $grandtotalusd+=$collection[$i]->equivalent_usd;

                    unset($collection[$i]->created_at);
                    unset($collection[$i]->status);
                    unset($collection[$i]->state_division);


                }



            }






        }


        $sec_index=0;
        $place=0;

          foreach ($total_collection as $key => $value) {
            $sec_index++;
            $place=count($excel_query)+$sec_index;

            $excel_query->put($place,collect());
            $excel_query[$place]->put('sr_id','');
            $excel_query[$place]->put('receiver_name','GrandTotal '.$key);

            $excel_query[$place]->put('a','Amount');
            $excel_query[$place]->put('equivalent_usd',$value['amount']);
            $excel_query[$place]->put('b','Equivalent USD');
            $excel_query[$place]->put('usd',$value['equivalent_usd']);
            $excel_query[$place]->put('c','MMK amount');
            $excel_query[$place]->put('amount_mmk',$value['amount_mmk']);


          }

            $excel_query->put($place+1,collect());
            $excel_query[$place+1]->put('sr_id','');
            $excel_query[$place+1]->put('receiver_name','GrandTotal');
            $excel_query[$place+1]->put('a','');
            $excel_query[$place+1]->put('b','');
            $excel_query[$place+1]->put('c','Equivalent USD');
            $excel_query[$place+1]->put('equivalent_usd',$grandtotalusd);
            $excel_query[$place+1]->put('d','MMK amount');
            $excel_query[$place+1]->put('amount_mmk',$grandtotalmmk);
            $excel_query[$place+1]->put('exchange_rate','');
            $excel_query[$place+1]->put('exchange_rate_usd','');







    }




        session()->put('query', $excel_query);

        return view('admin.reports.inward')->with('inward_transactions', $query)
                                           ->with('branches',$branches)
                                           ->with('ttlamount',$ttlamount)
                                           ->with('ttlusd',$ttlusd)
                                           ->with('ttlmmk',$ttlmmk)
                                           ->with('total_collection',$total_collection)
                                           ->with('is_text30_valid',$this->isText30_valid($enddate));


    }



    public function totalinward()
    {
        if (auth()->user()->type == 'editor' && auth()->user()->total_inward == 1)
      {
        $branches=Branch::all();
        $startdate = now()->toDateString();
        $enddate = now()->toDateString();
        $startdatebusiness = '2020-01-01';

        $todaytotal = Inwards::select("*")
            ->whereDate('created_at', Carbon::today())
            ->get();

        $mmk_amounts = DB::table('inwards')->whereDate('created_at', Carbon::today())->where('currency_code', 'MMK')->sum('amount');
        $usd_amounts = DB::table('inwards')->whereDate('created_at', Carbon::today())->where('currency_code', 'USD')->sum('amount');
        $eur_amounts = DB::table('inwards')->whereDate('created_at', Carbon::today())->where('currency_code', 'EUR')->sum('amount');
        $jpy_amounts = DB::table('inwards')->whereDate('created_at', Carbon::today())->where('currency_code', 'JPY')->sum('amount');
        $krw_amounts = DB::table('inwards')->whereDate('created_at', Carbon::today())->where('currency_code', 'KRW')->sum('amount');
        $myr_amounts = DB::table('inwards')->whereDate('created_at', Carbon::today())->where('currency_code', 'MYR')->sum('amount');
        $sgd_amounts = DB::table('inwards')->whereDate('created_at', Carbon::today())->where('currency_code', 'SGD')->sum('amount');
        $thb_amounts = DB::table('inwards')->whereDate('created_at', Carbon::today())->where('currency_code', 'THB')->sum('amount');
        $aed_amounts = DB::table('inwards')->whereDate('created_at', Carbon::today())->where('currency_code', 'AED')->sum('amount');
        $qar_amounts = DB::table('inwards')->whereDate('created_at', Carbon::today())->where('currency_code', 'QAR')->sum('amount');

        $T_amount = Inwards ::select("id", DB::raw("(sum(equivalent_usd)) as tusd"), DB::raw("(sum(amount_mmk)) as tmmk"),
        DB::raw("(sum(mmk_allowance)) as t_mmk_allowance"),
        DB::raw("(sum(total_mmk_amount)) as t_mmk_amount"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"))
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->orderBy('created_at')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->first();

        $Tb_amount = Inwards ::select("id", DB::raw("(sum(equivalent_usd)) as tbusd"), DB::raw("(sum(amount_mmk)) as tbmmk"),
        DB::raw("(sum(mmk_allowance)) as tb_mmk_allowance"),
        DB::raw("(sum(total_mmk_amount)) as tb_mmk_amount")
        
        
        )
            ->whereDate('created_at', '>=', $startdatebusiness)
            ->whereDate('created_at', '<=', $enddate)
            ->get();

        $currency_code_array = array('MMK', 'USD', 'EUR', 'JPY', 'KRW', 'MYR', 'SGD', 'THB', 'AED', 'QAR');
        $other_amount = DB::table('inwards')->whereDate('created_at', Carbon::today())->whereNotIn('currency_code', $currency_code_array)->sum('amount');
        $total_num_trans = DB::table('inwards')->whereDate('created_at', Carbon::today())->count();

        if($T_amount != null && $Tb_amount != null)
        {

            $new_array = array('id' => 1,'dates' => $enddate,'usd_amounts' => $usd_amounts,'eur_amounts' => $eur_amounts,'jpy_amounts' => $jpy_amounts,'krw_amounts' => $krw_amounts,'myr_amounts' => $myr_amounts,'sgd_amounts' => $sgd_amounts,'thb_amounts' => $thb_amounts,'aed_amounts' => $aed_amounts,'qar_amounts' => $qar_amounts,'other_amounts' => $other_amount,'count' => 'total_num_trans','tusd' => $T_amount->tusd,
            
            'tmmk' => $T_amount->tmmk /1000000 ,
            't_mmk_allowance'=>$T_amount->t_mmk_allowance,
            't_mmk_amount'=>$T_amount->t_mmk_amount,
            'TotalBUSD' => $Tb_amount[0]->tbusd ,'TotalBMMK' => $Tb_amount[0]->tbmmk /1000000,
            'tb_mmk_allowance'=>$Tb_amount[0]->tb_mmk_allowance,
            'tb_mmk_amount'=>$Tb_amount[0]->tb_mmk_amount,
        
        
        );
            
        }
        else
        {

            $new_array = array('id' => 1,'dates' => $enddate,'usd_amounts' => $usd_amounts,'eur_amounts' => $eur_amounts,'jpy_amounts' => $jpy_amounts,'krw_amounts' => $krw_amounts,'myr_amounts' => $myr_amounts,'sgd_amounts' => $sgd_amounts,'thb_amounts' => $thb_amounts,'aed_amounts' => $aed_amounts,'qar_amounts' => $qar_amounts,'other_amounts' => $other_amount,'count' => $total_num_trans,'tusd' => 0,'tmmk' => 0,
            't_mmk_allowance'=>0,
            't_mmk_amount'=>0,
            
            'TotalBUSD' => $Tb_amount[0]->tbusd ,'TotalBMMK' => $Tb_amount[0]->tbmmk /1000000,
            'tb_mmk_allowance'=>0,
            'tb_mmk_amount'=>0,
        
        );
        }

        $excel_inoutward[0]=$new_array;
     session()->put('query',collect($excel_inoutward));
   //  dd(session()->get('query'));


        return view('admin.reports.totalinward')
            ->with('mmk_amounts', $mmk_amounts)
            ->with('usd_amounts', $usd_amounts)
            ->with('eur_amounts', $eur_amounts)
            ->with('jpy_amounts', $jpy_amounts)
            ->with('krw_amounts', $krw_amounts)
            ->with('myr_amounts', $myr_amounts)
            ->with('sgd_amounts', $sgd_amounts)
            ->with('thb_amounts', $thb_amounts)
            ->with('aed_amounts', $aed_amounts)
            ->with('qar_amounts', $qar_amounts)
            ->with('total_num_trans', $total_num_trans)
            ->with('other_amount', $other_amount)
            ->with('sd', $startdate)
            ->with('ed', $enddate)
            ->with('T_amount', $T_amount)
            ->with('Tb_amount', $Tb_amount)
            ->with('branches',$branches)
            ->with('is_text30_valid',$this->isText30_valid_today());
      }
      else
      {
        return back()->with('status', 'You do not have access');
      }


    }

    public function inwardtransactionwithdate(Request $request)
    {

        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');
        

        $query = DB::table('inwards')->select()
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)->orderBy("txd_date_time", "desc")
        
            ->get();

            $blacklists = blacklists::All();
            $is_text30_valid= $this->isText30_valid($enddate);
        return view('admin.dailytransaction.inwardtransaction')->with('inward_transactions', $query)
                                                               ->with('blacklists',$blacklists)
                                                               ->with('is_text30_valid',$is_text30_valid);
    }


    public function totalinwardwithdate(Request $request)
    {
        $branches=Branch::all();
        $branch_id=$request->branch_id;

        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');
        $startdatebusiness = '2020-01-01';

        if ($startdate == null) {
            $startdate = now();
        }


     if($branch_id==null)
     {
         $currency_code_array = array('MMK', 'USD', 'EUR', 'JPY', 'KRW', 'MYR', 'SGD', 'THB', 'AED', 'QAR');
         $usd_amounts = self::currencyCatcher('USD', 'usd_amounts', $startdate, $enddate);
         $mmk_amounts = self::currencyCatcher('MMK', 'mmk_amounts', $startdate, $enddate);
         $eur_amounts = self::currencyCatcher('EUR', 'eur_amounts', $startdate, $enddate);
         $jpy_amounts = self::currencyCatcher('JPY', 'jpy_amounts', $startdate, $enddate);
         $krw_amounts = self::currencyCatcher('KRW', 'krw_amounts', $startdate, $enddate);
         $myr_amounts = self::currencyCatcher('MYR', 'myr_amounts', $startdate, $enddate);
         $sgd_amounts = self::currencyCatcher('SGD', 'sgd_amounts', $startdate, $enddate);
         $thb_amounts = self::currencyCatcher('THB', 'thb_amounts', $startdate, $enddate);
         $aed_amounts = self::currencyCatcher('AED', 'aed_amounts', $startdate, $enddate);
         $qar_amounts = self::currencyCatcher('QAR', 'qar_amounts', $startdate, $enddate);
         $other_amounts = self::othercurrencyCatcher($currency_code_array, $startdate, $enddate);


         $Totaltransaction = self::TtransactionCatcher($startdate, $enddate);
         $T_amount = self::TCatcher($startdate, $enddate);

     }
     else
     {
        $currency_code_array = array('MMK', 'USD', 'EUR', 'JPY', 'KRW', 'MYR', 'SGD', 'THB', 'AED', 'QAR');
        $usd_amounts = self::currencyCatcher('USD', 'usd_amounts', $startdate, $enddate,$branch_id);
        $mmk_amounts = self::currencyCatcher('MMK', 'mmk_amounts', $startdate, $enddate,$branch_id);
        $eur_amounts = self::currencyCatcher('EUR', 'eur_amounts', $startdate, $enddate,$branch_id);
        $jpy_amounts = self::currencyCatcher('JPY', 'jpy_amounts', $startdate, $enddate,$branch_id);
        $krw_amounts = self::currencyCatcher('KRW', 'krw_amounts', $startdate, $enddate,$branch_id);
        $myr_amounts = self::currencyCatcher('MYR', 'myr_amounts', $startdate, $enddate,$branch_id);
        $sgd_amounts = self::currencyCatcher('SGD', 'sgd_amounts', $startdate, $enddate,$branch_id);
        $thb_amounts = self::currencyCatcher('THB', 'thb_amounts', $startdate, $enddate,$branch_id);
        $aed_amounts = self::currencyCatcher('AED', 'aed_amounts', $startdate, $enddate,$branch_id);
        $qar_amounts = self::currencyCatcher('QAR', 'qar_amounts', $startdate, $enddate,$branch_id);
        $other_amounts = self::othercurrencyCatcher($currency_code_array, $startdate, $enddate,$branch_id);


        $Totaltransaction = self::TtransactionCatcher($startdate, $enddate,$branch_id);
        $T_amount = self::TCatcher($startdate, $enddate,$branch_id);
     }


        $merged = $mmk_amounts->merge($usd_amounts)->merge($eur_amounts)->merge($jpy_amounts)
        ->merge($krw_amounts)->merge($krw_amounts)->merge($myr_amounts)->merge($sgd_amounts)
        ->merge($thb_amounts)->merge($aed_amounts)->merge($qar_amounts)->merge($other_amounts)
        ->merge($Totaltransaction);


        $derived_array = $merged->toarray();

        $array_merge = $merged->merge($T_amount);

      $final_array=array_merge($derived_array,$array_merge->toarray());



        $usd_amount_nodate = DB::table('inwards')->where('currency_code', 'USD')->sum('amount');
        $mmk_amount_nodate = DB::table('inwards')->where('currency_code', 'MMK')->sum('amount');

        $T_usd_amount = DB::table('inwards')->sum('equivalent_usd');
        $T_mmk_amount = DB::table('inwards')->sum('amount_mmk');

        $date_gp=collect($final_array)->groupBy('dates')->sortKeysDesc();
        $dategp_array=$date_gp->toarray();




        $tempDateArray=$dategp_array=$date_gp->toarray();



    $merged_array=array();

    foreach($dategp_array as $key=>$value)
    {
      foreach($value as $subValue)
      {
      $merged_array=array_merge($merged_array,$subValue);


     }
    $merged_array['count']=$this->getMaxFromArray($value);
      unset($dategp_array[$key]);
      array_push($dategp_array,$merged_array);
      $merged_array=array();
    }

       for($i=0;$i<count($dategp_array);$i++)
       {
        $dategp_array[$i]['count'];

       }




foreach($dategp_array as &$item)
{
$item['TotalBUSD']=$this->getTotalFromStartBusinessUSD($item['dates']);
$item['TotalBMMK']=$this->getTotalFromStartBusinessMMK($item['dates']);

}

$temp=array();
$index=0;
foreach($dategp_array as &$array)
{
    if(!array_key_exists('usd_amounts',$array))
    {
        $array['usd_amounts']= '0';
    }
    if(!array_key_exists('eur_amounts',$array))
    {
        $array['eur_amounts']= '0';
    }
    if(!array_key_exists('jpy_amounts',$array))
    {
        $array['jpy_amounts']= '0';
    }
    if(!array_key_exists('krw_amounts',$array))
    {
        $array['krw_amounts']= '0';
    }
    if(!array_key_exists('myr_amounts',$array))
    {
        $array['myr_amounts']= '0';
    }
    if(!array_key_exists('sgd_amounts',$array))
    {
        $array['sgd_amounts']= '0';
    }
    if(!array_key_exists('thb_amounts',$array))
    {
        $array['thb_amounts']= '0';
    }
    if(!array_key_exists('aed_amounts',$array))
    {
        $array['aed_amounts']= '0';
    }
    if(!array_key_exists('qar_amounts',$array))
    {
        $array['qar_amounts']= '0';
    }
    if(!array_key_exists('other_amounts',$array))
    {
        $array['other_amounts']= '0';
    }


   $new_array = array('id' => $array['id'],'dates' => $array['dates'],'usd_amounts' => $array['usd_amounts'],'eur_amounts' => $array['eur_amounts'],'jpy_amounts' => $array['jpy_amounts'],'krw_amounts' => $array['krw_amounts'],'myr_amounts' => $array['myr_amounts'],'sgd_amounts' => $array['sgd_amounts'],'thb_amounts' => $array['thb_amounts'],'aed_amounts' => $array['aed_amounts'],'qar_amounts' => $array['other_amounts'],'other_amounts' => $array['qar_amounts'],'count' => $array['count'],'tusd' => $array['tusd'],'tmmk' => $array['tmmk'],'TotalBUSD' => $array['TotalBUSD'],'TotalBMMK' => $array['TotalBMMK']);



   $temp[$index]=$new_array;

   $index++;



}



session()->put('query',collect($temp));


        return view('admin.reports.totalinward')
            ->with('derived_array', $derived_array)
            ->with('dategp_array',$dategp_array)
            ->with('sd', $startdate)
            ->with('ed', $enddate)
            ->with('usd_amount_nodate', $usd_amount_nodate)
            ->with('mmk_amount_nodate', $mmk_amount_nodate)
            ->with('T_usd_amount', $T_usd_amount)
            ->with('T_mmk_amount', $T_mmk_amount)
            ->with('branches',$branches)
            ->with('is_text30_valid',$this->isText30_valid_today());;
    }

    function getMaxFromArray($arr)
    {
                    $max = 0;

            for($i=0;$i<count($arr);$i++)
            {
                if ($arr[$i]['count'] > $max)
                {
                    $max = $arr[$i]['count'];
                }
            }
            return $max;

    }


    function getTotalFromStartBusinessUSD($enddate)
    {
        $Total_amount = Inwards::select(DB::raw("(sum(equivalent_usd)) as TotalBUSD"), DB::raw("(sum(amount_mmk)) as TotalBMMK"))
        ->whereDate('created_at', '<=', $enddate)->orderBy('created_at', 'desc')
        ->get();


        return $Total_amount[0]->TotalBUSD;

    }
    function getTotalFromStartBusinessMMK($enddate)
    {
        $Total_amount = Inwards::select(DB::raw("(sum(equivalent_usd)) as TotalBUSD"), DB::raw("(sum(amount_mmk)) as TotalBMMK"))
        ->whereDate('created_at', '<=', $enddate)->orderBy('created_at', 'desc')
        ->get();


        return $Total_amount[0]->TotalBMMK;

    }


    private function currencyCatcher($currencyCode, $currencyName, $startdate, $enddate,$branch_id=null)
    {
        if($branch_id==null)
        {
            $currency_amount = Inwards::select("id", DB::raw("(sum(amount)) as ".$currencyName),
            DB::raw("(count(*)) as count"), DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"))
                ->whereDate('created_at', '>=', $startdate)
                ->whereDate('created_at', '<=', $enddate)
                ->where('currency_code', $currencyCode)->orderBy('created_at', 'desc')
                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
                ->get();

        }
        else
        {
            $currency_amount = Inwards::select("id", DB::raw("(sum(amount)) as ".$currencyName),
            DB::raw("(count(*)) as count"), DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"))
                ->whereDate('created_at', '>=', $startdate)
                ->whereDate('created_at', '<=', $enddate)
                ->where('currency_code', $currencyCode)->orderBy('created_at', 'desc')
                ->where('branch_id',$branch_id)
                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
                ->get();
        }

        return $currency_amount;
    }
    private function othercurrencyCatcher($currency_code_array, $startdate, $enddate,$branch_id=null)
    {
        if($branch_id==null)
        {

            $othercurrency_amount = Inwards::select("id", DB::raw("(sum(amount)) as other_amounts"),
            DB::raw("(count(*)) as count"), DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"))
                ->whereDate('created_at', '>=', $startdate)
                ->whereDate('created_at', '<=', $enddate)
                ->whereNotIn('currency_code', $currency_code_array)->orderBy('created_at', 'desc')
                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
                ->get();
        }
        else
        {
            $othercurrency_amount = Inwards::select("id", DB::raw("(sum(amount)) as other_amounts"),
            DB::raw("(count(*)) as count"), DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"))
                ->whereDate('created_at', '>=', $startdate)
                ->whereDate('created_at', '<=', $enddate)
                ->where('branch_id',$branch_id)
                ->whereNotIn('currency_code', $currency_code_array)->orderBy('created_at', 'desc')
                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
                ->get();
        }

        return $othercurrency_amount;
    }
    private function TtransactionCatcher($startdate, $enddate,$branch_id=null)
    {
        if($branch_id==null)
        {

            $total_num_trans = Inwards::select(DB::raw("(count(*)) as count"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"))
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->orderBy('created_at', 'desc')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->get();
        }
        else
        {
            $total_num_trans = Inwards::select(DB::raw("(count(*)) as count"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"))
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('branch_id',$branch_id)
            ->orderBy('created_at', 'desc')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->get();
        }

        return $total_num_trans;
    }
    private function TCatcher($startdate, $enddate,$branch_id=null)
    {
        if($branch_id==null)
        {

            $Total_amount = Inwards::select("id", DB::raw("(sum(equivalent_usd)) as tusd"), DB::raw("(sum(amount_mmk)) as tmmk"),
                DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"), DB::raw("(count(*)) as count"))
                ->whereDate('created_at', '>=', $startdate)
                ->whereDate('created_at', '<=', $enddate)
                ->orderBy('created_at', 'desc')
                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
                ->get();
        }
        else
        {
            $Total_amount = Inwards::select("id", DB::raw("(sum(equivalent_usd)) as tusd"), DB::raw("(sum(amount_mmk)) as tmmk"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"), DB::raw("(count(*)) as count"))
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('branch_id',$branch_id)
            ->orderBy('created_at', 'desc')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->get();
        }

        return $Total_amount;
    }


    public function approveinward($id)
    {
        $inwardtransaction = Inwards::find($id);

        DB::connection('mysql2')->table('inwards')->insert(
            array(
                   'user_id' => $inwardtransaction->user_id,
                   'sr_id' => $inwardtransaction->sr_id,
                   'branch_id'     =>   $inwardtransaction->branch_id,
                   'receiver_name'     =>   $inwardtransaction->receiver_name,
                   'receiver_nrc_passport'     =>   $inwardtransaction->receiver_nrc_passport,
                   'receiver_address_ph'     =>   $inwardtransaction->receiver_address_ph,
                   'purpose'     =>    $inwardtransaction->purpose,
                   'withdraw_point'     =>    $inwardtransaction->withdraw_point,
                   'sender_name'     =>   $inwardtransaction->sender_name,
                   'sender_country_code'     =>   $inwardtransaction->sender_country_code,
                   'currency_code'     =>   $inwardtransaction->currency_code,
                   'amount'     =>   $inwardtransaction->amount,
                   'equivalent_usd'     =>   $inwardtransaction->equivalent_usd,
                   'txd_date_time'     =>   $inwardtransaction->txd_date_time,
                   'amount_mmk'     =>   $inwardtransaction->amount_mmk,
                   'exchange_rate' => $inwardtransaction->exchange_rate,
                   'exchange_rate_usd'=>$inwardtransaction->exchange_rate_usd,
                   'created_at'     =>   $inwardtransaction->created_at,
                   'status' => 0,

            )
        );
        $inwardtransaction->status = 1;
        $inwardtransaction->update();

        return back()->with('status', 'Approved!');
    }

    public function unapproveinward($id)
    {
        $inwardtransaction = Inwards::find($id);

        $inwardtransaction->status = 0;
        $inwardtransaction->update();

        return back()->with('status', 'Unapproved!');
    }

    public function exportexcelinward(Request $request)
    {
        if ($this->isText30_valid_today()) {
           
            return Excel::download(new ReportsExportText30(session()->get('query')), 'InwardTransaction_Report.xlsx');
        }
        else
        {
            return Excel::download(new ReportsExport(session()->get('query')), 'InwardTransaction_Report.xlsx');
        }
    }

    public function exportexceltotalinward(Request $request)
    {
        return Excel::download(new ReportsExport_TotalInward(session()->get('query')), 'TotalInwardTransaction_Report.xlsx');
    }


    public function dashboarddailytotal()
    {
        $query = DB::table('inwards')->whereDate('created_at', DB::raw('CURDATE()'))->get();

        $dailyusd = 0;
        foreach ($query as $transation) {
            $dailyusd += $transation->equivalent_usd;
        }
        return view('admin.dashboard')->with('dailyusd', $dailyusd);
        $daily = Inwards::select("*")
            ->whereDate('created_at', Carbon::today())
            ->get();
        $dailyusd = 0;
        foreach ($daily as $transation) {
            $dailyusd += $transation->equivalent_usd;
        }
        return view('admin.dashboard')->with('dailyusd', $dailyusd);
    }

    public function dashboardmonthlytotal()
    {
        $query = DB::table('inwards')->select()->get();

        $monthlyusd = 0;
        foreach ($query as $transation) {
            $monthlyusd += $transation->equivalent_usd;
        }
        return view('admin.dashboard')->with('monthlyusd', $monthlyusd);
    }

    public function download($file)
{
    $down = public_path(). '/storage/files/' .$file;
    return \Response::download($down);
}

public function isText30_valid($date)
{
    $text30_startdate_row=Dates::where('name','text30start')->first();

    if ($text30_startdate_row) {
     
      $text30_startdate=$text30_startdate_row->date;
    }

   $text30_enddate_row=Dates::where('name','text30end')->first();

   if ($text30_enddate_row)  {
   
    $text30_enddate=$text30_enddate_row->date;
   }

  if (!empty($text30_startdate)) {
   

    if (!empty($text30_enddate)) {
       
        if ($date>$text30_startdate && $date<$text30_enddate) {
          
           return true;


        }
        else
        {
          
            return false;
        }
    }
    else
    {
            if ($date>$text30_startdate) {
            
                return true;
            }
            else
            {
              return false;
            }
    }


  }
  else
  {
            return false;
  }

}

public function isText30_valid_today()
{
    $date=Carbon::today();
    $text30_startdate_row=Dates::where('name','text30start')->first();

    if ($text30_startdate_row) {
     
      $text30_startdate=$text30_startdate_row->date;
    }

   $text30_enddate_row=Dates::where('name','text30end')->first();

   if ($text30_enddate_row)  {
   
    $text30_enddate=$text30_enddate_row->date;
   }

  if (!empty($text30_startdate)) {
   

    if (!empty($text30_enddate)) {
       
        if ($date>$text30_startdate && $date<$text30_enddate) {
          
           return true;


        }
        else
        {
          
            return false;
        }
    }
    else
    {
            if ($date>$text30_startdate) {
            
                return true;
            }
            else
            {
              return false;
            }
    }


  }
  else
  {
            return false;
  }

}

}





