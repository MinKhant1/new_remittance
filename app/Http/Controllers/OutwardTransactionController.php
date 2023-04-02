<?php

namespace App\Http\Controllers;

use App\Exports\Report_outward;
use App\Exports\ReportsExport_TotalOutward;
use App\Exports\ReportsExport_TotalInOutward;
use App\Models\Branch;
use App\Models\ExchangeRate;
use App\Models\Inwards;
use App\Models\Outwards;
use App\Models\OutwardTransaction;
use App\Models\PurposeOfTrans;
use App\Models\TotalOutward;
use App\Models\blacklists;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use Illuminate\Support\Facades\Storage;


class OutwardTransactionController extends Controller
{
    

    public function outwardtransaction()
    {
        if (auth()->user()->type == 'editor' && auth()->user()->outward_trans == 1)
        {
            $outwardtransactions = Outwards::select("*")
            ->whereDate('created_at', Carbon::today())
            ->get();
            $blacklists = blacklists::All();
        return view('admin.dailytransaction.outwardtransaction')->with('outwardtransactions', $outwardtransactions)->with('blacklists', $blacklists);
        }
        else
        {
          return back()->with('status', 'You do not have access');
        }
        
    }

   public function addoutwardtransaction(Request $req)
    {
        $outwardtransaction = Outwards::all();
       
        if($outwardtransaction != null)
        {
            $outwardtransaction = Outwards::latest()->value('sr_id');
        }
        else
        {
            
        }
       // dd($outwardtransaction);
        $branches = Branch::All();
        $purposeOfTrans = PurposeOfTrans::All();
        $exchange_rates = ExchangeRate::all();

        $usd = DB::table('exchange_rates')->where('currency_code', 'USD')
            ->value('exchange_rate');

        $thb = DB::table('exchange_rates')->where('currency_code', 'THB')
            ->value('exchange_rate');

        $countryFromBranch = Branch::all();


        $outwardtransactions = Outwards::all();

        return view('admin.dailytransaction.addoutwardtransaction')->with('purposeOfTrans', $purposeOfTrans)
            ->with('outwardtransaction', $outwardtransaction)
            ->with('branches', $branches)
            ->with('usd', $usd)->with('thb', $thb)
            ->with('countryFromBranch', $countryFromBranch)
            ->with('exchange_rates', $exchange_rates);

    }

    public function outwardtransactionapprove()
    {
        if (auth()->user()->type == 'editor' && auth()->user()->outward_approve == 1)
        {
            $outwardtransactions = Outwards::All()->where('status', 0);

            return view('admin.dailytransaction.approveoutwardtransaction')->with('outwardtransactions', $outwardtransactions);
        }
        else
        {
          return back()->with('status', 'You do not have access');
        }
           
       

    }

    public function outwardtransactionreport()
    {
        if (auth()->user()->type == 'editor' && auth()->user()->outward == 1)
        {
            $outwardtransactions = Outwards::All();
            $branches = Branch::all();
            session()->put('outwardexcel', $outwardtransactions);
            return view('admin.reports.outward')
                ->with('outwardtransactions', $outwardtransactions)
                ->with('branches', $branches);
        }
        else
        {
          return back()->with('status', 'You do not have access');
        }
      
    }
    public function outwardwithbranch(Request $request)
    {
        $branch_id = $request->input('branch_id');
        $branches = Branch::all();

        $outwardtransactions = DB::table('outwards')->select()->where('branch_id', $branch_id)
            ->get();
        $out_array = $outwardtransactions->toarray();

        session()->put('outwardexcel', $outwardtransactions);

        return view('admin.reports.outward')
            ->with('outwardtransactions', $outwardtransactions)
            ->with('branches', $branches);
    }

    public function totaloutward()
    {
        if (auth()->user()->type == 'editor' && auth()->user()->total_outward == 1)
        {
            $startdate = now()->toDateString();
            $enddate = now()->toDateString();
            $branches=Branch::all();
    
            $startdatebusiness = '2020-01-01';
    
            $total_num_trans = DB::table('outwards')->whereDate('created_at', Carbon::today())->count();
            $T_usd_amount = DB::table('outwards')->whereDate('created_at', Carbon::today())->sum('equivalent_usd');
            $T_mmk_amount = DB::table('outwards')->whereDate('created_at', Carbon::today())->sum('amount_mmk');
    
            $Tb_usd_amount = DB::table('outwards')->whereDate('created_at', '>=', $startdatebusiness)
                ->whereDate('created_at', '<=', $enddate)->sum('equivalent_usd');
            $Tb_mmk_amount = DB::table('outwards')->whereDate('created_at', '>=', $startdatebusiness)
                ->whereDate('created_at', '<=', $enddate)->sum('amount_mmk');
    
    
               $temp_array=array("No" => 1, "date"=>$startdate,"t_no_trans"=>$total_num_trans,"t_usd"=>$T_usd_amount,"t_mmk"=>$T_mmk_amount,"tb_usd"=>$Tb_usd_amount,"tb_mmk"=>$Tb_mmk_amount);
    
               $excel_array[0]=$temp_array;
             //  dd(collect($excel_array));
                session()->put('outwardexcel', collect($excel_array));
    
              //  dd(session()->get('outwardexcel'));
    
            return view('admin.reports.totaloutward')
                ->with('total_num_trans', $total_num_trans)
                ->with('T_usd_amount', $T_usd_amount)->with('T_mmk_amount', $T_mmk_amount)
                ->with('Tb_usd_amount', $Tb_usd_amount)->with('Tb_mmk_amount', $Tb_mmk_amount)
                ->with('sd', $startdate)->with('ed', $enddate)
                ->with('branches',$branches);
        }
        else
        {
          return back()->with('status', 'You do not have access');
        }
           
       
    }

    public function searchoutward(Request $request)
    {
        $branches=Branch::all();
        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');
        $branch_id=null;
        if($request->input('branch_id')!=null)
        {
            $branch_id=$request->input('branch_id');

        }
        $state_division=$request->input('state_division');

    if(!is_null($branch_id) && !is_null($state_division))
    {
        $query = DB::table('outwards')->select()
                ->whereDate('created_at', '>=', $startdate)
                ->whereDate('created_at', '<=', $enddate)
                 ->where('branch_id',$branch_id)
                ->where('state_division',$state_division)
                ->get();
        $excel_query = DB::table('inwards')
        ->select('sr_id', 'branch_id', 'sender_name', 'sender_nrc_passport', 'sender_address_ph', 'purpose', 'deposit_point', 'receiver_name', 'receiver_country_code', 'equivalent_usd', 'amount_mmk', 'txd_date_time')
        ->whereDate('created_at', '>=', $startdate)
        ->whereDate('created_at', '<=', $enddate)
         ->where('branch_id',$branch_id)
        ->where('state_division',$state_division)
        ->get();

    }else if(!is_null($branch_id) && is_null($state_division))
                { $query = DB::table('outwards')->select()
                    ->whereDate('created_at', '>=', $startdate)
                    ->whereDate('created_at', '<=', $enddate)
                    ->where('branch_id',$branch_id)
                  //  ->where('state_division',$state_division)
                    ->get();
            $excel_query = DB::table('outwards')
            ->select('sr_id', 'branch_id', 'sender_name', 'sender_nrc_passport', 'sender_address_ph', 'purpose', 'deposit_point', 'receiver_name', 'receiver_country_code', 'equivalent_usd', 'amount_mmk', 'txd_date_time')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('branch_id',$branch_id)
           // ->where('state_division',$state_division)
            ->get();

    }
    else if( !is_null($state_division) && is_null($branch_id))
            {$query = DB::table('outwards')->select()
                ->whereDate('created_at', '>=', $startdate)
                ->whereDate('created_at', '<=', $enddate)
                //->where('branch_id',$branch_id)
          ->where('state_division',$state_division)
                ->get();
        $excel_query = DB::table('outwards')
        ->select('sr_id', 'branch_id', 'sender_name', 'sender_nrc_passport', 'sender_address_ph', 'purpose', 'deposit_point', 'receiver_name', 'receiver_country_code', 'equivalent_usd', 'amount_mmk', 'txd_date_time')
        ->whereDate('created_at', '>=', $startdate)
        ->whereDate('created_at', '<=', $enddate)
        //->where('branch_id',$branch_id)
      ->where('state_division',$state_division)
        ->get();

    }
    else
    {
         $query = DB::table('outwards')->select()
                ->whereDate('created_at', '>=', $startdate)
                ->whereDate('created_at', '<=', $enddate)
                //->where('branch_id',$branch_id)
       // ->where('state_division',$state_division)
                ->get();
        $excel_query = DB::table('outwards')
        ->select('sr_id', 'branch_id', 'sender_name', 'sender_nrc_passport', 'sender_address_ph', 'purpose', 'deposit_point', 'receiver_name', 'receiver_country_code', 'equivalent_usd', 'amount_mmk', 'txd_date_time')
        ->whereDate('created_at', '>=', $startdate)
        ->whereDate('created_at', '<=', $enddate)
        //->where('branch_id',$branch_id)
       // ->where('state_division',$state_division)
        ->get();

    }
  //  dd($excel_query);

    session()->put('outwardexcel', $excel_query);

    return view('admin.reports.outward')->with('outwardtransactions', $query)->with('branches', $branches);


        // $branches = Branch::all();
        // $startdate = $request->input('startdate');
        // $enddate = $request->input('enddate');

        // $query = DB::table('outwards')->select()->whereDate('created_at', '>=', $startdate)->whereDate('created_at', '<=', $enddate)->get();

        // $excel_query = DB::table('outwards')
        //     ->select('sr_id', 'branch_id', 'sender_name', 'sender_nrc_passport', 'sender_address_ph', 'purpose', 'deposit_point', 'receiver_name', 'receiver_country_code', 'equivalent_usd', 'amount_mmk', 'txd_date_time')
        //     ->whereDate('created_at', '>=', $startdate)->whereDate('created_at', '<=', $enddate)->get();

        //     dd($excel_query);


    }

    public function totaloutwardwithdate(Request $request)
    {
        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');
        $startdatebusiness = '2020-01-01';

        $branches=Branch::all();
        $branch_id=$request->branch_id;

        if($branch_id==null)
        {

       

        $query = DB::table('outwards')->select('equivalent_usd', 'amount_mmk')->whereDate('created_at', '>=', $startdate)
                                                                              ->whereDate('created_at', '<=', $enddate)
                                                                              ->orderBy("created_at", "desc")->get();
        $excel_query = DB::table('outwards')
            ->select('id', 'user_id', 'sr_id', 'branch_id', 'sender_name', 'sender_nrc_passport', 'sender_address_ph', 'purpose', 'deposit_point', 'receiver_name', 'receiver_country_code', 'equivalent_usd', 'amount_mmk', 'txd_date_time')
            ->where('created_at', '>=', $startdate)
            ->where('created_at', '<=', $enddate)->orderBy("created_at", "desc")->get();

        session()->put('outwardexcel', $excel_query);

        $total_num_trans = Outwards::select(DB::raw("(count(*)) as count"))
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->orderBy("created_at", "desc")
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->count();
            $T_amount = self::TotalCatcher($startdate, $enddate);
        }
        else
        {
            $query = DB::table('outwards')->select('equivalent_usd', 'amount_mmk')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('branch_id',$branch_id)
            ->orderBy("created_at", "desc")->get();

            $excel_query = DB::table('outwards')
            ->select('id', 'user_id', 'sr_id', 'branch_id', 'sender_name', 'sender_nrc_passport', 'sender_address_ph', 'purpose', 'deposit_point', 'receiver_name', 'receiver_country_code', 'equivalent_usd', 'amount_mmk', 'txd_date_time')
            ->where('created_at', '>=', $startdate)
            ->where('branch_id',$branch_id)
            ->where('created_at', '<=', $enddate)->orderBy("created_at", "desc")->get();

            session()->put('outwardexcel', $excel_query);

            $total_num_trans = Outwards::select(DB::raw("(count(*)) as count"))
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('branch_id',$branch_id)
            ->orderBy("created_at", "desc")
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->count();
            $T_amount = self::TotalCatcherWithBranch($startdate, $enddate,$branch_id);
        }



     
       
        $derived_array = $T_amount->toArray();

        foreach($derived_array as &$item)
        {
            $item['TotalBUSD']=$this->getTotalFromStartBusinessUSD($item['dates']);
            $item['TotalBMMK']=$this->getTotalFromStartBusinessMMK($item['dates']);
        }

        $temp=array();
        $index=0;
        foreach($derived_array as &$array)
        {
            if(!array_key_exists('tusd',$array))
            {
                $array['tsud']= '0';
            }
            if(!array_key_exists('tmmk',$array))
            {
                $array['tmmk']= '0';
            }
            if(!array_key_exists('TotalBUSD',$array))
            {
                $array['TotalBMMK']= '0';
            }


           $new_array = array('id' => $array['id'],'dates' => $array['dates'],'count' => $array['count'],'tusd' => $array['tusd'],'tmmk' => $array['tmmk'],'TotalBUSD' => $array['TotalBUSD'],'TotalBMMK' => $array['TotalBMMK']);

           $temp[$index]=$new_array;

           $index++;

        }
       // dd(collect($temp));
        session()->put('outwardexcel',collect($temp));



        return view('admin.reports.totaloutward')
        ->with('derived_array', $derived_array)
            ->with('query', $query)->with('total_num_trans', $total_num_trans)
            ->with('T_amount', $T_amount)
            ->with('sd', $startdate)->with('ed', $enddate)
            ->with('branches',$branches);

    }

    function getTotalFromStartBusinessUSD($enddate)
    {
        $Total_amount = Outwards::select(DB::raw("(sum(equivalent_usd)) as TotalBUSD"), DB::raw("(sum(amount_mmk)) as TotalBMMK"))
        ->whereDate('created_at', '<=', $enddate)->orderBy("created_at", "desc")
        ->get();

        return $Total_amount[0]->TotalBUSD;

    }
    function getTotalFromStartBusinessMMK($enddate)
    {
        $Total_amount = Outwards::select(DB::raw("(sum(equivalent_usd)) as TotalBUSD"), DB::raw("(sum(amount_mmk)) as TotalBMMK"))
        ->whereDate('created_at', '<=', $enddate)->orderBy("created_at", "desc")
        ->get();

        return $Total_amount[0]->TotalBMMK;

    }

    private function TotalCatcher($startdate, $enddate)
    {
        $Total_amount = Outwards::select("id", DB::raw("(sum(equivalent_usd)) as tusd"), DB::raw("(sum(amount_mmk)) as tmmk"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"), DB::raw("(count(*)) as count"))
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate("created_at", '<=', $enddate)
            ->orderBy('created_at', "desc")
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->get();

        return $Total_amount;
    }


    private function TotalCatcherWithBranch($startdate, $enddate,$branch_id)
    {
        $Total_amount = Outwards::select("id", DB::raw("(sum(equivalent_usd)) as tusd"), DB::raw("(sum(amount_mmk)) as tmmk"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"), DB::raw("(count(*)) as count"))
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate("created_at", '<=', $enddate)
            ->where('branch_id',$branch_id)
            ->orderBy('created_at', "desc")
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->get();

        return $Total_amount;
    }

    private function TotalbCatcher($startdatebusiness, $enddate)
    {
        $Totalb_amount = Outwards::select("id", DB::raw("(sum(equivalent_usd)) as tbusd"), DB::raw("(sum(amount_mmk)) as tbmmk"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"), DB::raw("(count(*)) as count"))
            ->whereDate('created_at', '>=', $startdatebusiness)
            ->whereDate('created_at', '<=', $enddate)
            ->orderBy("created_at", "desc")
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->get();



        return $Totalb_amount;
    }

    public function outwardtransactionwithdate(Request $request)
    {

        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');

        $query = DB::table('outwards')->select()
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)->orderBy("txd_date_time", "desc")
            ->get();

            $blacklists = blacklists::All();

        return view('admin.dailytransaction.outwardtransaction')->with('outwardtransactions', $query)->with('blacklists', $blacklists);
    }

    public function totalinwardoutward()
    {
        if (auth()->user()->type == 'editor' && auth()->user()->total_inward_outward == 1)
      {
        $startdate = now()->toDateString();
        $enddate = now()->toDateString();

        $branches=Branch::all();

        $T_Inamount = Inwards::select("id", DB::raw("(count(*)) as icount"), DB::raw("(sum(equivalent_usd)) as itusd"), DB::raw("(sum(amount_mmk)) as itmmk"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as odates"))
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->first();

        $T_Outamount = Outwards::select("id", DB::raw("(count(*)) as ocount"), DB::raw("(sum(equivalent_usd)) as otusd"), DB::raw("(sum(amount_mmk)) as otmmk"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as odates"))
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->first();

        $Net_trans = ($T_Inamount->icount??'0') + ($T_Outamount->ocount??'0');
        $Net_usd = ($T_Inamount->itusd??'0') + ($T_Outamount->otusd??'0');
        $Net_mmk = ($T_Inamount->itmmk??'0') + ($T_Outamount->otmmk??'0');

        if($T_Inamount!=null && $T_Outamount!=null)
        {
            $temp_array=array("date"=>$startdate,"t_inward_tran"=>$T_Inamount->icount,"t_inward_usd"=>$T_Inamount->itusd,"t_inward_mmk"=>$T_Inamount->itmmk,
            "t_outward_tran"=>$T_Outamount->ocount,"t_outward_usd"=>$T_Outamount->otusd,"t_outward_mmk"=>$T_Outamount->otmmk,
            "net_tran"=>$Net_trans,"net_usd"=>$Net_usd,"net_mmk"=>$Net_mmk);

        }
        else if($T_Inamount==null && $T_Outamount!=null)
        {
            $temp_array=array("date"=>$startdate,"t_inward_tran"=>0,"t_inward_usd"=>0,"t_inward_mmk"=>0,
            "t_outward_tran"=>$T_Outamount->ocount,"t_outward_usd"=>$T_Outamount->otusd,"t_outward_mmk"=>$T_Outamount->otmmk,
            "net_tran"=>$Net_trans,"net_usd"=>$Net_usd,"net_mmk"=>$Net_mmk);
        }
        else if($T_Inamount!=null && $T_Outamount==null)
        {
            $temp_array=array("date"=>$startdate,"t_inward_tran"=>$T_Inamount->icount,"t_inward_usd"=>$T_Inamount->itusd,"t_inward_mmk"=>$T_Inamount->itmmk,
            "t_outward_tran"=>0,"t_outward_usd"=>0,"t_outward_mmk"=>0,
            "net_tran"=>$Net_trans,"net_usd"=>$Net_usd,"net_mmk"=>$Net_mmk);
        }
        else
        {
            $temp_array=array("No" => 1, "date"=>$startdate,"t_inward_tran"=>0,"t_inward_usd"=>0,"t_inward_mmk"=>0,
            "t_outward_tran"=>0,"t_outward_usd"=>0,"t_outward_mmk"=>0,
            "net_tran"=>$Net_trans,"net_usd"=>$Net_usd,"net_mmk"=>$Net_mmk);
        }


     $excel_inoutward[0]=$temp_array;
     session()->put('outwardexcel',collect($excel_inoutward));
    // dd(collect($excel_inoutward));
        // dd($Net_usd);
        return view('admin.reports.totalinwardoutward')
            ->with('sd', $startdate)->with('ed', $enddate)
            ->with('T_Inamount', $T_Inamount)->with('T_Outamount', $T_Outamount)
            ->with('Net_trans', $Net_trans)->with('Net_usd', $Net_usd)
            ->with('Net_mmk', $Net_mmk)
            ->with('branches',$branches);
    
      }
      else
      {
        return back()->with('status', 'You do not have access');
      }

    }

    public function totalinwardoutwardwithdate(Request $request)
    {
        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');
        $branches=Branch::all();
     
        $branch_id=$request->branch_id;

        if($branch_id==null)
        {
            
            $T_Inamount = Inwards::select("id", DB::raw("(count(*)) as icount"), DB::raw("(sum(equivalent_usd)) as itusd"), DB::raw("(sum(amount_mmk)) as itmmk"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"))
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->orderBy('created_at', 'desc')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->get();
    
            $T_Outamount = Outwards::select("id", DB::raw("(count(*)) as ocount"), DB::raw("(sum(equivalent_usd)) as otusd"), DB::raw("(sum(amount_mmk)) as otmmk"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"))
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->orderBy('created_at', 'desc')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->get();
        }
        else
        {
            $T_Inamount = Inwards::select("id", DB::raw("(count(*)) as icount"), DB::raw("(sum(equivalent_usd)) as itusd"), DB::raw("(sum(amount_mmk)) as itmmk"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"))
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('branch_id',$branch_id)
            ->orderBy('created_at', 'desc')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->get();
    
            $T_Outamount = Outwards::select("id", DB::raw("(count(*)) as ocount"), DB::raw("(sum(equivalent_usd)) as otusd"), DB::raw("(sum(amount_mmk)) as otmmk"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d')) as dates"))
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('branch_id',$branch_id)
            ->orderBy('created_at', 'desc')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->get();
        }


        $T_amount= new \Illuminate\Database\Eloquent\Collection;
        
        $T_amount=collect($T_Inamount)->merge(collect($T_Outamount));



        $T_amountArray=$T_amount->toarray();
        $T_inArray=$T_Inamount->toarray();
        $T_outArray=$T_Outamount->toarray();

        $max_count=0;
        if(count($T_inArray)>= count($T_outArray))
        {
            $max_count=count($T_inArray);
        }
        else
        {
            $max_count=count($T_outArray);
        }


        for($i=0;$i<$max_count;$i++)
        {

            $T_amountArray[$i]['icount']=$this->getInwardCountValueWithDate($T_Inamount,$T_amountArray[$i]['dates']);
            $T_amountArray[$i]['ocount']=$this->getOutwardCountValueWithDate($T_Outamount,$T_amountArray[$i]['dates']);


            $T_amountArray[$i]['itusd']=$this->getInwardUSDValueWithDate($T_Inamount,$T_amountArray[$i]['dates']);
            $T_amountArray[$i]['otusd']=$this->getOutwardUSDValueWithDate($T_Outamount,$T_amountArray[$i]['dates']);

            $T_amountArray[$i]['itmmk']=$this->getInwardMMKValueWithDate($T_Inamount,$T_amountArray[$i]['dates']);
            $T_amountArray[$i]['otmmk']=$this->getOutwardMMKValueWithDate($T_Outamount,$T_amountArray[$i]['dates']);


            $T_amountArray[$i]['netcount']=$this->getInwardCountValueWithDate($T_Inamount,$T_amountArray[$i]['dates'])+$this->getOutwardCountValueWithDate($T_Outamount,$T_amountArray[$i]['dates']);
            $T_amountArray[$i]['netusd']=$this->getInwardUSDValueWithDate($T_Inamount,$T_amountArray[$i]['dates'])-$this->getOutwardUSDValueWithDate($T_Outamount,$T_amountArray[$i]['dates']);
            $T_amountArray[$i]['netmmk']=$this->getInwardMMKValueWithDate($T_Inamount,$T_amountArray[$i]['dates'])-$this->getOutwardMMKValueWithDate($T_Outamount,$T_amountArray[$i]['dates']);

        }


   
 foreach($T_amountArray as $k=>$item)
{

    if(array_key_exists('icount',$item) && array_key_exists('ocount',$item))
    {
       continue;

    }
    else
    {
     unset($T_amountArray[$k]);
    }
}



        $temp=array();
$index=0;
foreach($T_amountArray as &$array)
{
    if(!array_key_exists('icount',$array))
    {
        $array['icount']= '0';
    }
    if(!array_key_exists('itusd',$array))
    {
        $array['itusd']= '0';
    }
    if(!array_key_exists('itmmk',$array))
    {
        $array['itmmk']= '0';
    }
    if(!array_key_exists('ocount',$array))
    {
        $array['ocount']= '0';
    }
    if(!array_key_exists('otusd',$array))
    {
        $array['otusd']= '0';
    }
    if(!array_key_exists('otmmk',$array))
    {
        $array['otmmk']= '0';
    }
    if(!array_key_exists('netcount',$array))
    {
        $array['netcount']= '0';
    }
    if(!array_key_exists('netusd',$array))
    {
        $array['netusd']= '0';
    }
    if(!array_key_exists('netmmk',$array))
    {
        $array['netmmk']= '0';
    }



   $new_array = array('id' => $array['id'],'dates' => $array['dates'],'icount' => $array['icount'],'itusd' => $array['itusd'],'itmmk' => $array['itmmk'],'ocount' => $array['ocount'],'otusd' => $array['otusd'],'otmmk' => $array['otmmk'],'netcount' => $array['netcount'],'netusd' => $array['netusd'],'netmmk' => $array['netmmk']);



   $temp[$index]=$new_array;

   $index++;



}



session()->put('outwardexcel',collect($temp));


      //  dd($T_Inamount->toarray(),$T_Outamount->toarray(),$T_amountArray);
        // $Amount = collect([$T_Inamount, $T_Outamount, $Net_array]);
        //  dd($T_amount_array);

        return view('admin.reports.totalinwardoutward')
        ->with('sd', $startdate)->with('ed', $enddate)
        ->with('T_Inamount', $T_Inamount)->with('T_Outamount', $T_Outamount)
        ->with('T_amount', $T_amountArray)
        ->with('branches',$branches);



    }

    public function getInwardUSDValueWithDate($collection,$date)
    {
        $outputValue=0;

        foreach($collection as $item)
        {
            if($item['dates']==$date)
            {
                $outputValue=$item->itusd;
            }
        }
        return $outputValue;

    }
    public function getOutwardUSDValueWithDate($collection,$date)
    {
        $outputValue=0;

        foreach($collection as $item)
        {
            if($item['dates']==$date)
            {
                $outputValue=$item->otusd;
            }
        }
        return $outputValue;
    }



    public function getInwardCountValueWithDate($collection,$date)
    {
        $outputValue=0;

        foreach($collection as $item)
        {
            if($item['dates']==$date)
            {
                $outputValue=$item->icount;
            }
        }
        return $outputValue;

    }
    public function getOutwardCountValueWithDate($collection,$date)
    {
        $outputValue=0;

        foreach($collection as $item)
        {
            if($item['dates']==$date)
            {
                $outputValue=$item->ocount;
            }
        }
        return $outputValue;

    }
    public function getInwardMMKValueWithDate($collection,$date)
    {
        $outputValue=0;

        foreach($collection as $item)
        {
            if($item['dates']==$date)
            {
                $outputValue=$item->itmmk;
            }
        }
        return $outputValue;

    }
    public function getOutwardMMKValueWithDate($collection,$date)
    {
        $outputValue=0;

        foreach($collection as $item)
        {
            if($item['dates']==$date)
            {
                $outputValue=$item->otmmk;
            }
        }
        return $outputValue;

    }

    public function saveoutwardtransaction(Request $request)
    {
        $this->validate($request, ['sr_id' => 'required',
            'branch_id' => 'required',
            'sender_name' => 'required',
            'nrc_number' => 'required',
            'sender_address' => 'required',
            'sender_phno' => 'required',
            'purpose_of_transaction' => 'required',
            'deposit_point' => 'required',
            'receiver_name' => 'required',
            'receiver_nrc_passport' => 'required',
            'receiver_country' => 'required',
            'mmk_amount' => 'required',
            'equivalent_usd' => 'required',
            'state_division' => 'required',
             'exchange_rate_input_usd'=>'required'

        ]);
       // dd($request->all());

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
            $fileNameToStore = 'Nofile.jpg';
        }

        $nrc_code = $request->input('nrc_code');
        $nrc_city = $request->input('nrc_city');
        $nrc_citizen = $request->input('nrc_citizen');
        $nrc_number = $request->input('nrc_number');

        if ($nrc_code == null && $nrc_citizen == null && $nrc_city == null) {
            $nrc = $nrc_number;
        } else {
            $nrc = $nrc_code . '/' . $nrc_city . '(' . $nrc_citizen . ')' . $nrc_number;

        }

        $outwardtransaction = new Outwards();
        $outwardtransaction->sr_id = $request->input('sr_id');
        $outwardtransaction->branch_id = $request->input('hidden_branch_id');
        $outwardtransaction->sender_name = $request->input('sender_name');
        $outwardtransaction->sender_nrc_passport = $nrc;
        $outwardtransaction->sender_address_ph = $request->input('sender_address') . '/' . $request->input('sender_phno');
        $outwardtransaction->purpose = $request->input('purpose_of_transaction');
        $outwardtransaction->deposit_point = $request->input('deposit_point');
        $outwardtransaction->receiver_name = $request->input('receiver_name');
        $outwardtransaction->receiver_nrc_passport = $request->input('receiver_nrc_passport');
        $outwardtransaction->receiver_country_code = $request->input('receiver_country');
        $outwardtransaction->amount_mmk = $request->input('mmk_amount');
        $outwardtransaction->equivalent_usd = $request->input('equivalent_usd');
        $outwardtransaction->txd_date_time = $request->input('date');
        $outwardtransaction->state_division = $request->input('state_division');
        $outwardtransaction->exchange_rate_usd=$request->input('exchange_rate_input_usd');
        $outwardtransaction->file = $fileNameToStore;
        $outwardtransaction->status = 0;
        $outwardtransaction->save();

       // Session::put('outwardexcel', $outwardtransaction);

        return back()->with('status', 'Outward Transaction has been added!');
    }

    public function updateoutwardtransaction(Request $request)
    {
        $this->validate($request, ['sr_id' => 'required',
            'branch_id' => 'required',
            'sender_name' => 'required',
            'nrc_number' => 'required',
            'sender_address' => 'required',
            'sender_phno' => 'required',
            'purpose_of_transaction' => 'required',
            'deposit_point' => 'required',
            'receiver_name' => 'required',
            'receiver_nrc_passport' => 'required',
            'receiver_country' => 'required',
            'mmk_amount' => 'required',
            'equivalent_usd' => 'required',
            'state_division' => 'required',

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
            $fileNameToStore = 'Nofile.jpg';
        }
        $nrc_code = $request->input('nrc_code');
        $nrc_city = $request->input('nrc_city');
        $nrc_citizen = $request->input('nrc_citizen');
        $nrc_number = $request->input('nrc_number');

        if ($nrc_code == null && $nrc_citizen == null && $nrc_city == null) {
            $nrc = $nrc_number;
        } else {
            $nrc = $nrc_code . '/' . $nrc_city . '(' . $nrc_citizen . ')' . $nrc_number;

        }

        $outwardtransaction = Outwards::find($request->input('id'));
        $outwardtransaction->sr_id = $request->input('sr_id');
        $outwardtransaction->branch_id = $request->input('hidden_branch_id');
        $outwardtransaction->sender_name = $request->input('sender_name');
        $outwardtransaction->sender_nrc_passport = $nrc;
        $outwardtransaction->sender_address_ph = $request->input('sender_address') . '/' . $request->input('sender_phno');
        $outwardtransaction->purpose = $request->input('purpose_of_transaction');
        $outwardtransaction->deposit_point = $request->input('deposit_point');
        $outwardtransaction->receiver_name = $request->input('receiver_name');
        $outwardtransaction->receiver_nrc_passport = $request->input('receiver_nrc_passport');
        $outwardtransaction->receiver_country_code = $request->input('receiver_country');
        $outwardtransaction->amount_mmk = $request->input('mmk_amount');
        $outwardtransaction->equivalent_usd = $request->input('equivalent_usd');
        $outwardtransaction->txd_date_time = $request->input('date');
        $outwardtransaction->status = 0;
        $outwardtransaction->state_division = $request->input('state_division');
        $outwardtransaction->exchange_rate_usd=$request->input('exchange_rate_input_usd');
        $outwardtransaction->file = $fileNameToStore;
        $outwardtransaction->update();

        return back()->with('status', 'Outward Transaction has been updated!');
    }

    public function editoutwardtransaction($id)
    {

        $outwardtransaction = Outwards::find($id);
        $purposeOfTrans = PurposeOfTrans::all();
        $branches = Branch::All();
        $exchange_rates = ExchangeRate::all();
        $usd = DB::table('exchange_rates')->where('currency_code', 'USD')
            ->value('exchange_rate');

        $thb = DB::table('exchange_rates')->where('currency_code', 'THB')
            ->value('exchange_rate');

        return view('admin.dailytransaction.editoutwardtransaction')
            ->with('outward_transaction', $outwardtransaction)->with('branches', $branches)->with('purposeOfTrans', $purposeOfTrans)
            ->with('usd', $usd)->with('thb', $thb)
            ->with('exchange_rates', $exchange_rates);

    }

    public function deleteoutwardtransaction($id)
    {

        $outwardtransactions = Outwards::find($id);

        $outwardtransactions->delete();

        return back()->with('status', 'Outward Transaction has been deleted!');

    }
    // public function approveoutwardtransaction()
    // {
    //     $outwardtransactions = OutwardTransaction::All();
    //     return view('admin.dailytransaction.approveoutwardtransaction')->with('outwardtransactions',$outwardtransactions);
    // }

    public function approveoutward($id)
    {

        $outwardtransaction = Outwards::find($id);
        DB::connection('mysql2')->table('outwards')->insert(
            array(
                   'user_id' => $outwardtransaction->user_id,
                   'sr_id' => $outwardtransaction->sr_id,
                   'branch_id'     =>   $outwardtransaction->branch_id,
                   'sender_name'     =>   $outwardtransaction->sender_name,
                   'sender_nrc_passport'     =>   $outwardtransaction->sender_nrc_passport,
                   'sender_address_ph'     =>   $outwardtransaction->sender_address_ph,
                   'purpose'     =>    $outwardtransaction->purpose,
                   'deposit_point'     =>    $outwardtransaction->deposit_point,
                   'receiver_name'     =>   $outwardtransaction->receiver_name,
                   'receiver_country_code' => $outwardtransaction->receiver_country_code,
                    'amount_mmk' => $outwardtransaction->amount_mmk,
                    'equivalent_usd' => $outwardtransaction->equivalent_usd,
                    'txd_date_time'     =>   $outwardtransaction->txd_date_time,
                    'exchange_rate_usd' =>         $outwardtransaction->exchange_rate_usd,
                   'created_at'     =>   $outwardtransaction->created_at,
                   'status' => 0,

            )
        );

        $outwardtransaction->status = 1;
        $outwardtransaction->update();

        return back()->with('status', 'Approved!');

    }

    public function unapproveoutward($id)
    {

        $outwardtransaction = Outwards::find($id);

        $outwardtransaction->status = 0;
        $outwardtransaction->update();

        return back()->with('status', 'Unapproved!');

    }

    public function exportexceloutward()
    {

        return Excel::download(new Report_outward(session()->get('outwardexcel')), 'OutwardTransaction_Report.xlsx');

    }

    public function exportexceloutwardtotal(Request $request)
    {
        return Excel::download(new ReportsExport_TotalOutward(session()->get('outwardexcel')), 'TotalOutwardTransaction_Report.xlsx');
    }


    public function exportexcelinoutwardtotal(Request $request)
    {
        return Excel::download(new ReportsExport_TotalInOutward(session()->get('outwardexcel')), 'TotalInwardOutwardTransaction_Report.xlsx');
    }

    public function download($file)
    {
        $down = public_path(). '/storage/files/' .$file;
        return \Response::download($down);
    }

}
