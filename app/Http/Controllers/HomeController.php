<?php

namespace App\Http\Controllers;

use App\Models\Inwards;
use App\Models\Outwards;
use App\Models\Company;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd(auth()->user()->type);
        $inwardsum=0;
        $outwardsum=0;

        $dailyinward = Inwards::select("*")
            ->whereDate('created_at', Carbon::today())
            ->count();

        $dailyoutward = Outwards::select("*")
            ->whereDate('created_at', Carbon::today())
            ->count();

        $dailytotal = $dailyinward + $dailyoutward;

        // weekly

        $weeklyinward = Inwards::select("*")
            ->whereBetween('created_at',
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        $weeklyoutward = Outwards::select("*")
            ->whereBetween('created_at',
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        $weeklytotal = $weeklyinward + $weeklyoutward;

        // monthly
        $monthly = Inwards::select("*")->whereMonth('created_at', Carbon::now()->month)->get();
        $monthlyusd = 0;
        foreach ($monthly as $transation) {
            $monthlyusd += $transation->equivalent_usd;
        }
        $monthlyinward = Inwards::select("*")
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $monthlyoutward = Outwards::select("*")
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $monthlytotal = $monthlyinward + $monthlyoutward;

        // yearly
        $yearly = Inwards::select("*")
            ->whereYear('created_at', date('Y'))->get();
        $yearlyusd = 0;
        foreach ($yearly as $transation) {
            $yearlyusd += $transation->equivalent_usd;
        }
        $yearlyinward = Inwards::select("*")
            ->whereYear('created_at', date('Y'))
            ->count();

        $yearlyoutward = Outwards::select("*")
            ->whereYear('created_at', date('Y'))
            ->count();

        $yearlytotal = $yearlyinward + $yearlyoutward;


        $startCountDate=null;
        $endCountDate=null;


        $inwardTransactions=Inwards::whereDate('created_at','>=',Carbon::today())->get();

        $inwardDateGrouped=$inwardTransactions->groupBy(function($data)
        {
            return  Carbon::parse($data->created_at)->toDateString();
        });


        $inwardCounts=collect();

        foreach ($inwardDateGrouped as $key=>$inward) {
        
            $total_count=$inward->count();
          $approved_count=  $inward->where('status',1)->count();
          $remaining_count=  $inward->where('status',0)->count();

          $inwardCounts->put($key,['total_count'=>$total_count,'approved_count'=>$approved_count,'remaining_count'=>$remaining_count]);


        }


        $outTransactions=Outwards::whereDate('created_at','>=',Carbon::today())->get();

        $outwardDateGrouped=$outTransactions->groupBy(function($data)
        {
            return  Carbon::parse($data->created_at)->toDateString();
        });


        $outwardCounts=collect();

        foreach ($outwardDateGrouped as $key=>$outward) {
        
            $total_count=$outward->count();
          $approved_count=  $outward->where('status',1)->count();
          $remaining_count=  $outward->where('status',0)->count();

          $outwardCounts->put($key,['total_count'=>$total_count,'approved_count'=>$approved_count,'remaining_count'=>$remaining_count]);


        }

       

        

      
        
       

        return view('admin.dashboard')->with('dailyinward', $dailyinward)->with('dailyoutward', $dailyoutward)
            ->with('dailytotal', $dailytotal)->with('monthlyinward', $monthlyinward)->with('monthlyoutward', $monthlyoutward)
            ->with('weeklytotal', $weeklytotal)->with('monthlytotal', $monthlytotal)
            ->with('yearlyinward', $yearlyinward)->with('yearlyoutward', $yearlyoutward)->with('yearlytotal', $yearlytotal)
            ->with('inwardsum',$inwardsum)->with('outwardsum',$outwardsum)
            ->with('inwardCounts',$inwardCounts)
            ->with('outwardCounts',$outwardCounts)
            ->with('startCountDate',$startCountDate)
            ->with('endCountDate',$endCountDate);
    }

    public function countwithdate(Request $request)
    {
       


        $inwardsum=0;
        $outwardsum=0;

        $dailyinward = Inwards::select("*")
            ->whereDate('created_at', Carbon::today())
            ->count();

        $dailyoutward = Outwards::select("*")
            ->whereDate('created_at', Carbon::today())
            ->count();

        $dailytotal = $dailyinward + $dailyoutward;

        // weekly

        $weeklyinward = Inwards::select("*")
            ->whereBetween('created_at',
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        $weeklyoutward = Outwards::select("*")
            ->whereBetween('created_at',
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        $weeklytotal = $weeklyinward + $weeklyoutward;

        // monthly
        $monthly = Inwards::select("*")->whereMonth('created_at', Carbon::now()->month)->get();
        $monthlyusd = 0;
        foreach ($monthly as $transation) {
            $monthlyusd += $transation->equivalent_usd;
        }
        $monthlyinward = Inwards::select("*")
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $monthlyoutward = Outwards::select("*")
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $monthlytotal = $monthlyinward + $monthlyoutward;

        // yearly
        $yearly = Inwards::select("*")
            ->whereYear('created_at', date('Y'))->get();
        $yearlyusd = 0;
        foreach ($yearly as $transation) {
            $yearlyusd += $transation->equivalent_usd;
        }
        $yearlyinward = Inwards::select("*")
            ->whereYear('created_at', date('Y'))
            ->count();

        $yearlyoutward = Outwards::select("*")
            ->whereYear('created_at', date('Y'))
            ->count();

        $yearlytotal = $yearlyinward + $yearlyoutward;


        $startCountDate=$request->startCountDate;
        $endCountDate=$request->endCountDate;

    $inwardTransactions=Inwards::whereDate('created_at','>=',$startCountDate)->whereDate('created_at','<=',$endCountDate)->get();

        $inwardDateGrouped=$inwardTransactions->groupBy(function($data)
        {
            return  Carbon::parse($data->created_at)->toDateString();
        });


        $inwardCounts=collect();

        foreach ($inwardDateGrouped as $key=>$inward) {
        
            $total_count=$inward->count();
          $approved_count=  $inward->where('status',1)->count();
          $remaining_count=  $inward->where('status',0)->count();

          $inwardCounts->put($key,['total_count'=>$total_count,'approved_count'=>$approved_count,'remaining_count'=>$remaining_count]);


        }


        $outTransactions=Outwards::whereDate('created_at','>=',$startCountDate)->whereDate('created_at','<=',$endCountDate)->get();

        $outwardDateGrouped=$outTransactions->groupBy(function($data)
        {
            return  Carbon::parse($data->created_at)->toDateString();
        });


        $outwardCounts=collect();

        foreach ($outwardDateGrouped as $key=>$outward) {
        
            $total_count=$outward->count();
          $approved_count=  $outward->where('status',1)->count();
          $remaining_count=  $outward->where('status',0)->count();

          $outwardCounts->put($key,['total_count'=>$total_count,'approved_count'=>$approved_count,'remaining_count'=>$remaining_count]);


        }

       

        

      
        
       

        return view('admin.dashboard')->with('dailyinward', $dailyinward)->with('dailyoutward', $dailyoutward)
            ->with('dailytotal', $dailytotal)->with('monthlyinward', $monthlyinward)->with('monthlyoutward', $monthlyoutward)
            ->with('weeklytotal', $weeklytotal)->with('monthlytotal', $monthlytotal)
            ->with('yearlyinward', $yearlyinward)->with('yearlyoutward', $yearlyoutward)->with('yearlytotal', $yearlytotal)
            ->with('inwardsum',$inwardsum)->with('outwardsum',$outwardsum)
            ->with('inwardCounts',$inwardCounts)
            ->with('outwardCounts',$outwardCounts)
            ->with('startCountDate',$startCountDate)
            ->with('endCountDate',$endCountDate);

       
    }

    public function dailywithdate(Request $request)
    {

        $inwardsum=0;
        $outwardsum=0;
        // dd($dailyinward,$dailyoutward);
        //daily with date
        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');

        $dailyinward = DB::table('inwards')->select()
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)->count();
        session()->put('$dailyinward', $dailyinward);

        $dailyoutward = DB::table('outwards')->select()
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)->count();
        session()->put('$dailyoutward', $dailyoutward);
        // dd($inwardquery, $outwardquery);

        // monthly
        $monthlyinward = Inwards::select("*")
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $monthlyoutward = Outwards::select("*")
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $monthlytotal = $monthlyinward + $monthlyoutward;

        // yearly
        $yearlyinward = Inwards::select("*")
            ->whereYear('created_at', date('Y'))
            ->count();

        $yearlyoutward = Outwards::select("*")
            ->whereYear('created_at', date('Y'))
            ->count();

        $yearlytotal = $yearlyinward + $yearlyoutward;
        // return dd($dailytotal, $weeklytotal);
        return view('admin.dashboard')
            ->with('dailyinward', $dailyinward)->with('dailyoutward', $dailyoutward)
            ->with('monthlyinward', $monthlyinward)->with('monthlyoutward', $monthlyoutward)
            ->with('monthlytotal', $monthlytotal)->with('yearlyinward', $yearlyinward)
            ->with('yearlyoutward', $yearlyoutward)->with('yearlytotal', $yearlytotal);
    }

    public function monthlywithdate(Request $request)
    {
        // dd("Month");
        //daily
        $dailyinward = Inwards::select("*")
            ->whereDate('created_at', Carbon::today())
            ->count();

        $dailyoutward = Outwards::select("*")
            ->whereDate('created_at', Carbon::today())
            ->count();

        //monthly with date
        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');
        $startdate = $startdate.'-01';
        $enddate = $enddate.'-31';

        $monthlyinward=DB::table('inwards')->select()
        ->whereDate('created_at','>=',$startdate)
        ->whereDate('created_at','<=',$enddate)->count();

        $monthlyoutward=DB::table('outwards')->select()
        ->whereDate('created_at','>=',$startdate)
        ->whereDate('created_at','<=',$enddate)->count();

        //yearly
        $yearlyinward = Inwards::select("*")
            ->whereYear('created_at', date('Y'))
            ->count();

        $yearlyoutward = Outwards::select("*")
            ->whereYear('created_at', date('Y'))
            ->count();

        $yearlytotal = $yearlyinward + $yearlyoutward;
        // return dd($dailytotal, $weeklytotal);
        return view('admin.dashboard')
                            ->with('dailyinward', $dailyinward)->with('dailyoutward', $dailyoutward)
                            ->with('monthlyinward', $monthlyinward)->with('monthlyoutward', $monthlyoutward)
                            ->with('yearlyinward', $yearlyinward)->with('yearlyoutward', $yearlyoutward)
                            ->with('yearlytotal', $yearlytotal);
                            
    }

    public function yearlywithdate(Request $request)
    {
        $outwardsum=0;
        $inwardsum=0;

        // dd("Year");
        //daily
        $dailyinward = Inwards::select("*")
            ->whereDate('created_at', Carbon::today())
            ->count();

        $dailyoutward = Outwards::select("*")
            ->whereDate('created_at', Carbon::today())
            ->count();

        // monthly
        $monthlyinward = Inwards::select("*")
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $monthlyoutward = Outwards::select("*")
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        //daily with date
        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');

        $yearlyinward = DB::table('inwards')->select()
            ->whereYear('created_at', '>=', $startdate)
            ->whereYear('created_at', '<=', $enddate)->count();
        session()->put('$yearlyinward', $yearlyinward);

        $yearlyoutward = DB::table('outwards')->select()
            ->whereYear('created_at', '>=', $startdate)
            ->whereYear('created_at', '<=', $enddate)->count();
        session()->put('$yearlyoutward', $yearlyoutward);
        return view('admin.dashboard')
        ->with('dailyinward', $dailyinward)->with('dailyoutward', $dailyoutward)
        ->with('monthlyinward', $monthlyinward)->with('monthlyoutward', $monthlyoutward)
        ->with('yearlyinward', $yearlyinward)->with('yearlyoutward', $yearlyoutward)
        ->with('inwardsum',$inwardsum)->with('outwardsum',$outwardsum);;
        // dd($yearlyinward, $yearlyoutward);
    }
}
