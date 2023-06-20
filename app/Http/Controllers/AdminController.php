<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Models\Inwards;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function inward_customer_list()
    {

        $customers=Inwards::select('*')->whereDate('created_at', Carbon::today())->distinct('receiver_name')->get();

        $unique_customers=$customers->unique('receiver_nrc_passport')->pluck('receiver_name');

       session()->put('customers',$unique_customers);


      

      return view('admin.dailytransaction.inwardcustomerlist')->with('customers',$unique_customers)->with('customer_type','all');
    }

    public function inward_customer_list_filtered(Request $request)
    {
        $customer_type=$request->customer_type;

        
      
      
        if($customer_type=='residence')
        {
            $customers=Inwards::select('*')->whereDate('created_at', Carbon::today())->distinct('receiver_name')->get();
       
            $customers= $this->filterInwardResidence($customers,true);


        }
        else if($customer_type=='non-residence')
        {
            $customers=Inwards::select('*')->whereDate('created_at', Carbon::today())->distinct('receiver_name')->get();
            $customers= $this->filterInwardResidence($customers,false);
        }
        else 
        {
            $customers=Inwards::select('*')->whereDate('created_at', Carbon::today())->distinct('receiver_name')->get();

        }
    

        $unique_customers=$customers->unique('receiver_nrc_passport')->pluck('receiver_name');
        session()->put('customers',$unique_customers);

        return view('admin.dailytransaction.inwardcustomerlist')->with('customers',$unique_customers)->with('customer_type',$customer_type);
    }


    public function outward_customer_list()
    {
      return view('admin.dailytransaction.inwardcustomerlist');
    }

    public function outward_customer_list_filtered()
    {
      return view('admin.dailytransaction.inwardcustomerlist');
    }


    public function filterInwardResidence($customers,$bol)
    {
      $the_customers=$customers;

      if ($bol) {
            foreach ($the_customers as $key=>$customer) {
             if (!$this->isResidence($customer->receiver_nrc_passport)) {
              unset($the_customers[$key]);
             }
            }
      }
      else
      {
        foreach ($the_customers as $key=>$customer) {
          if ($this->isResidence($customer->receiver_nrc_passport)) {
            unset($the_customers[$key]);
          }
         }
      }

      return $the_customers;
    }

    public function isResidence($nrc)
    {
      $is_residence=false;
       $nrc_prefix=strtok($nrc,'/');
       $nrc_no=(int)$nrc_prefix;

       if($nrc_no>=1 && $nrc_no<=14) 
       {
        $is_residence=true;

       }
       else{
       $is_residence=false;
       }

       return $is_residence;
      
       

    }

    public function customer_export()
    {
      $customers=session()->get('customers');

      $customer_collect=collect();

    for ($i=0; $i <count($customers) ; $i++) { 
      $customer_collect->push(['name'=>$customers[$i]]);
    }

     // dd($customer_collect);

   
    

    return Excel::download(new CustomerExport($customer_collect),'Customers.xlsx');

    }

}
