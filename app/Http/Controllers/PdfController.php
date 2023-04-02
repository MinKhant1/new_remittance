<?php

namespace App\Http\Controllers;

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

           // return $pdf->download('voucher.pdf');


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
      $len = strlen($str);
      return substr($str, 0, 2).str_repeat('*', $len - 4).substr($str, $len - 2, 3);
  }

  function get_image($img)
  {
    
    $img_src = 'images/company/'.$img;
    return $img_src;
  }

    public function convert_orders_data_to_html(){

        $data_outputs = Inwards::where('id',Session::get('id'))->get();
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
        //  dd($this->get_image($company->image));
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
<img style="width:50%;height:20%;margin-left:25%" src='.$this->get_image($company->image).'>

<table style="border: 2px solid black;border-collapse:collapse;width:100%">
        <tr style="border: 2px solid black;">
          <td  style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Slip No. <span style="font-weight: normal;">'.$data->sr_id.'</span></td>
          <td  style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Date:<span style="font-weight: normal;"> '.$data->created_at->toDateString().'</span></td>
        </tr>
        <tr style="border: 2px solid black;">
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Name:<span style="font-weight: normal;"> '.$data->receiver_name.'</span></td>
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Sender Name:<span style="font-weight: normal;"> '.$data->sender_name.'</span></td>
          </tr>
          <tr style="border: 2px solid black;">
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Receiver NRC/Passport:<span style="font-weight: normal;"> '.$data->receiver_nrc_passport.'</span></td>
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Sender NRC/Passport:<span style="font-weight: normal;"> '.$this->get_starred($data->sender_nrc_passport).'</span></td>
          </tr>
          <tr style="border: 2px solid black;">
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Address:<span style="font-weight: normal;"> '.$add_ph[0].' </span></td>
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Sender Country:<span style="font-weight: normal;"> '.$data->sender_country_code.'</span></td>
          </tr>
          <tr style="border: 2px solid black;">
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Phone Number:<span style="font-weight: normal;"> '.$add_ph[1].' </span></td>
          <td  style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">'.$data->currency_code.':<span style="font-weight: normal;"> '.$data->amount.'</span></td>
        </tr>
          <tr style="border: 2px solid black;">
          <td  style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Purpose of Transaction:<span style="font-weight: normal;"> '.$data->purpose.'</span></td>
          <td  style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;"">MMK:<span style="font-weight: normal;"> '.$data->amount_mmk.'</span></td>
          </tr>
      </table>
      <img style="width:100%;height:25%";margin-left:30%;" src="frontend/images/sign.PNG">
      <img style="width:50%;height:7%;margin-left:25%" src="frontend/images/voucher.png">
      <button style="background-color:green;padding:10px;float:right;"><a style="text-decoration:none;color:white;font-weight:bold;padding:10px;" href="/downloadpdfinward/'.$data->id.'">Print</a></button>

      </body>
    </html>';


        return $output;
        }
    }


    public function convert_orders_data_to_html_download(){

      $data_outputs = Inwards::where('id',Session::get('id'))->get();
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
        //  dd($this->get_image($company->image));
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
<img style="width:50%;height:20%;margin-left:25%" src="'.$this->get_image($company->image).'">

<table style="border: 2px solid black;border-collapse:collapse;width:100%">
        <tr style="border: 2px solid black;">
          <td  style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Slip No. <span style="font-weight: normal;"> '.$data->sr_id.'</span></td>
          <td  style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Date:<span style="font-weight: normal;"> '.$data->created_at->toDateString().'</span></td>
        </tr>
        <tr style="border: 2px solid black;">
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Name:<span style="font-weight: normal;"> '.$data->receiver_name.'</span></td>
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Sender Name:<span style="font-weight: normal;"> '.$data->sender_name.'</span></td>
          </tr>
          <tr style="border: 2px solid black;">
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Receiver NRC/Passport:<span style="font-weight: normal;"> '.$data->receiver_nrc_passport.'</span></td>
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Sender NRC/Passport:<span style="font-weight: normal;"> '.$this->get_starred($data->sender_nrc_passport).'</span></td>
          <tr style="border: 2px solid black;">
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Address:<span style="font-weight: normal;"> '.$add_ph[0].' </span></td>
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Sender Country:<span style="font-weight: normal;"> '.$data->sender_country_code.'</span></td>
          </tr>
          <tr style="border: 2px solid black;">
          <td style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Receiver Phone Number:<span style="font-weight: normal;"> '.$add_ph[1].' </span></td>
          <td  style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">'.$data->currency_code.':<span style="font-weight: normal;"> '.$data->amount.'</span></td>
        </tr>
          <tr style="border: 2px solid black;">
          <td  style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;">Purpose of Transaction:<span style="font-weight: normal;"> '.$data->purpose.'</span></td>
          <td  style="border: 2px solid black;font-size: 14px;height: 30px;padding-left: 10px;width:50%;font-weight: bold;"">MMK:<span style="font-weight: normal;"> '.$data->amount_mmk.'</span></td>
          </tr>
      </table>
      <img style="width:100%;height:25%";margin-left:30%;" src="frontend/images/sign.PNG">
      <img style="width:50%;height:7%;margin-left:25%" src="frontend/images/voucher.png">


    </body>
  </html>';


      return $output;
      }
  }



}


