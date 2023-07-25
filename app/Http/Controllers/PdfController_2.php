<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outwards;
use App\Models\ExchangeRate;
use App\Models\Company;
use Session;

class PdfController_2 extends Controller
{
    // public function showOutwardslip()
    // {
    //     return view('admin.dailytransaction.slip');
    // }


    public function viewpdf($id){

        Session::put('id', $id);
        try{
            $pdf = \App::make('dompdf.wrapper')->setPaper('a5', 'landscape');
            $pdf->loadHTML($this->convert_orders_data_to_html());

            return $pdf->stream();

           // return $pdf->download('voucher.pdf');


        }
        catch(Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
    public function downloadpdfoutward($id){

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

        $data_outputs = Outwards::where('id',Session::get('id'))->get();
        $rate = ExchangeRate::select('exchange_rate','currency_code')->get();
        $company = Company::find(1);
        // $data_outputs_array = $data_outputs->toarray();
        // $data_array = $data->toarray();

        // $merge_array = array_merge($data_outputs_array,$data_array);
        // $merge_array_collection =  collect($merge_array);
        // dd($merge_array_collection);

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
          <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Name:<span style="font-weight: normal;"> '.$data->sender_name.'</span></td>
          <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Name:<span style="font-weight: normal;"> '.$data->receiver_name.'</span></td>
          </tr>
          <tr style="border: 2px solid black;">
          <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender NRC/Passport:<span style="font-weight: normal;"> '.$data->sender_nrc_passport.'</span></td>
          <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver NRC/Passport:<span style="font-weight: normal;"> '.$this->get_starred($data->receiver_nrc_passport).'</span></td>
          </tr>
          <tr style="border: 2px solid black;">
          <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Address:<span style="font-weight: normal;"> '.$add_ph[0].' </span></td>
          <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Country:<span style="font-weight: normal;"> '.$data->receiver_country_code.'</span></td>
          </tr>
          <tr style="border: 2px solid black;">
          <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Phone Number:<span style="font-weight: normal;"> '.$add_ph[1].' </span></td>
          <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Equivalent USD:<span style="font-weight: normal;"> '.$data->equivalent_usd.'</span></td>
        </tr>
          <tr style="border: 2px solid black;">
          <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Purpose of Transaction:<span style="font-weight: normal;"> '.$data->purpose.'</span></td>
          <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;"">MMK:<span style="font-weight: normal;"> '.$data->amount_mmk.'</span></td>
          </tr>
          <tr style="border: 2px solid black;">
          <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Withdraw Point:<span style="font-weight: normal;"> '.$data->deposit_point.'</span></td>
          <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;"></td>
          </tr>
      </table>
      <img style="width:100%;height:20%";margin-left:30%;" src="frontend/images/sign.PNG">
      <img style="width:50%;height:7%;margin-left:25%" src="frontend/images/voucher.png">
      <img style="width:70%;height:auto;margin-left:15%;margin-top:2%;" src="frontend/images/text30.png">
      <button style="background-color:green;padding:10px;float:right;"><a style="text-decoration:none;color:white;font-weight:bold;padding:10px;" href="/downloadpdfoutward/'.$data->id.'">Print</a></button>

      </body>
    </html>';


        return $output;
        }
    }


    public function convert_orders_data_to_html_download(){

      $data_outputs = Outwards::where('id',Session::get('id'))->get();
      $rate = ExchangeRate::select('exchange_rate','currency_code')->get();
      $company = Company::find(1);
      // $data_outputs_array = $data_outputs->toarray();
      // $data_array = $data->toarray();

      // $merge_array = array_merge($data_outputs_array,$data_array);
      // $merge_array_collection =  collect($merge_array);
      // dd($merge_array_collection);


      foreach($data_outputs as $data)
      {
        $address_ph = $data->receiver_address_ph;
          $separator = '/';
          $add_ph = explode($separator, $address_ph);
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
            <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Name:<span style="font-weight: normal;"> '.$data->sender_name.'</span></td>
            <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Name:<span style="font-weight: normal;"> '.$data->receiver_name.'</span></td>
            </tr>
            <tr style="border: 2px solid black;">
            <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender NRC/Passport:<span style="font-weight: normal;"> '.$data->sender_nrc_passport.'</span></td>
            <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver NRC/Passport:<span style="font-weight: normal;"> '.$this->get_starred($data->receiver_nrc_passport).'</span></td>
            </tr>
            <tr style="border: 2px solid black;">
            <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Address:<span style="font-weight: normal;"> '.$add_ph[0].' </span></td>
            <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Country:<span style="font-weight: normal;"> '.$data->receiver_country_code.'</span></td>
            </tr>
            <tr style="border: 2px solid black;">
            <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Sender Phone Number:<span style="font-weight: normal;"> '.$add_ph[1].' </span></td>
            <td  style="border: 2px solid black;font-size: 10px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">'.$data->currency_code.':<span style="font-weight: normal;"> '.$data->equivalent_usd.'</span></td>
          </tr>
            <tr style="border: 2px solid black;">
            <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Purpose of Transaction:<span style="font-weight: normal;"> '.$data->purpose.'</span></td>
            <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;"">MMK:<span style="font-weight: normal;"> '.$data->amount_mmk.'</span></td>
            </tr>
            <tr style="border: 2px solid black;">
          <td  style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;">Withdraw Point:<span style="font-weight: normal;"> '.$data->deposit_point.'</span></td>
          <td style="border: 2px solid black;font-size: 10px;height: 20px;padding-left: 10px;width:50%;font-weight: bold;"></td>
          </tr>
        </table>
        <img style="width:100%;height:20%";margin-left:30%;" src="frontend/images/sign.PNG">
        <img style="width:50%;height:7%;margin-left:25%" src="frontend/images/voucher.png">
        <img style="width:70%;height:auto;margin-left:15%;margin-top:2%;" src="frontend/images/text30.png">
        </body>
      </html>';


          return $output;
          }
  }



}


