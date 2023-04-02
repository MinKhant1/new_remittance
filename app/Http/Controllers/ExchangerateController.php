<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExchangeRate;
use App\Models\Currency;
use Session;

class ExchangerateController extends Controller
{
    public function exchangerate()
    {
       if (auth()->user()->type == 'editor' && auth()->user()->exchange_rate == 1)
      {
        $exchangerates = ExchangeRate::All();

        return view('admin.datasetup.exchangerate')->with('exchange_rates', $exchangerates);
      }
      else
      {
        return back()->with('status', 'You do not have access');
      }
        
    }

    public function addexchangerate()
    {      
      $currencies = Currency::All()->pluck('currency_code', 'currency_code');
         if (auth()->user()->type == 'editor' && auth()->user()->exchange_rate == 1)
      {
          return view('admin.datasetup.addexchangerate')->with('currencies', $currencies);
        }
        else if(auth()->user()->type == 'checker') 
        {
          return back()->with('status', 'You do not have access');
        }
    }

    public function saveexchangerate(Request $request)
    {
        $this->validate($request, ['currency_code'=>'required|unique:exchange_rates',
                                    'exchange_rate' =>'required|unique:exchange_rates']);

        $exchangerates = new ExchangeRate();
        $exchangerates->currency_code = $request->input('currency_code');
        $exchangerates->exchange_rate = $request->input('exchange_rate');
        $exchangerates->save();

        return redirect('/exchangerate')->with('status', 'Exchange Rate has been added!');
    }

    public function editexchangerate($id)
    {
     
         if (auth()->user()->type == 'editor' && auth()->user()->exchange_rate == 1)
      {
           $currencies = Currency::All()->pluck('currency_code', 'currency_code');
          $exchangerates = ExchangeRate::find($id);

          return view('admin.datasetup.editexchangerate')->with('exchange_rates', $exchangerates)->with('currencies', $currencies);
        }
        else if(auth()->user()->type == 'checker') 
        {
          return back()->with('status', 'You do not have access');
        }
      }
    

    public function updateexchangerate(Request $request)
    {
        $this->validate($request, ['currency_code'=>'required',
                                    'exchange_rate' =>'required']);

        $exchangerates = ExchangeRate::find($request->input('id'));

        $exchangerates->currency_code = $request->input('currency_code');
        $exchangerates->exchange_rate = $request->input('exchange_rate');

        $exchangerates->update();

        return redirect('/exchangerate')->with('status', 'Exchange Rate has been updated!');
    }

    public function deleteexchangerate($id)
    {
       
        $exchangerates = ExchangeRate::find($id);

        $exchangerates->delete();

        return back()->with('status', 'Exchange Rate has been deleted!');
        
    }
}

