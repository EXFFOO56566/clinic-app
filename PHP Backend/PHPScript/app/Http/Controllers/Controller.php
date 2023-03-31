<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Model\Setting;
use Twilio\Rest\Client;
use Auth;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use Twilio\Jwt\Grants\SyncGrant;
use Twilio\Jwt\Grants\ChatGrant;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

     public function gettoken(){
		        $identity = Auth::user()->email;
		        $setting=Setting::find(1);
		        $TWILIO_ACCOUNT_SID=$setting->TWILIO_ACCOUNT_SID;
		        $TWILIO_API_KEY=$setting->TWILIO_API_KEY;
		        $TWILIO_API_SECRET=$setting->TWILIO_API_SECRET;
		        $TWILIO_CHAT_SERVICE_SID=$setting->TWILIO_CHAT_SERVICE_SID;
		        
		        $token = new AccessToken(
		            $TWILIO_ACCOUNT_SID,
		            $TWILIO_API_KEY,
		            $TWILIO_API_SECRET,
		            3600,
		            $identity
		        );
		        
		            $grant = new VideoGrant();
		            $token->addGrant($grant);
		            if (!empty($TWILIO_CHAT_SERVICE_SID)) {

		                $chatGrant = new ChatGrant();
		                $chatGrant->setServiceSid($TWILIO_CHAT_SERVICE_SID);
		                $token->addGrant($chatGrant);
		            }
		            header('Content-type:application/json;charset=utf-8');
		            return  json_encode(array(
		                'identity' => $identity,
		                'token' => $token->toJWT(),
		            ));
     }
     
     
     public function pushgettoken($type){
                $identity = Auth::user()->email;
		        $setting=Setting::find(1);
		        $TWILIO_ACCOUNT_SID=$setting->TWILIO_ACCOUNT_SID;
		        $TWILIO_API_KEY=$setting->TWILIO_API_KEY;
		        $TWILIO_API_SECRET=$setting->TWILIO_API_SECRET;
		        $TWILIO_CHAT_SERVICE_SID=$setting->TWILIO_CHAT_SERVICE_SID;
		        $pushios=$setting->APNCredentialSid;
		        $pushandroid=$setting->GCM_or_FCM_Credential_Sid;
		        $token = new AccessToken(
		            $TWILIO_ACCOUNT_SID,
		            $TWILIO_API_KEY,
		            $TWILIO_API_SECRET,
		            3600,
		            $identity
		        );
		        
		            $grant = new VideoGrant();
		            $token->addGrant($grant);
		            if (!empty($TWILIO_CHAT_SERVICE_SID)) {

		                $chatGrant = new ChatGrant();
		                $chatGrant->setServiceSid($TWILIO_CHAT_SERVICE_SID);
		                if($type=='1'){//android
		                    $chatGrant->setPushCredentialSid($pushandroid);
		                }else if($type=='2'){//ios
		                    $chatGrant->setPushCredentialSid($pushios);
		                }else{
		                    
		                }
		                $token->addGrant($chatGrant);
		            }
		            header('Content-type:application/json;charset=utf-8');
		            return  json_encode(array(
		                'identity' => $identity,
		                'token' => $token->toJWT(),
		            ));
     }


   }
