<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WithdrawPoint;

class WithdrawPointController extends Controller
{
    public function withdrawpoint()
    {
        if (auth()->user()->type == 'editor' && auth()->user()->purpose_of_trans == 1)
      {
        $withdrawpoints=WithdrawPoint::All();
        return view('admin.datasetup.withdrawpoint')->with('withdrawpoints',$withdrawpoints);
      }
      else
      {
        return back()->with('status', 'You do not have access');
      }
    }
    public function addwithdrawpoint()
    {
         if (auth()->user()->type == 'editor' && auth()->user()->purpose_of_trans == 1)
      {
          return view('admin.datasetup.addwithdrawpoint');
        }
        else if(auth()->user()->type == 'checker') 
        {
          return back()->with('status', 'You do not have access');
        }
    }

    public function savewithdrawpoint(Request $request)
    {
        $this->validate($request,['withdraw_point_name'=>'required|unique:withdraw_points'
                                ]);
        $withdrawpoint_name=new WithdrawPoint();
        $withdrawpoint_name->withdraw_point_name=$request->input('withdraw_point_name');
        $withdrawpoint_name->save();

        return redirect('/withdrawpoint')->with('status', 'Withdraw Point has been added!');
    }

    public function editwithdrawpoint($id)
    {
           if (auth()->user()->type == 'editor' && auth()->user()->purpose_of_trans == 1)
      {
          $withdraw_point=WithdrawPoint::find($id);
      //  dd($twithdraw_point);
          return view('admin.datasetup.editwithdrawpoint')->with('withdraw_point',$withdraw_point);
        }
        else if(auth()->user()->type == 'checker') 
        {
          return back()->with('status', 'You do not have access');
        }

    }

    public function updatewithdrawpoint(Request $request)
    {  
        $this->validate($request,['withdraw_point_name'=>'required|unique:withdraw_points'
    ]);
        $withdraw_point=WithdrawPoint::find($request->input('id'));
        $withdraw_point->withdraw_point_name=$request->input('withdraw_point_name');
       

        $withdraw_point->update();
        return redirect('/withdrawpoint')->with('status','Withdraw Point has been updated!');
    }

    public function deletewithdrawpoint($id)
    {
     
        $withdraw_point=WithdrawPoint::find($id);

        $withdraw_point->delete();
        return back()->with('status','Withdraw Point \has been deleted!'); 
        

    }
}
