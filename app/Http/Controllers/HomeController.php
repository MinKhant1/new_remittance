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

        $totalInwardCount=Inwards::whereDate('created_at',Carbon::today())->count();
        $approvedInwardCount=Inwards::whereDate('created_at',Carbon::today())->where('status',1)->count();
        $remainingInwardCount=Inwards::whereDate('created_at',Carbon::today())->where('status',0)->count();
        

        $totalOutwardCount=Outwards::whereDate('created_at',Carbon::today())->count();
        $approvedOutwardCount=Outwards::whereDate('created_at',Carbon::today())->where('status',1)->count();
        $remainingOutwardCount=Outwards::whereDate('created_at',Carbon::today())->where('status',0)->count();
        
       

        return view('admin.dashboard')->with('dailyinward', $dailyinward)->with('dailyoutward', $dailyoutward)
            ->with('dailytotal', $dailytotal)->with('monthlyinward', $monthlyinward)->with('monthlyoutward', $monthlyoutward)
            ->with('weeklytotal', $weeklytotal)->with('monthlytotal', $monthlytotal)
            ->with('yearlyinward', $yearlyinward)->with('yearlyoutward', $yearlyoutward)->with('yearlytotal', $yearlytotal)
            ->with('inwardsum',$inwardsum)->with('outwardsum',$outwardsum)
            ->with('totalInwardCount',$totalInwardCount)
            ->with('apprevedInwardCount',$approvedInwardCount)
            ->with('remainingInwardCount',$remainingInwardCount)
            ->with('totalOutwardCount',$totalOutwardCount)
            ->with('approvedOutwardCount',$approvedOutwardCount)
            ->with('remainingOutwardCount',$remainingOutwardCount)
            ->with('startCountDate',$startCountDate)
            ->with('endCountDate',$endCountDate);
    }

    public function countwithdate(Request $request)
    {

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
