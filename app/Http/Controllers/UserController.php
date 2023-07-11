<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Branch;
use App\Models\Role;
use App\Http\Middleware\CheckerUser;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;

class UserController extends Controller
{
    public function login()
    {
    $company=Company::find(1);
        return view('auth.login')->with('company',$company);

    }

    public function signup()
    {
        return view('auth.register');
    }

    public function logout()
    {       $company=Company::find(1);

        Auth::logout();
        return view('auth.login')->with('company',$company);

    }

    public function createaccount(Request $request)
    {
        $this->validate($request, ['name'=>'required|unique:users',
                                   'password' => 'required|min:4']);

        $user = new User();
        $user->name = $request->input('name');
        $user->password = bcrypt($request->input('password'));
        $user->role = 'Checker';

        $user->save();

        return redirect('/login')->with('status', 'Acount has been created!');
    }

    public function  accessaccount(Request $request)
    {
        $this->validate($request, ['name'=>'required',
                                   'email'=>'required',
                                   'password' => 'required']);

        $user = User::where('name', $request->input('name'))->first();

        if($user)
        {
            if(Hash::check($request->input('password'), $user->password))
            {
                Session::put('user', $user);
                return redirect('/');
            }
            else
            {
                return back()->with('status', 'Bad email or password');
            }
        }
        else
        {
            return back()->with('status', 'You dont have an acount');
        }
    }

      public function user()
      {


          if(auth()->user()->type == 'editor' && auth()->user()->user_control == 1)
          {
            $users = User::All();
            return view('admin.usersetup.user')->with('users', $users);
          }
          else
          {
            return back()->with('status', 'You do not have access');
          }
      }

      public function adduser()
      {
        $branches = Branch::all();

        return view('admin.usersetup.adduser')->with('branches', $branches);
      }


      public function usermanual()
      {
        return view('admin.usersetup.usermanual');
      }

      public function saveuser(Request $request)
      {
        $this->validate($request, ['name'=>'required|unique:users',
                                   'password' => 'required|min:4',
                                   'password' => 'required|confirmed|min:4']);

        $user = new User();
        // $user = User::find($request->input('id'));
        $user->name = $request->input('name');
        $user->branch_code = $request->input('branch_code');
        $user->password = bcrypt($request->input('password'));
        $user->inward = $request->input('inward');
        $user->outward = $request->input('outward');
        $user->total_inward = $request->input('total_inward');
        $user->total_outward = $request->input('total_outward');
        $user->total_inward_outward = $request->input('total_inward_outward');
        $user->inward_trans = $request->input('inward_trans');
        $user->outward_trans = $request->input('outward_trans');
        $user->inward_approve = $request->input('inward_approve');
        $user->outward_approve = $request->input('outward_approve');
        $user->company = $request->input('company');
        $user->branch = $request->input('branch');
        $user->country = $request->input('country');
        $user->currency = $request->input('currency');
        $user->purpose_of_trans = $request->input('purpose_of_trans');
        $user->trans_max_limit = $request->input('trans_max_limit');
        $user->blacklist = $request->input('blacklist');
        $user->exchange_rate = $request->input('exchange_rate');
        $user->user_control = $request->input('user_control');
        $user->save();

        return redirect('/user')->with('status', 'User has been added!');
      }

      public function edituser($id)
      {
          $user = User::find($id);

          $branches = Branch::all();

          $roles =  DB::table('users')->select('type')->groupby('type')->get()->toArray();


          return view('admin.usersetup.edituser')->with('user', $user)->with('roles', $roles)->with('branches', $branches);
      }

      public function updateuser(Request $request)
      {
        $this->validate($request, ['name'=>'required',
                                   ]);

        $user = User::find($request->input('id'));
        $user->name = $request->input('name');
        $user->branch_code = $request->input('branch_code');
        $user->inward = $request->input('inward');
        $user->outward = $request->input('outward');
        $user->total_inward = $request->input('total_inward');
        $user->total_outward = $request->input('total_outward');
        $user->total_inward_outward = $request->input('total_inward_outward');
        $user->inward_trans = $request->input('inward_trans');
        $user->outward_trans = $request->input('outward_trans');
        $user->inward_approve = $request->input('inward_approve');
        $user->outward_approve = $request->input('outward_approve');
        $user->company = $request->input('company');
        $user->branch = $request->input('branch');
        $user->country = $request->input('country');
        $user->currency = $request->input('currency');
        $user->purpose_of_trans = $request->input('purpose_of_trans');
        $user->trans_max_limit = $request->input('trans_max_limit');
        $user->blacklist = $request->input('blacklist');
        $user->exchange_rate = $request->input('exchange_rate');
        $user->user_control = $request->input('user_control');
        $user->update();

        return redirect('/user')->with('status', 'User has been updated!');
      }

      public function deleteuser($id)
      {
        $user = User::find($id);

        $user->delete();

        return back()->with('status', 'User has been deleted!');
      }

     
}

