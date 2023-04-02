<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\CurrencyList;
use Session;

class CurrencyController extends Controller
{


    public function currency()
    {
      if (auth()->user()->type == 'editor' && auth()->user()->currency == 1)
      {
        $currencies=Currency::All();

        return view('admin.datasetup.currency')->with('currencies',$currencies);
      }
      else
      {
        return back()->with('status', 'You do not have access');
      }

    }
    public function addcurrency()
    {
        $currencies_lists = CurrencyList::All();
       if (auth()->user()->type == 'editor' && auth()->user()->currency == 1)
      {
          return view('admin.datasetup.addcurrency')->with('currencies_lists', $currencies_lists);
        }
        else if(auth()->user()->type == 'checker')
        {
          return back()->with('status', 'You do not have access');
        }
    }
    public function savecurrency(Request $request)
    {
        $this->validate($request,['currency_code'=>'required|unique:currencies',
                                    'currency_name'=>'required|unique:currencies'
                                ]);
        $currency=new Currency();
        $currency->currency_code=$request->input('currency_code');
        $currency->currency_name=$request->input('currency_name');
        $currency->save();

        $exchangerates = new ExchangeRate();
        $exchangerates->currency_code = $request->input('currency_code');
        $exchangerates->save();

        return redirect('/currency')->with('status', 'Currency and Exchange Rate have been added!');
    }

    public function editcurrency($id)
    {

        if (auth()->user()->type == 'editor' && auth()->user()->currency == 1)
      {
          $currencies=Currency::find($id);

          return view('admin.datasetup.editcurrency')->with('currencies',$currencies);
        }
        else if(auth()->user()->type == 'checker')
        {
          return back()->with('status', 'You do not have access');
        }
      }


    public function updatecurrency(Request $request)
    {
        $this->validate($request,['currency_code'=>'required|unique:currencies',
                                  'currency_name'=>'required|unique:currencies'
                                 ]);
        $currency=Currency::find($request->input('id'));
        $currency->currency_code=$request->input('currency_code');
        $currency->currency_name=$request->input('currency_name');

        $currency->update();
        return redirect('/currency')->with('status','Currency has been updated!');
    }

    public function deletecurrency($id)
    {

        $currency=Currency::find($id);
        $exchangerates=ExchangeRate::find($id);

        $currency->delete();
        $exchangerates->delete();
        return back()->with('status','Currency and Exchange Rate have been deleted!');


    }



}
