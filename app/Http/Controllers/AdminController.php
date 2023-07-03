<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Models\Inwards;
use App\Models\Outwards;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function inward_customer_list()
    {

      $startDate=null;
      $endDate=null;


        $customers=Inwards::select('*')->distinct('receiver_name')->get();

        $unique_customers=$customers->unique('receiver_nrc_passport');

       session()->put('customers',$unique_customers);


      

      return view('admin.dailytransaction.inwardcustomerlist')
                                        ->with('customers',$unique_customers)
                                        ->with('customer_type','all')
                                        ->with('startDate',$startDate)
                                        ->with('endDate',$endDate);
    }

    public function outward_customer_list()
    {
      $startDate=null;
      $endDate=null;


        $customers=Outwards::select('*')->distinct('sender_name')->get();

        $unique_customers=$customers->unique('sender_nrc_passport');

       session()->put('customers',$unique_customers);


      

      return view('admin.dailytransaction.outwardcustomerlist')
                                          ->with('customers',$unique_customers)
                                          ->with('customer_type','all')
                                          ->with('startDate',$startDate)
                                          ->with('endDate',$endDate);
    }

    public function inward_customer_list_filtered(Request $request)
    {
        $customer_type=$request->customer_type;
       
       $startDate=$request->startDate;
      $endDate=$request->endDate;
        
      
     
        if($customer_type=='residence')
        {
            $customers=Inwards::select('*')->whereBetween('created_at', [Carbon::parse($startDate)->toDateString(),Carbon::parse($endDate)->toDateString()])->distinct('receiver_name')->get();
       
            $customers= $this->filterInwardResidence($customers,true);


        }
        else if($customer_type=='non-residence')
        {
            $customers=Inwards::select('*')->whereBetween('created_at', [Carbon::parse($startDate)->toDateString(),Carbon::parse($endDate)->toDateString()])->distinct('receiver_name')->get();
            $customers= $this->filterInwardResidence($customers,false);
        }
        else 
        {
            $customers=Inwards::select('*')->whereBetween('created_at', [Carbon::parse($startDate)->toDateString(),Carbon::parse($endDate)->toDateString()])->distinct('receiver_name')->get();

        }
    

        $unique_customers=$customers->unique('receiver_nrc_passport');
        session()->put('customers',$unique_customers);

        return view('admin.dailytransaction.inwardcustomerlist')->with('customers',$unique_customers)
                                                                ->with('customer_type',$customer_type)
                                                                ->with('startDate',$startDate)
                                                                ->with('endDate',$endDate);
    }


   

    public function outward_customer_list_filtered(Request $request)
    {
      $customer_type=$request->customer_type;

      $startDate=$request->startDate;
      $endDate=$request->endDate;
      
      
      if($customer_type=='residence')
      {
          $customers=Outwards::select('*')->whereBetween('created_at', [Carbon::parse($startDate)->toDateString(),Carbon::parse($endDate)->toDateString()])->distinct('sender_name')->get();
     
          $customers= $this->filterouwardResidence($customers,true);


      }
      else if($customer_type=='non-residence')
      {
          $customers=Outwards::select('*')->whereBetween('created_at', [Carbon::parse($startDate)->toDateString(),Carbon::parse($endDate)->toDateString()])->distinct('sender_name')->get();
          $customers= $this->filterouwardResidence($customers,false);
      }
      else 
      {
          $customers=Outwards::select('*')->whereBetween('created_at', [Carbon::parse($startDate)->toDateString(),Carbon::parse($endDate)->toDateString()])->distinct('sender_name')->get();

      }
  

      $unique_customers=$customers->unique('sender_nrc_passport');
      session()->put('customers',$unique_customers);

      return view('admin.dailytransaction.outwardcustomerlist')->with('customers',$unique_customers)->with('customer_type',$customer_type)
      ->with('startDate',$startDate)
      ->with('endDate',$endDate);
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


    public function filterouwardResidence($customers,$bol)
    {
      $the_customers=$customers;

      if ($bol) {
            foreach ($the_customers as $key=>$customer) {
             if (!$this->isResidence($customer->sender_nrc_passport)) {
              unset($the_customers[$key]);
             }
            }
      }
      else
      {
        foreach ($the_customers as $key=>$customer) {
          if ($this->isResidence($customer->sender_nrc_passport)) {
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

    public function inward_customer_export()
    {
      $customers=session()->get('customers');

      $customer_results=collect();

    $index=0;
      foreach ($customers as $key=>$customer) {
      $customer_results->put($index,['name'=>$customer->receiver_name,'Nrc'=>$customer->receiver_nrc_passport,'address_ph'=>$customer->receiver_address_ph]);
      $index++;
      }
    

   
    

    return Excel::download(new CustomerExport($customer_results),'Customers.xlsx');

    }

    public function outward_customer_export()
    {
      $customers=session()->get('customers');

      $customer_results=collect();

      $index=0;
      foreach ($customers as $key=>$customer) {
      $customer_results->put($index,['name'=>$customer->sender_name,'Nrc'=>$customer->sender_nrc_passport,'address_ph'=>$customer->sender_address_ph]);
      $index++;
      }
    

   
    

    return Excel::download(new CustomerExport($customer_results),'Customers.xlsx');

    }

    public function inward_customer_detail(Request $request)
    {
     
      $nrc=$request->nrc;
      
        $transactions=Inwards::where('receiver_nrc_passport',$nrc)->get();
        $total_inward_transactions=$transactions->count();
        $name=$transactions[0]->receiver_name;
    //  dd($name);
        // $outwards=Outwards::where('sender_nrc_passport',$nrc)->get();

    
        return view('admin.dailytransaction.inwardcustomerdetail')->with('name',$name)
                                                                  ->with('nrc',$nrc)
                                                                  ->with('total_count',$total_inward_transactions)
                                                                  ->with('inward_transactions',$transactions);



    }



    public function outward_customer_detail(Request $request)
    {
     
      $nrc=$request->nrc;
      
        $transactions=Outwards::where('sender_nrc_passport',$nrc)->get();
        $total_outward_transactions=$transactions->count();
        $name=$transactions[0]->sender_name;


    
        return view('admin.dailytransaction.outwardcustomerdetail')->with('name',$name)
                                                                  ->with('nrc',$nrc)
                                                                  ->with('total_count',$total_outward_transactions)
                                                                  ->with('outward_transactions',$transactions);



    }

}
