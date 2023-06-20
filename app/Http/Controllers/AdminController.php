<?php

namespace App\Http\Controllers;

use App\Models\Inwards;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function inward_customer_list()
    {

        $customers=Inwards::whereDate('created_at', Carbon::today())->select('receiver_name')->orderBy('receiver_name')->get();
        dd($customers);

      return view('admin.dailytransaction.inwardcustomerlist');
    }

    public function inward_customer_list_filtered(Request $request)
    {
   

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
