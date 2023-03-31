<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Model\Appointment;
use App\Model\Doctor;
use Session;
use validate;
use DB;
use App\User;
use App\Model\Token;
use App\Model\Setting;
class AppointmentController extends Controller
{
  
   public function showappointment($startdate,$enddate){
   	   if($startdate==0&&$enddate==0){
   	   	  $data=Appointment::with('doctors','services','department')->get();
   	   }else{
   	   	  $data=Appointment::with('doctors','services','department')->whereBetween('date', [$startdate,$enddate])->get();
   	   }
   	   
   	   return view("admin.appointment.default")->with("data",$data)->with("startdate",$startdate)->with("enddate",$enddate);
   }

   public function showdoctorappointment(){
   	   $data=Appointment::with('referdocto','referdocby')->where("doc_id",Auth::id())->get();
   	   return view("doctor.appointment")->with("data",$data);
   }

   public function changeorderstatus($staus_id,$app_id){
            DB::beginTransaction();
              try {
                             $msg="";
                             $store=Appointment::find($app_id);
                             $setting=Setting::find(1);
                             $user=User::find($store->user_id);
                             if(!$user){
                                 Session::flash('message',__('messages.user_not_exist')); 
                                 Session::flash('alert-class', 'alert-success');
                                 return redirect()->back();
                             }
                             if($staus_id==3){//approve
                                
                                  $msg=__('messages.approve appointement successfully');
                                  $android=$this->send_notification_android($setting->android_server_key,$store->user_id,$msg,$app_id);
                                  $ios=$this->send_notification_IOS($setting->ios_server_key,$store->user_id,$msg,$app_id); 
                             }
                             if($staus_id==6){//reject
                                
                                  $msg=__('messages.appointement reject successfully');
                                  $android=$this->send_notification_android($setting->android_server_key,$store->user_id,$msg,$app_id);
                                  $ios=$this->send_notification_IOS($setting->ios_server_key,$store->user_id,$msg,$app_id); 
                             }
                             if($staus_id==4){//complete
                                
                                  $msg=__('messages.appointement complete successfully');
                                  $android=$this->send_notification_android($setting->android_server_key,$store->user_id,$msg,$app_id);
                                  $ios=$this->send_notification_IOS($setting->ios_server_key,$store->user_id,$msg,$app_id); 
                             }


                             $store->status=$staus_id;
                             $store->save();
                             
                             DB::commit();
                             Session::flash('message',$msg); 
                             Session::flash('alert-class', 'alert-success');
                             return redirect()->back();
                    }
              catch (\Exception $e) {
                   DB::rollback();
                   Session::flash('message',$e); 
                   Session::flash('alert-class', 'alert-danger');
                   return redirect()->back();       
              }
   }

   
   public function getreferdoctor($id){
      $getappointment=Appointment::find($id);
      $getlist=Doctor::where("department_id",$getappointment->department_id)->where("user_id",'!=',$getappointment->doc_id)->get();
      $txt="";
      if(count($getlist)>0)
      {
         foreach ($getlist as $g) {
           $txt=$txt.'<option value='.$g->user_id.'>'.$g->name.'</option>';
         }
      }

      return $txt;
   }

   public function apmrefer(Request $request){
      DB::beginTransaction();
              try {
                             $msg="";
                             $store=Appointment::find($request->get("app_id"));
                             $setting=Setting::find(1);
                             $user=User::find($store->user_id);
                             $referdoc=User::find($request->get("refer_id"));
                             if(!$user){
                                 Session::flash('message',__('messages.user_not_exist')); 
                                 Session::flash('alert-class', 'alert-success');
                                 return redirect()->back();
                             }
                             if($request->get("status")==5){//refer
                                
                                  $msg=__('messages.Appointment Refer Another Doctor Successfully').' '.$referdoc->name;
                                  $android=$this->send_notification_android($setting->android_server_key,$store->user_id,$msg,$request->get("app_id"));
                                  $ios=$this->send_notification_IOS($setting->ios_server_key,$store->user_id,$msg,$request->get("app_id")); 
                             }
                             
                             $store->status=$request->get("status");
                             $store->date=$request->get("date");
                             $store->time=$request->get("time");
                             $store->doctor_discription=$request->get("doctor_discription");
                             $store->refer_to=$request->get("refer_id");
                             $store->save();
                             $data=new Appointment();
                             $data->department_id=$store->department_id;
                             $data->service_id=$store->service_id;
                             $data->doc_id=$request->get("refer_id");
                             $data->name=$store->name;
                             $data->phone_no=$store->phone_no;
                             $data->refer_by=$store->doc_id;
                             $data->user_id=$store->user_id;
                             $data->date=$request->get("date");
                             $data->time=$request->get("time");
                             $data->messages=$store->messages;   
                             $data->status=1; 
                             $data->save();             
                             DB::commit();
                             Session::flash('message',$msg); 
                             Session::flash('alert-class', 'alert-success');
                             return redirect()->back();
                    }
              catch (\Exception $e) {
                   DB::rollback();
                   Session::flash('message',$e); 
                   Session::flash('alert-class', 'alert-danger');
                   return redirect()->back();       
              }
   }

   public function apmreschedule(Request $request){
        DB::beginTransaction();
              try {
                             $msg="";
                             $store=Appointment::find($request->get("app_id"));
                             $setting=Setting::find(1);
                             $user=User::find($store->user_id);
                             if(!$user){
                                 Session::flash('message',__('messages_error_success.user_not_exist')); 
                                 Session::flash('alert-class', 'alert-success');
                                 return redirect()->back();
                             }
                             if($request->get("status")==2){//approve
                                
                                  $msg=__('messages.Appointment Reschedule Successfully');
                                  $android=$this->send_notification_android($setting->android_server_key,$store->user_id,$msg,$request->get("app_id"));
                                  $ios=$this->send_notification_IOS($setting->ios_server_key,$store->user_id,$msg,$request->get("app_id")); 
                             }
                             
                             $store->status=$request->get("status");
                             $store->date=$request->get("date");
                             $store->time=$request->get("time");
                             $store->doctor_discription=$request->get("doctor_discription");
                             $store->save();
                             
                             DB::commit();
                             Session::flash('message',$msg); 
                             Session::flash('alert-class', 'alert-success');
                             return redirect()->back();
                    }
              catch (\Exception $e) {
                   DB::rollback();
                   Session::flash('message',$e); 
                   Session::flash('alert-class', 'alert-danger');
                   return redirect()->back();       
              }
   }


     public function send_notification_android($key,$user_id,$msg,$id){
          $getuser=Token::where("type",1)->where("user_id",$user_id)->get();
          if(count($getuser)!=0){               
               $reg_id = array();
               foreach($getuser as $gt){
                   $reg_id[]=$gt->token;
               }
               $registrationIds =  $reg_id;    
               $message = array(
                    'message' => $msg,
                    'key'=>__('messages.Appointment'),
                    'title' => __('messages.appointmentstatus'),
                    'order_id'=>$id
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
                   $response=json_decode($result,true); 
                  } catch (\Exception $e) {
                    return 0;
                 }
             if(isset($response)&&$response['success']>0)
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
   public function send_notification_IOS($key,$user_id,$msg,$id){
      $getuser=Token::where("type",2)->where("user_id",$user_id)->get();
         if(count($getuser)!=0){               
               $reg_id = array();
               foreach($getuser as $gt){
                   $reg_id[]=$gt->token;
               }
                $registrationIds =  $reg_id;    
                $message = array(
                   'body'  => $msg,
                   'title'     => __('messages.Notification'),
                   'vibrate'   => 1,
                   'sound'     => 1,
                   'key'=>__('messages.Appointment'),
                   'order_id'=>$id
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
                   $response=json_decode($result,true); 
                  } catch (\Exception $e) {
                    return 0;
                 }
             if(isset($response)&&$response['success']>0)
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

}
