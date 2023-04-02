<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\Country;
use Session;
use DB;

class BranchController extends Controller
{
    public function branch()
      {
         if (auth()->user()->type == 'editor' && auth()->user()->branch == 1)
        {
          
          $branches = Branch::All();

          return view('admin.datasetup.branch')->with('branches', $branches);
        }
        else
        {
          return back()->with('status', 'You do not have access');
        }
      }

      public function addbranch()
      {
        $countries_codes = Country::All()->pluck('country_code', 'country_code');
        $currencies = Currency::All()->pluck('currency_name','currency_code');

       if (auth()->user()->type == 'editor' && auth()->user()->branch == 1)
        {
          
           return view('admin.datasetup.addbranch')->with('currencies', $currencies)->with('countries_codes', $countries_codes);
        }
        else if(auth()->user()->type == 'checker')
        {
          return back()->with('status', 'You do not have access');
        }
      }

      public function savebranch(Request $request)
      {
            $this->validate($request, [ 'branch_code' => 'required',
                                        'branch_name'=>'required',
                                        'country'=>'required',
                                        'prefer_currency'=>'required',
                                        'ph_no'=>'required|',
                                        'address'=>'required']);

        $branch = new Branch();
        $branch->branch_code = $request->input('branch_code');
        $branch->branch_name = $request->input('branch_name');
        $branch->country = $request->input('country');
        $branch->prefer_currency = $request->input('prefer_currency');
        $branch->ph_no = $request->input('ph_no');
        $branch->address = $request->input('address');
        $branch->save();

        DB::connection('mysql2')->table('branches')->insert(
          array(
                'user_id' => 24,
                 'branch_id' => $request->input('branch_code'),
                 'name' => $request->input('branch_name'),
                 'address'     =>   $request->input('address'),
                 'country_code'     =>   $request->input('country'),

          )
      );

        return redirect('/branch')->with('status', 'Branch has been added!');
      }

      

      public function editbranch($id)
      {
         if (auth()->user()->type == 'editor' && auth()->user()->branch == 1)
        {
          
          $countries_codes = Country::All()->pluck('country_code', 'country_code');
          $currencies = Currency::All()->pluck('currency_code','currency_code');
            $branches = Branch::find($id);

           return view('admin.datasetup.editbranch')->with('branches', $branches)->with('currencies', $currencies)->with('countries_codes', $countries_codes);
        }
        else if(auth()->user()->type == 'checker')
        {
          return back()->with('status', 'You do not have access');
        }
      }

      public function updatebranch(Request $request)
      {
        $this->validate($request, [ 'branch_code' => 'required',
                                    'branch_name'=>'required',
                                    'country'=>'required',
                                    'prefer_currency'=>'required',
                                    'ph_no'=>'required|',
                                    'address'=>'required']);

          $branch = Branch::find($request->input('id'));
          $branch->branch_code = $request->input('branch_code');
          $branch->branch_name = $request->input('branch_name');
          $branch->country = $request->input('country');
          $branch->prefer_currency = $request->input('prefer_currency');
          $branch->ph_no = $request->input('ph_no');
          $branch->address = $request->input('address');

        $branch->update();

        return redirect('/branch')->with('status', 'Branch has been updated!');
      }

      public function deletebranch($id)
      {       
        $branch = Branch::find($id);

        
        
        $primary_user_branch_id =  DB::connection('mysql')->table('branches')->select('branch_code')->where('id', $id)->first();
        $secondary_user_branch_id = DB::connection('mysql2')->table('branches')->select('user_id','branch_id')->where('user_id', 24)->get();
       
    // dd($primary_user_branch_id);

     foreach($secondary_user_branch_id as $id)
     {
       if($id->branch_id == $primary_user_branch_id->branch_code)
       {
      
           DB::connection('mysql2')->table('branches')->select('user_id', $id->user_id)->where('branch_id',$id->branch_id)->delete();      
           $branch->delete();  
       }
     }
     
   

         return back()->with('status', 'Branch has been deleted!');

      }
}
