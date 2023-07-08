<?php

namespace App\Http\Controllers;

use App\Models\PurposeOfTrans;
use Illuminate\Http\Request;
use Session;

class PurposeOfTransController extends Controller
{

    public function purposeoftrans()
    {
        if (auth()->user()->type == 'editor' && auth()->user()->purpose_of_trans == 1)
      {
        $purposes=PurposeOfTrans::All();
        return view('admin.datasetup.purposeoftrans')->with('purposes',$purposes);
      }
      else
      {
        return back()->with('status', 'You do not have access');
      }
    }
    public function addpurposeoftrans()
    {
         if (auth()->user()->type == 'editor' && auth()->user()->purpose_of_trans == 1)
      {
          return view('admin.datasetup.addpurposeoftrans');
        }
        else if(auth()->user()->type == 'checker') 
        {
          return back()->with('status', 'You do not have access');
        }
    }

    public function savepurposeoftrans(Request $request)
    {
        $this->validate($request,['purpose_name'=>'required|unique:purpose_of_trans'
                                ]);
        $purpose=new PurposeOfTrans();
        $purpose->purpose_name=$request->input('purpose_name');
        $purpose->save();

        return redirect('/purposeoftrans')->with('status', 'Purpose Of Transactions has been added!');
    }

    public function editpurposeoftrans($id)
    {
           if (auth()->user()->type == 'editor' && auth()->user()->purpose_of_trans == 1)
      {
          $purposes=PurposeOfTrans::find($id);

          return view('admin.datasetup.editpurposeoftrans')->with('purposes',$purposes);
        }
        else if(auth()->user()->type == 'checker') 
        {
          return back()->with('status', 'You do not have access');
        }

    }

    public function updatepurposeoftrans(Request $request)
    {  
        $this->validate($request,['purpose_name'=>'required:purpose_of_trans']);
        $purpose=PurposeOfTrans::find($request->input('id'));
        $purpose->purpose_name=$request->input('purpose_name');
       

        $purpose->update();
        return redirect('/purposeoftrans')->with('status','Purpose Of Transactions has been updated!');
    }

    public function deletepurposeoftrans($id)
    {
     
        $purpose=PurposeOfTrans::find($id);

        $purpose->delete();
        return back()->with('status','Purpose Of Transactions has been deleted!'); 
        

    }

}
