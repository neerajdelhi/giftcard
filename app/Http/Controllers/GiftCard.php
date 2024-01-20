<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class GiftCard extends Controller
{
    public function validationcheck(Request $request){
        
        $coupon = ($request->coupon)  ? $request->coupon : '';
        $client = new Client([
            'auth' => ['ck_ad713bc399f8d63da81a3583057b3e7b3d0899d4', 'cs_ee0259074bde553ce2008e6e0cd3994f99da77d5']
        ]);
        
        if(empty($coupon)) return "Please enter valid coupon";
        $response = $client->get('https://etesting.space/wp-json/wc-pimwick/v1/pw-gift-cards/'.$coupon);

        $body = $response->getBody();
        // echo $response->getStatusCode() . PHP_EOL;

        return $body;
    }

    public function save(Request $request){

        Log::info('All data--'.json_encode($request->all()));

        $validate = $request->validate([
            'coupon' => 'required',
            'balance' => 'required',
        ]);

        $pay = new Payment;
        $pay->coupon = $request->coupon;
        $pay->balance = $request->balance;
        $pay->customer_no = $request->customer_no;
        $pay->save();
        
        return redirect('/')->with('success','data stored.');
    }
}
