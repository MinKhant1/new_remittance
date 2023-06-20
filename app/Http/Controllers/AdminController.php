<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function inward_customer_list()
    {
      return view('admin.dailytransaction.inwardcustomerlist');
    }

    public function inward_customer_list_filtered(Request $request)
    {
        dd($request->all());

    }


    public function outward_customer_list()
    {
      return view('admin.dailytransaction.inwardcustomerlist');
    }

    public function outward_customer_list_filtered()
    {
      return view('admin.dailytransaction.inwardcustomerlist');
    }

}
