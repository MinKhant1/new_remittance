<?php

namespace App\Http\Controllers;

use App\Models\Dates;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Inwards;
use App\Models\ExchangeRate;
use App\Models\Company;
use Session;

class PdfController extends Controller
{
    // public function showoutwardslip()
    // {
    //     return view('admin.dailytransaction.slip');
    // }


    public function viewpdf($id){

        Session::put('id', $id);

   
        try{


          $pdf = \App::make('dompdf.wrapper')->setPaper('a5', 'landscape');

          $pdf->loadHTML($this->convert_orders_data_to_html());
      

           




            return $pdf->stream();

          

        }
        catch(Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
    public function downloadpdfinward($id){

        Session::put('id', $id);
        try{
            $pdf = \App::make('dompdf.wrapper')->setPaper('a5', 'landscape');
            $pdf->loadHTML($this->convert_orders_data_to_html_download());

          //  return $pdf->stream();

            return $pdf->download('voucher.pdf');


        }
        catch(Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
    function get_starred($str) {
      if($str==null)
      {
        return '';
      }
      $len = strlen($str);
      return substr($str, 0, 2).str_repeat('*', $len - 4).substr($str, $len - 2, 3);
  }

  function get_image($img)
  {

    $img_src = 'frontend/images/'.$img;
    return $img_src;
  }

    public function convert_orders_data_to_html(){

        $data_outputs = Inwards::where('id',Session::get('id'))->get();
        $rate = ExchangeRate::select('exchange_rate','currency_code')->get();
        $company = Company::find(1);
       

        foreach($data_outputs as $data)
        {
          $address_ph = $data->receiver_address_ph;
          $separator = '/';
          if($address_ph!=null)
          {

            if(str_contains($address_ph,$separator))
            {

              $add_ph = explode($separator, $address_ph);
            }
            else
            {
              $add_ph=array('',$address_ph);
            }
          }
          else
          {
            $add_ph=array('','');
            
          }
       
          if($this->isText30_valid($data->created_at))
          {
            $output = '
            <!DOCTYPE html>
    <meta charset="UTF-8">
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
    
    <div style="font-family: Arial, sans-serif; font-size: 18px; color: #333;">
        <div style="display: inline-block; vertical-align: middle;">
            <img src='.$this->get_image($company->image).' alt="Company Logo" style="width: 100px; height: 100px; margin-right: 20px;">
        </div>
        <div style="display: inline-block; vertical-align: middle;">
            <h1 style="font-size: 24px; font-weight: bold; margin: 0;">'.$company->company_name.'</h1>
            <p style="margin: 0;">'.$company->company_phno.'</p>
            <p style="margin: 0;">'.$company->company_address.'</p>
        </div>
    </div>
    
    <table style="border: 2px solid black;border-collapse:collapse;width:100%">
            <tr style="border: 2px solid black;">
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Slip No. <span style="font-weight: normal;">'.$data->sr_id.'</span></td>
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Date:<span style="font-weight: normal;"> '.$data->created_at->toDateString().'</span></td>
            </tr>
            <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Name:<span style="font-weight: normal;"> '.$data->receiver_name.'</span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Name:<span style="font-weight: normal;"> '.$data->sender_name.'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver NRC/Passport:<span style="font-weight: normal;"> '.$data->receiver_nrc_passport.'</span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender NRC/Passport:<span style="font-weight: normal;"> '.$this->get_starred($data->sender_nrc_passport).'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Address:<span style="font-weight: normal;"> '.$add_ph[0].' </span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Country:<span style="font-weight: normal;"> '.$data->sender_country_code.'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Phone Number:<span style="font-weight: normal;"> '.$add_ph[1].' </span></td>
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">'.$data->currency_code.':<span style="font-weight: normal;"> '.$data->amount.'</span></td>
            </tr>
              <tr style="border: 2px solid black;">
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Purpose of Transaction:<span style="font-weight: normal;"> '.$data->purpose.'</span></td>
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;"">MMK:<span style="font-weight: normal;"> '.$data->amount_mmk.'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Withdraw Point:<span style="font-weight: normal;"> '.$data->withdraw_point.'</span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;"></td>
              </tr>
          </table>
          <img style="width:100%;height:20%";margin-left:30%;" src="frontend/images/sign.PNG">
          <img style="width:50%;height:7%;margin-left:25%" src="frontend/images/voucher.png">
          <img style="width:70%;height:auto;margin-left:15%;margin-top:2%;" src="frontend/images/text30.png">
          <button style="background-color:green;padding:10px;float:right;"><a style="text-decoration:none;color:white;font-weight:bold;padding:10px;" href="/downloadpdfinward/'.$data->id.'">Print</a></button>
    
          </body>
        </html>';
          }
          else
          {
            $output = '
            <!DOCTYPE html>
    <meta charset="UTF-8">
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
    
    <div style="font-family: Arial, sans-serif; font-size: 18px; color: #333;">
        <div style="display: inline-block; vertical-align: middle;">
            <img src='.$this->get_image($company->image).' alt="Company Logo" style="width: 100px; height: 100px; margin-right: 20px;">
        </div>
        <div style="display: inline-block; vertical-align: middle;">
            <h1 style="font-size: 24px; font-weight: bold; margin: 0;">'.$company->company_name.'</h1>
            <p style="margin: 0;">'.$company->company_phno.'</p>
            <p style="margin: 0;">'.$company->company_address.'</p>
        </div>
    </div>
    
    <table style="border: 2px solid black;border-collapse:collapse;width:100%">
            <tr style="border: 2px solid black;">
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Slip No. <span style="font-weight: normal;">'.$data->sr_id.'</span></td>
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Date:<span style="font-weight: normal;"> '.$data->created_at->toDateString().'</span></td>
            </tr>
            <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Name:<span style="font-weight: normal;"> '.$data->receiver_name.'</span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Name:<span style="font-weight: normal;"> '.$data->sender_name.'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver NRC/Passport:<span style="font-weight: normal;"> '.$data->receiver_nrc_passport.'</span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender NRC/Passport:<span style="font-weight: normal;"> '.$this->get_starred($data->sender_nrc_passport).'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Address:<span style="font-weight: normal;"> '.$add_ph[0].' </span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Country:<span style="font-weight: normal;"> '.$data->sender_country_code.'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Phone Number:<span style="font-weight: normal;"> '.$add_ph[1].' </span></td>
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">'.$data->currency_code.':<span style="font-weight: normal;"> '.$data->amount.'</span></td>
            </tr>
              <tr style="border: 2px solid black;">
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Purpose of Transaction:<span style="font-weight: normal;"> '.$data->purpose.'</span></td>
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;"">MMK:<span style="font-weight: normal;"> '.$data->amount_mmk.'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Withdraw Point:<span style="font-weight: normal;"> '.$data->withdraw_point.'</span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;"></td>
              </tr>
          </table>
          <img style="width:100%;height:20%";margin-left:30%;" src="frontend/images/sign.PNG">
          <img style="width:50%;height:7%;margin-left:25%" src="frontend/images/voucher.png">
    
          <button style="background-color:green;padding:10px;float:right;"><a style="text-decoration:none;color:white;font-weight:bold;padding:10px;" href="/downloadpdfinward/'.$data->id.'">Print</a></button>
    
          </body>
        </html>';
          }
       


        return $output;
        }
    }





    public function convert_orders_data_to_html_download(){

      $data_outputs = Inwards::where('id',Session::get('id'))->get();
      $rate = ExchangeRate::select('exchange_rate','currency_code')->get();
      $company = Company::find(1);
   


      foreach($data_outputs as $data)
      {
        $address_ph = $data->receiver_address_ph;
          $separator = '/';
          if($address_ph!=null)
          {

            $add_ph = explode($separator, $address_ph);
          }
          else
          {
            $add_ph=array('','');
            
          }
       
          if($this->isText30_valid($data->created_at))
          {
            $output = '
            <!DOCTYPE html>
    <meta charset="UTF-8">
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>

    <div style="font-family: Arial, sans-serif; font-size: 18px; color: #333;">
        <div style="display: inline-block; vertical-align: middle;">
            <img src='.$this->get_image($company->image).' alt="Company Logo" style="width: 100px; height: 100px; margin-right: 20px;">
        </div>
        <div style="display: inline-block; vertical-align: middle;">
            <h1 style="font-size: 24px; font-weight: bold; margin: 0;">Company Name</h1>
            <p style="margin: 0;">Company Phone Number</p>
            <p style="margin: 0;">Company Address</p>
        </div>
    </div>

    <table style="border: 2px solid black;border-collapse:collapse;width:100%">
            <tr style="border: 2px solid black;">
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Slip No. <span style="font-weight: normal;">'.$data->sr_id.'</span></td>
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Date:<span style="font-weight: normal;"> '.$data->created_at->toDateString().'</span></td>
            </tr>
            <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Name:<span style="font-weight: normal;"> '.$data->receiver_name.'</span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Name:<span style="font-weight: normal;"> '.$data->sender_name.'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver NRC/Passport:<span style="font-weight: normal;"> '.$data->receiver_nrc_passport.'</span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender NRC/Passport:<span style="font-weight: normal;"> '.$this->get_starred($data->sender_nrc_passport).'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Address:<span style="font-weight: normal;"> '.$add_ph[0].' </span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Country:<span style="font-weight: normal;"> '.$data->sender_country_code.'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Phone Number:<span style="font-weight: normal;"> '.$add_ph[1].' </span></td>
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">'.$data->currency_code.':<span style="font-weight: normal;"> '.$data->amount.'</span></td>
            </tr>
              <tr style="border: 2px solid black;">
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Purpose of Transaction:<span style="font-weight: normal;"> '.$data->purpose.'</span></td>
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;"">MMK:<span style="font-weight: normal;"> '.$data->total_mmk_amount.'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Withdraw Point:<span style="font-weight: normal;"> '.$data->withdraw_point.'</span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;"></td>
              </tr>
          </table>
          <img style="width:100%;height:20%";margin-left:30%;" src="frontend/images/sign.PNG">
          <img style="width:50%;height:7%;margin-left:25%" src="frontend/images/voucher.png">

          <img style="width:70%;height:auto;margin-left:15%;margin-top:2%;" src="frontend/images/text30.png">

        </body>
      </html>';
          }
          else
          {
            $output = '
            <!DOCTYPE html>
    <meta charset="UTF-8">
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>

    <div style="font-family: Arial, sans-serif; font-size: 18px; color: #333;">
        <div style="display: inline-block; vertical-align: middle;">
            <img src='.$this->get_image($company->image).' alt="Company Logo" style="width: 100px; height: 100px; margin-right: 20px;">
        </div>
        <div style="display: inline-block; vertical-align: middle;">
            <h1 style="font-size: 24px; font-weight: bold; margin: 0;">Company Name</h1>
            <p style="margin: 0;">Company Phone Number</p>
            <p style="margin: 0;">Company Address</p>
        </div>
    </div>

    <table style="border: 2px solid black;border-collapse:collapse;width:100%">
            <tr style="border: 2px solid black;">
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Slip No. <span style="font-weight: normal;">'.$data->sr_id.'</span></td>
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Date:<span style="font-weight: normal;"> '.$data->created_at->toDateString().'</span></td>
            </tr>
            <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Name:<span style="font-weight: normal;"> '.$data->receiver_name.'</span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Name:<span style="font-weight: normal;"> '.$data->sender_name.'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver NRC/Passport:<span style="font-weight: normal;"> '.$data->receiver_nrc_passport.'</span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender NRC/Passport:<span style="font-weight: normal;"> '.$this->get_starred($data->sender_nrc_passport).'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Address:<span style="font-weight: normal;"> '.$add_ph[0].' </span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Country:<span style="font-weight: normal;"> '.$data->sender_country_code.'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Phone Number:<span style="font-weight: normal;"> '.$add_ph[1].' </span></td>
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">'.$data->currency_code.':<span style="font-weight: normal;"> '.$data->amount.'</span></td>
            </tr>
              <tr style="border: 2px solid black;">
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Purpose of Transaction:<span style="font-weight: normal;"> '.$data->purpose.'</span></td>
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;"">MMK:<span style="font-weight: normal;"> '.$data->total_mmk_amount.'</span></td>
              </tr>
              <tr style="border: 2px solid black;">
              <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Withdraw Point:<span style="font-weight: normal;"> '.$data->withdraw_point.'</span></td>
              <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;"></td>
              </tr>
          </table>
          <img style="width:100%;height:20%";margin-left:30%;" src="frontend/images/sign.PNG">
          <img style="width:50%;height:7%;margin-left:25%" src="frontend/images/voucher.png">

        

        </body>
      </html>';

          }
             


      return $output;
      }
  }

  public function isText30_valid($date)
  {
      $text30_startdate_row=Dates::where('name','text30start')->first();
  
      if ($text30_startdate_row) {
       
        $text30_startdate=$text30_startdate_row->date;
      }
  
     $text30_enddate_row=Dates::where('name','text30end')->first();
  
     if ($text30_enddate_row)  {
     
      $text30_enddate=$text30_enddate_row->date;
     }
  
    if (!empty($text30_startdate)) {
     
  
      if (!empty($text30_enddate)) {
         
          if ($date>$text30_startdate && $date<$text30_enddate) {
            
             return true;
  
  
          }
          else
          {
            
              return false;
          }
      }
      else
      {
              if ($date>$text30_startdate) {
              
                  return true;
              }
              else
              {
                return false;
              }
      }
  
  
    }
    else
    {
              return false;
    }
  
  }

}


