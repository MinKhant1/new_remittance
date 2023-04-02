<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\blacklists;
use Session;

class BlacklistController extends Controller
{
    public function blacklist()
    {
         if (auth()->user()->type == 'editor' && auth()->user()->blacklist == 1)
      {
        
        $blacklists = blacklists::All();

        return view('admin.datasetup.blacklist')->with('blacklists', $blacklists);
      }
      else
      {
        return back()->with('status', 'You do not have access');
      }
    }

    public function addblacklist()
    {
         if (auth()->user()->type == 'editor' && auth()->user()->blacklist == 1)
      {

        return view('admin.datasetup.addblacklist');
      }
      else if(auth()->user()->type == 'checker')
      {
        return back()->with('status', 'You do not have access');
      }      
    }
    

    public function saveblacklist(Request $request)
    {
        $this->validate($request, ['nrc_passportno'=>'required|unique:blacklists',
                                   'name'=>'required|unique:blacklists']);

        $blacklists = new blacklists();
        $blacklists->nrc_passportno = $request->input('nrc_passportno');
        $blacklists->name = $request->input('name');
        $blacklists->save();

        return redirect('/blacklist')->with('status', 'Black List has been added!');
    }

    public function editblacklist($id)
    {
            if (auth()->user()->type == 'editor' && auth()->user()->blacklist == 1)
      {

          $blacklists = blacklists::find($id);

          return view('admin.datasetup.editblacklist')->with('blacklists', $blacklists);
        }
        else if(auth()->user()->type == 'checker')
        {
          return back()->with('status', 'You do not have access');
        } 
    }

    public function updateblacklist(Request $request)
    {
        $this->validate($request, ['nrc_passportno'=>'required',
                                   'name'=>'required']);

        $blacklists = blacklists::find($request->input('id'));

        $blacklists->nrc_passportno = $request->input('nrc_passportno');
        $blacklists->name = $request->input('name');

        $blacklists->update();

        return redirect('/blacklist')->with('status', 'Black List has been updated!');
    }

    public function deleteblacklist($id)
    {
   
        $blacklists = blacklists::find($id);

        $blacklists->delete();

        return back()->with('status', 'Black List has been deleted!');
        
    }
}
