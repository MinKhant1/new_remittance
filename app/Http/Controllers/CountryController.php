<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\CountryList;
use Session;
use DB;

class CountryController extends Controller
{
  public function country()
    {
         if (auth()->user()->type == 'editor' && auth()->user()->country == 1)
    {
       $countries = Country::all();

        return view('admin.datasetup.country')->with('countries', $countries);
    }
    else
    {
      return back()->with('status', 'You do not have access');
    }
    }

    public function addcountry()
    {
        $country_lists = CountryList::All();
        
        //print_r($country_lists->toArray());
        // $countries_names = CountryList::All()->pluck('country_name', 'country_name');
        if (auth()->user()->type == 'editor' && auth()->user()->country == 1)
    {
          return view('admin.datasetup.addcountry')->with('country_lists', $country_lists);
        }
        else if(auth()->user()->type == 'checker')
        {
          return back()->with('status', 'You do not have access');
        }
     }


    public function savecountry(Request $request)
    {
      $this->validate($request, ['country_code'=>'required|unique:countries',
                                 'country_name'=>'required|unique:countries']);

      $country = new Country();
      $country->country_code = $request->input('country_code');
      $country->country_name = $request->input('country_name');
      $country->save();

      return redirect('/country')->with('status', 'Country has been added!');
    }

    public function editcountry($id)
    {
      $country_lists = CountryList::All();
       if (auth()->user()->type == 'editor' && auth()->user()->country == 1)
    {
        
        return view('admin.datasetup.editcountry')->with('country_lists', $country_lists);
      }
      else if(auth()->user()->type == 'checker')
      {
        return back()->with('status', 'You do not have access');
      }
    }


    public function updatecountry(Request $request)
    {
      $this->validate($request, ['country_code'=>'required',
                                 'country_name'=>'required']);

      $country = Country::find($request->input('id'));

      $country->country_code = $request->input('country_code');
      $country->country_name = $request->input('country_name');

      $country->update();

      return redirect('/country')->with('status', 'Country has been updated!');
    }

    public function deletecountry($id)
    {

      $country = Country::find($id);

      $country->delete();

      return back()->with('status', 'country has been deleted!');

    }
}
