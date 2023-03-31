<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Model\Subscription;
use App\Model\Department;
use App\Model\Setting;
use App\Model\Package;
use App\Model\Paymentgateway;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Session;
use DataTables;
use validate;
class SubscriptionController extends Controller
{
 
     public function showorderpage($id){
     	 $department=Department::all();
     	 $subscription=Package::find($id);
     	 $setting = Setting::find(1);
       $token="";
     	 $payment=Paymentgateway::where("is_active",'1')->get();
       if(count($payment)>0){
          foreach ($payment as $k) {
              if($k->id==2 &&$k->is_active==1){
               
                 $gateway = new \Braintree\Gateway([
                      'environment' => $k->mode,
                      'merchantId' => $k->merchant_key,
                      'publicKey' => $k->key,
                      'privateKey' => $k->secert
                 ]);
                 $token=$gateway->ClientToken()->generate();
                 break;
              } 
          }
         
       }
      
           return view("front.showorderpage")->with("packageid",$id)->with("department",$department)->with("subscription",$subscription)->with("payment",$payment)->with("token",$token)->with("setting",$setting);
     }

     public function showsubscription(){
        $data=Subscription::with('userdata','packagedata','paymentdata')->get();
        
        return view("admin.subscription")->with("data",$data);

     }
  
     public function paymentsubscription(Request $request){

            $payment=Paymentgateway::find(2);
            $package=Package::find($id);
     	      $input = $request->input();
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName($payment->key);
            $merchantAuthentication->setTransactionKey($payment->secert);
            $refId = 'ref' . time();
            $cardNumber = preg_replace('/\s+/', '', $input['cardNumber']);
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($cardNumber);
            $creditCard->setExpirationDate($input['expiration-year'] . "-" .$input['expiration-month']);
            $creditCard->setCardCode($input['cvv']);
            $paymentOne = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($package->price);
            $transactionRequestType->setPayment($paymentOne);
            $requests = new AnetAPI\CreateTransactionRequest();
            $requests->setMerchantAuthentication($merchantAuthentication);
            $requests->setRefId($refId);
            $requests->setTransactionRequest($transactionRequestType);
            $controller = new AnetController\CreateTransactionController($requests);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
            if ($response != null) {
            if ($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    $message_text = $tresponse->getMessages()[0]->getDescription().", Transaction ID: " . $tresponse->getTransId();
                    $msg_type = "success_msg";  
                      $store=new Subscription();  
                      $store->name=trim($input['owner']);
                      $store->user_id=Auth::id();
                      $store->package_id=$input['package_id'];
                      $store->transaction_id=$tresponse->getTransId();
                      $store->date=$input['package_date'];
                      $store->time=$input['package_time'];
                      $store->payment_type='1';
                      $store->amount=$package->price;
                      $store->save(); 
                      Session::flash("payment_done",1);
                      return redirect()->back();
                } else {
                    $message_text = __('messages.There were some issue with the payment. Please try again later');
                    $msg_type = "error_msg";
                    if ($tresponse->getErrors() != null) {
                        $message_text = $tresponse->getErrors()[0]->getErrorText();
                        $msg_type = "error_msg";                                    
                    }
                }
            } else {
                $message_text = __('messages.There were some issue with the payment. Please try again later');;
                $msg_type = "error_msg";                                    

                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $message_text = $tresponse->getErrors()[0]->getErrorText();
                    $msg_type = "error_msg";                    
                } else {
                    $message_text = $response->getMessages()->getMessage()[0]->getText();
                    $msg_type = "error_msg";
                }                
            }
        } else {
            $message_text = __('messages.No response returned');
            $msg_type = "error_msg";
        }
        return back()->with($msg_type, $message_text);
     }

     public function changepackagestatus($status,$id){
        $store=Subscription::find($id);
        $store->status=$status;
        $store->save();
        Session::flash('message',__('messages.Subscription Status Changes Successfully')); 
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
     }
}
