<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Model\Notification;
use App\Model\Token;
use App\Model\Setting;
use App\Model\Newsletter;
use Session;
use DataTables;
use DateTimeZone;
use DateTime;
use Mail;
use validate;
class NotificationController extends Controller
{
 
     public function index(){
        return view("admin.setting.notification");
     }
   
     public function savenotification(Request $request){
        $keys=Setting::find(1);
        $msg=$request->get("message");
        $android=$this->send_notification_android($keys->android_server_key,$msg);
        $ios=$this->send_notification_IOS($keys->ios_server_key,$msg);
        if($android==1||$ios==1){
             $store=new Notification();
             $store->message=$request->get("message");
             $store->save();
             Session::flash('message',__("messages.Notification Send Successfully")); 
             Session::flash('alert-class', 'alert-success');
             return redirect("admin/notification");
        }
        else{
            Session::flash('message',__('messages.Notification Not Send Successfully')); 
            Session::flash('alert-class', 'alert-danger');
            return redirect("admin/notification");
        }
     }

     public function send_notification_android($key,$msg){
        $getuser=Token::where("type",1)->get();
        $i=0;
        if(count($getuser)!=0){
               $reg_id = array();
               foreach($getuser as $gt){
                   $reg_id[]=$gt->token;
               }
               $regIdChunk=array_chunk($reg_id,1000);
               foreach ($regIdChunk as $k) {
                       $registrationIds =  $k;    
                       $message = array(
                            'message' => $msg,
                            'title' =>  __('messages.Notification')
                          );
                       $fields = array(
                          'registration_ids'  => $registrationIds,
                          'data'              => $message
                       );
                       $url = 'https://fcm.googleapis.com/fcm/send';
                       $headers = array(
                         'Authorization: key='.$key,// . $api_key,
                         'Content-Type: application/json'
                       );
                      $json =  json_encode($fields);   
                       try {
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
                            $result = curl_exec($ch);  
              
                            if ($result === FALSE){
                               die('Curl failed: ' . curl_error($ch));
                            }     
                           curl_close($ch);
                           $response[]=json_decode($result,true);
                      } catch (\Exception $e) {
                       
                      }
               }
              $succ=0;
               foreach ($response as $k) {
                  $succ=$succ+$k['success'];
               }
             if($succ>0)
              {
                   return 1;
              }
            else
               {
                  return 0;
               }
        }
        return 0;
   }
   public function send_notification_IOS($key,$msg){
      $getuser=Token::where("type",2)->get();
         if(count($getuser)!=0){               
               $reg_id = array();
               foreach($getuser as $gt){
                   $reg_id[]=$gt->token;
               }
                
              $regIdChunk=array_chunk($reg_id,1000);
               foreach ($regIdChunk as $k) {
                       $registrationIds =  $k;    
                       $message = array(
                            'message' => $msg,
                            'title' => __('messages.Notification')
                          );
                       $fields = array(
                          'registration_ids'  => $registrationIds,
                          'data'              => $message
                       );
                       $url = 'https://fcm.googleapis.com/fcm/send';
                       $headers = array(
                         'Authorization: key='.$key,// . $api_key,
                         'Content-Type: application/json'
                       );
                      $json =  json_encode($fields);   
                       try {
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
                            $result = curl_exec($ch);  
              
                            if ($result === FALSE){
                               die('Curl failed: ' . curl_error($ch));
                            }     
                           curl_close($ch);
                           $response[]=json_decode($result,true);
                      } catch (\Exception $e) {
                       
                      }
               }
              $succ=0;
               foreach ($response as $k) {
                  $succ=$succ+$k['success'];
               }
             if($succ>0)
              {
                   return 1;
              }
            else
               {
                  return 0;
               }
        }
        return 0;
   }


   public function showsetting($id){
       $setting=Setting::find(1);
       $timezone=$this->generate_timezone_list();
       return view("admin.setting.setting")->with("id",$id)->with("data",$setting)->with("timezone",$timezone);
   }

   public function savebasicsetting(Request $request){
       $setting=Setting::find(1);
       $img=$setting->logo;
       $file_name=$setting->logo;
       if($request->get("logo")){
              $data = $request->get("logo");
              list($type, $data) = explode(';', $data);
              list(, $data)      = explode(',', $data);
              $folderName = '/upload/images/';
              $destinationPath = public_path() . $folderName;
              $file_name=uniqid() . '.png';
              $file = $destinationPath .$file_name;
              $data = base64_decode($data);
              file_put_contents($file, $data);
              $setting->logo=$file_name;
       }
      
       $setting->address=$request->get("address");
       $setting->email=$request->get("email");
       $setting->phone_no=$request->get("phone_no");
       $setting->timezone=$request->get("timezone");
       $setting->currency=$request->get("currency");
       $setting->save();
        if($img!=$file_name){
                        $image_path="";
                        $image_path = public_path() ."/upload/images/".$img;
                        if(file_exists($image_path)) {
                            try{
                                 unlink($image_path);
                            }
                            catch(\Exception $e)
                            {
                                
                            }
                            
                        }
                }
       return redirect('admin/setting'.'/2');
   }

   public function saveserverkey(Request $request){
       $setting=Setting::find(1);
       $setting->android_server_key=$request->get("android_server_key");
       $setting->ios_server_key=$request->get("ios_server_key");
       $setting->TWILIO_ACCOUNT_SID=$request->get("TWILIO_ACCOUNT_SID");
       $setting->TWILIO_API_KEY=$request->get("TWILIO_API_KEY");
       $setting->TWILIO_API_SECRET=$request->get("TWILIO_API_SECRET");
       $setting->TWILIO_CHAT_SERVICE_SID=$request->get("TWILIO_CHAT_SERVICE_SID");
       $setting->TWILIO_AUTH_TOKEN=$request->get("TWILIO_AUTH_TOKEN");
       $setting->save();
       return redirect('admin/setting'.'/3');
   }

   public function savesitesetting(Request $request){
       $setting=Setting::find(1);
       $setting->facebook_id=$request->get("facebook_id");
       $setting->twitter_id=$request->get("twitter_id");
       $setting->google_id=$request->get("google_id");
       $setting->instagram_id=$request->get("instagram_id");
       $setting->happy_client=$request->get("happy_client");
       $setting->surgery_complete=$request->get("surgery_complete");
       $setting->expert_doctor=$request->get("expert_doctor");
       $setting->worldwide_branch=$request->get("worldwide_branch");
       $setting->working_day=$request->get("working_day");
       
       $setting->save();
       return redirect('admin/setting'.'/4');
   }

   public function saveprivacy(Request $request){
       $setting=Setting::find(1);
       $setting->terms_condition=$request->get("termscondition");
       return redirect('admin/setting'.'/5');
   }

   public function saveterms(Request $request){
       $setting=Setting::find(1);
       $setting->privacy_policy=$request->get("privacydata");
       return redirect('admin/setting'.'/5');
   }

   public function changesettingstatus($fields){
      $setting=Setting::find(1);
      if($setting->$fields=='0'){
        $setting->$fields='1';
      }else{
        $setting->$fields='0';
      }
      $setting->save();
      return redirect()->back();
   }
     
   static public function generate_timezone_list(){
    static $regions = array(
      DateTimeZone::AFRICA,
      DateTimeZone::AMERICA,
      DateTimeZone::ANTARCTICA,
      DateTimeZone::ASIA,
      DateTimeZone::ATLANTIC,
      DateTimeZone::AUSTRALIA,
      DateTimeZone::EUROPE,
      DateTimeZone::INDIAN,
      DateTimeZone::PACIFIC,
    );

    $timezones = array();
    foreach($regions as $region) {
      $timezones = array_merge($timezones, DateTimeZone::listIdentifiers($region));
    }

    $timezone_offsets = array();
    foreach($timezones as $timezone) {
      $tz = new DateTimeZone($timezone);
      $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
    }

    asort($timezone_offsets);

    $timezone_list = array();
    
    foreach($timezone_offsets as $timezone=>$offset){
      $offset_prefix = $offset < 0 ? '-' : '+';
      $offset_formatted = gmdate('H:i', abs($offset));
      $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
      $timezone_list[] = "(${pretty_offset}) $timezone";
    }

    return $timezone_list;
    ob_end_flush();
  }


  public function shownews(){
          return view("admin.news");
     }
     
     public function sendnews(Request $request){
          $msg=$request->get("news");
          $getall=Newsletter::all();
          $setting=Setting::find(1);
          foreach($getall as $g){
              $data=array();
              $data['email']=$g->email;
              $data['msg']=$msg;
                try {
                      $result=Mail::send('email.news', ['user' => $data], function($message) use ($data){
                         $message->to($data['email'],'customer')->subject(__('messages.site_name'));
                      });
            
               } catch (\Exception $e) {
               }
        
          }
       Session::flash('message',__('messages.News Send Successfully'));
       Session::flash('alert-class', 'alert-success');
       return redirect()->back();
     }


     public function saveuploadsection(Request $request){
        $store=Setting::find(1);
          if ($request->hasFile('banner')) 
        {
                 $img=$store->main_banner;
                 $file = $request->file('banner');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/web';
                 $picture = time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('banner')->move($destinationPath, $picture);
                 $store->main_banner =$picture;
                 $image_path=public_path().'/upload/web/'.$img;
                  if(file_exists($image_path)) {
                            try{
                                 unlink($image_path);
                            }
                            catch(\Exception $e)
                            {
                                
                            }
                            
                        }
        }
        $store->save();
        return redirect()->back();
     }
}
