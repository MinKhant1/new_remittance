<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Inwards;
use App\Models\Outwards;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'name' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt(array('name' => $input['name'], 'password' => $input['password']))) {

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
            // return dd($dailytotal, $weeklytotal);
            return view('admin.dashboard')->with('dailyinward', $dailyinward)->with('dailyoutward', $dailyoutward)->with('dailytotal', $dailytotal)->with('weeklytotal', $weeklytotal)->with('monthlyinward', $monthlyinward)->with('monthlyoutward', $monthlyoutward)->with('monthlytotal', $monthlytotal)->with('yearlyinward', $yearlyinward)->with('yearlyoutward', $yearlyoutward)->with('yearlytotal', $yearlytotal);

        } else {
            return redirect()->route('login')
                ->with('error', 'Email-Address And Password Are Wrong.');
        }

    }
}
