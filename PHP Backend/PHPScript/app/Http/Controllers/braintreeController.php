<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Model\Appointment;
use App\Model\Doctor;
use Session;
use validate;
use App\Model\Department;
use App\Model\Paymentgateway;
use App\Model\Subscription;
use App\Model\Package;
use DB;
use Config;
use App\User;
use App\Model\Token;
use App\Model\Setting;
class braintreeController extends Controller
{
   
    public function showbrain(){
      $department=Department::all();
      $payment=Paymentgateway::find(2);
      $gateway = new \Braintree\Gateway([
          'environment' => $payment->mode,
          'merchantId' => $payment->merchant_key,
          'publicKey' => $payment->key,
          'privateKey' => $payment->secert
      ]);
      $token=$gateway->ClientToken()->generate();
      
      return view("braintree")->with("department",$department)->with("token",$token);
    }

    public function checkoutbrain(Request $request){
      $payment=Paymentgateway::find(2);
      $subscription=Package::find($request->get('package_id'));
      $gateway = new \Braintree\Gateway([
                      'environment' => $payment->mode,
                      'merchantId' => $payment->merchant_key,
                      'publicKey' => $payment->key,
                      'privateKey' => $payment->secert
                 ]);
        $amount = $request->get("amount");
        $nonce = $request->get("payment_method_nonce");

        $result = $gateway->transaction()->sale([
            'amount' => $subscription->price,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        if ($result->success) {
            $transaction = $result->transaction;
               $store=new Subscription();  
                      $store->name=trim($request->get("subscribername"));
                      $store->user_id=Auth::id();
                      $store->package_id=$request->get('package_id');
                      $store->transaction_id=$transaction->id;
                      $store->date=$request->get('package_date');
                      $store->time=$request->get('package_time');
                      $store->payment_type='2';
                      $store->amount=$subscription->price;
                      $store->save(); 
                      Session::flash("payment_done",1);
                      return redirect()->back();
        } else {
            $errorString = "";

            foreach($result->errors->deepAll() as $error) {
                $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
            }
            Session::flash('message',$errorString); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }
 

}
