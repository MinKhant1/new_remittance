<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransMaxLimit;
use Session;

class TransmaxlimitController extends Controller
{
    public function transmaxlimit()
    {
       if (auth()->user()->type == 'editor' && auth()->user()->trans_max_limit == 1)
      {
        $transmaxlimits = TransMaxLimit::All();

        return view('admin.datasetup.transmaxlimit')->with('trans_max_limits', $transmaxlimits);
      }
      else
      {
        return back()->with('status', 'You do not have access');
      }
    }

    public function addtransmaxlimit()
    {      
        if (auth()->user()->type == 'editor' && auth()->user()->trans_max_limit == 1)
      {
          return view('admin.datasetup.addtransmaxlimit');
        }
        else if(auth()->user()->type == 'checker') 
        {
          return back()->with('status', 'You do not have access');
        }
        
    }

    public function savetransmaxlimit(Request $request)
    {
        $this->validate($request, ['par_transaction'=>'required|unique:trans_max_limits',
                                   'par_month'=>'required|unique:trans_max_limits']);

        $transmaxlimits = new TransMaxLimit();
        $transmaxlimits->par_transaction = $request->input('par_transaction');
        $transmaxlimits->par_month = $request->input('par_month');
        $transmaxlimits->save();

        return redirect('/transmaxlimit')->with('status', 'Transaction Max Limit has been added!');
    }

    public function edittransmaxlimit($id)
    {
         if (auth()->user()->type == 'editor' && auth()->user()->trans_max_limit == 1)
      {
          $transmaxlimits = TransMaxLimit::find($id);

          return view('admin.datasetup.edittransmaxlimit')->with('trans_max_limits', $transmaxlimits);
        }
        else if(auth()->user()->type == 'checker') 
        {
          return back()->with('status', 'You do not have access');
        }
    }

    public function updatetransmaxlimit(Request $request)
    {
        $this->validate($request, ['par_transaction'=>'required',
                                   'par_month'=>'required']);

        $transmaxlimits = TransMaxLimit::find($request->input('id'));

        $transmaxlimits->par_transaction = $request->input('par_transaction');
        $transmaxlimits->par_month = $request->input('par_month');

        $transmaxlimits->update();

        return redirect('/transmaxlimit')->with('status', 'Transaction Max Limit has been updated!');
    }

    public function deletetransmaxlimit($id)
    {
       
        $transmaxlimits = TransMaxLimit::find($id);

        $transmaxlimits->delete();

        return back()->with('status', 'Transaction Max Limit has been deleted!');
        
    }
}
