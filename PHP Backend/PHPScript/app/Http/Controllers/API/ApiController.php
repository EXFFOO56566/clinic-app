<?php

namespace App\Http\Controllers\api;
error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('memory_limit', '-1');


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Sentinel;
use Validator;
use App\User;
use App\Model\Appointment;
use App\Model\Review;
use App\Model\Token;
use App\Model\Package;
use App\Model\Service;
use App\Model\Album;
use App\Model\Setting;
use App\Model\Gallery;
use App\Model\Doctor;
use App\Model\ContactUs;
use App\Model\Paymentgateway;
use DateTimeZone;
use App\Model\Department;
use App\Model\DepartService;
use App\Model\Reportspam;
use App\Model\TimeTable;
use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use Twilio\Jwt\Grants\SyncGrant;
use Twilio\Jwt\Grants\ChatGrant;
use App\Model\Resetpassword;
use App\Model\Subscription;
use DateTime;
use Image;
use Auth;
use Mail;
use DB;
class ApiController extends Controller {
   
     public function bookappointment(Request $request){
           $response = array("status" => 0, "register" => "Validation error");
           $rules = [
                      'department_id' => 'required',
                      'service_id' => 'required',
                      'doctor_id' => 'required',
                      'name' => 'required',
                      'phone_no' => 'required',
                      'date' => 'required',
                      'time' => 'required',
                      'user_id'=>'required',
                      'messages' => 'required'
                    ];                    
            $messages = array(
                      'department_id.required' => "Department Id is required",
                      'service_id.required' => "Service Id is required",
                      'doctor_id.required' => "Doctor Id is required",
                      'name.required' => "name is required",
                      'phone_no.required' => "phone_no is required",
                      'date.required' => "date is required",
                      'time.required' => "time is required",
                      'user_id.required' => "user_id is required",
                      'messages.required' => "messages is required"

            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {                 
                       $data=new Appointment();
                       $data->department_id=$request->get("department_id");
                       $data->service_id=$request->get("service_id");
                       $data->doc_id=$request->get("doctor_id");
                       $data->name=$request->get("name");
                       $data->phone_no=$request->get("phone_no");
                       $data->user_id=$request->get("user_id");
                       $data->date=$request->get("date");
                       $data->time=$request->get("time");
                       $data->messages=$request->get("messages");   
                       $data->status=1;              
                       $data->save();
                       $response = array("status" =>1, "msg" => "Bookappointment Add Successfully","data"=>$data->id);
           }
           return Response::json($response);
     }  

     public function userregister(Request $request){
           $response = array("status" => 0, "msg" => "Validation error");
           $rules = [
                      'name' => 'required',
                      'email' => 'required|unique:users',
                      'password' => 'required',
                      'phone'=>'required',
                      'device_token'=>'required',
                      'device_type'=>'required',
                      'image'=>'required'              
                    ];                    
            $messages = array(
                      'name.required' => "name is required",
                      'email.unique' => 'Email Already exist',
                      'email.required' => "Email are required",                      
                      'password.required' => "password is required",
                      'phone.required'=>"phone is required",
                      'device_token.required'=>"Device Token is required",
                      'device_type.required'=>"Device Type is required",
                      'image'=>"image is required"
            );

           
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $message = '';
                $messages_l = json_decode(json_encode($validator->messages()), true);
                foreach ($messages_l as $msg) {
                    $message .= $msg[0] . ", ";
                }
                $response['msg'] = $message;
            }  else {
              $img_url="";
                if ($request->hasFile('image')) 
                  {
                     $file = $request->file('image');
                     $filename = $file->getClientOriginalName();
                     $extension = $file->getClientOriginalExtension() ?: 'png';
                     $folderName = '/upload/profile';
                     $picture = 'profile_'.mt_rand(100000,999999). '.' . $extension;
                     $destinationPath = public_path() . $folderName;
                     $request->file('image')->move($destinationPath, $picture);
                     $img_url = $picture;
                 }
                $user =User::where("email",$request->get("email"))->get(); 
                if(count($user)==0){
                            $user=new User();
                            $user->name=$request->get("name");
                            $user->email=$request->get("email");
                            $user->password=$request->get("password");
                            $user->login_type=1;
                            $user->phone_no=$request->get("phone");
                            $user->usertype=1;  
                            $user->profile_pic=$img_url;               
                            $user->save();
                            $gettoken=Token::where("token",$request->get("token"))->first();
                            if($gettoken){
                              $gettoken->user_id=$user->id;
                              $gettoken->save();
                            }else{
                               $store=new Token();
                               $store->token=$request->get("device_token");
                               $store->type=$request->get("device_type");
                               $store->user_id=$user->id;
                               $store->save();
                            }

                             
                              
                             if($user->user_sid){
                                 if($img_url!=""){
                                       $image="profile/".$img_url;
                                 }
                                 else{
                                       $image="profile/profile.png";
                                 }
                                 
                             }
                            $user->profile_pic=asset('public/upload/profile').'/'.$user->profile_pic;
                            $response = array("status" =>1, "msg" => "Register Successfully","data"=>$user);
                 
                }
                 else{
                  $response = array("status" =>0, "msg" => "Something wrong");
                 }                
           }
          return Response::json($response);
     }
     
     public function adddoctosdetail(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://demo.freaktemplate.com/bookappointment/api/getalldoctors",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                 "cache-control: no-cache",
                 "postman-token: 7c85dd11-e916-790e-6dda-8f782d43888e"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
             $data = json_decode($response);
             foreach($data as $d){
                    $user = new User();
                    $user->name = $d->name;
                    $user->email = $d->email;
                    $user->password = $d->password;
                    $user->profile_pic = $d->image;
                    $user->phone_no = $d->phoneno;
                    $user->usertype = '3';
                    $user->save();
                    $store = new Doctor();
                    $store->user_id=$user->id;
                    $store->department_id=$d->department_id;
                    $store->name = $d->name;
                    $store->email = $d->email;
                    $store->password = $d->password;
                    $store->phone_no = $d->phoneno;
                    $store->about_us = $d->aboutus;
                    $store->service = $d->services;
                    $store->working_hour = $d->working_time;
                    $store->facebook_id = $d->facebook_url;
                    $store->twitter_id = $d->twitter_url;
                    $store->google_id = $d->twitter_url;
                    $store->instagram_id = $d->facebook_url;
                    $store->image = $d->image;
                    $store->save();
                    for($i=1;$i<=7;$i++){
                        $a = new TimeTable();
                        $a->doctor_id = $user->id;
                        $a->day = $i;
                        $a->from = "09:00";
                        $a->to = "22:00";
                        $a->save();
                    }
             }
             
             echo "done data";exit;
          
        }
     }

     public function savetoken(Request $request){

       $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'token' => 'required',
                      'type'=>'required'             
                    ];                    
            $messages = array(
                      'token.required' => "token is required",
                      'type.required' => "type is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {
                if($request->get("token")!=""&&$request->get("type")!=""&&$request->get("token")!="null"){
                     $store=new Token();
                     $store->token=$request->get("token");
                     $store->type=$request->get("type");
                     $store->save();
                     $response = array("status" =>1, "msg" => "Token Save Successfully","data"=>$store);
                }
                else{

                 $response = array("status" =>0, "msg" => "Fields is Required");
                }
                
          }
           return Response::json($response);
   }

    public function postreview(Request $request){
           $response = array("status" => 0, "register" => "Validation error");
           $rules = [
                      'user_id' => 'required',
                      'doctor_id' => 'required',
                      'review' => 'required',
                      'ratting' => 'required'                 
                    ];                    
            $messages = array(
                      'user_id.required' => "user_id is required",
                      'doctor_id.required' => "doctor_id is required",
                      'review.required' => "review is required",
                      'ratting.required' => "ratting is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {
                $data=array();
                $data=new Review();
                $data->doctor_id=$request->get("doctor_id");
                $data->user_id=$request->get("user_id");
                $data->review=$request->get("review");
                $data->ratting=$request->get("ratting");
                $data->save();
                $response = array("status" =>1, "msg" => "Review Add Successfully","data"=>$data);
           }
          return Response::json($response);
     }



     public function listofdepartment(Request $request){
          $data=Department::select('id','name','image','description')->paginate(10);
          if($data){
               foreach ($data as $k) {
                   $k->image=asset('public/upload/department').'/'.$k->image;
               }
              $response = array("status" =>1, "msg" => "Department List Get Successfully","data"=>$data);
          }else{
              $response = array("status" =>0, "msg" => "Data Not Found","data"=>$data);
          }
          return Response::json($response);
     }


     public function departmentdetailbyid(Request $request){
           $response = array("status" => 0, "msg" => "Validation error");
           $rules = [
                      'department_id' => 'required'          
                    ];                    
            $messages = array(
                      'department_id.required' => "department_id is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {               
                     $data=Department::select("id","name","description","emergency_no","image")->find($request->get("department_id"));
                     if($data){
                         $data->image=asset('public/upload/department').'/'.$data->image;
                         $data->service=DepartService::select("id","name","price","price_for")->where("department_id",$request->get("department_id"))->get();
                         $response = array("status" =>1, "msg" => "Department get Successfully","data"=>$data);
                     }else{
                         $response = array("status" =>0, "msg" => "Department Not Found","data"=>$data);
                     }
                   
           }
           return Response::json($response);
     }

     public function listoffacilities(){
         $data=Service::select("id","icon","name","description")->paginate(10);
          if($data){
               foreach ($data as $k) {
                   $k->icon=asset('public/upload/service').'/'.$k->icon;
               }
              $response = array("status" =>1, "msg" => "Facilities List Get Successfully","data"=>$data);
          }else{
              $response = array("status" =>0, "msg" => "Facilities Not Found","data"=>$data);
          }
          return Response::json($response);
     }
  
     public function listofgallerycategory(){
        $data=Album::paginate(10);
        if($data){               
              $response = array("status" =>1, "msg" => "List Of Gallery Category","data"=>$data);
          }else{
              $response = array("status" =>0, "msg" => "Gallery Not Found","data"=>$data);
          }
          return Response::json($response);
     }

     public function listofimagebycategoryid(Request $request){
         $response = array("status" => 0, "msg" => "Validation error");
           $rules = [
                      'album_id' => 'required'          
                    ];                    
            $messages = array(
                      'album_id.required' => "album_id is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {        
                      if($request->get("album_id")==0){
                          $data=Gallery::paginate(10);
                      }else{
                           $data=Gallery::where("album_id",$request->get("album_id"))->paginate(10);
                      }
                     
                     if($data){
                          foreach ($data as $k) {
                             $k->image=asset('public/upload/gallery').'/'.$k->image;
                          }
                          $response = array("status" =>1, "msg" => "Image get Successfully","data"=>$data);
                     }else{
                         $response = array("status" =>0, "msg" => "Image Not Found","data"=>$data);
                     }
                   
           }
           return Response::json($response);
     }

     public function getlistofappointment(Request $request){
            $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'doc_id' => 'required'          
                    ];                    
            $messages = array(
                      'doc_id.required' => "doc_id is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {               
                     $future=Appointment::where("doc_id",$request->get("doc_id"))->where("date",">=",date('Y-m-d'))->paginate(10);
                     $past=Appointment::where("doc_id",$request->get("doc_id"))->where("date","<",date('Y-m-d'))->paginate(10);

                     if($future||$past){
                          $appointment=array("upcomming"=>$future,"past"=>$past);
                          $response = array("status" =>1, "msg" => "Appointment Get Successfully","data"=>$appointment);
                     }else{
                         $response = array("status" =>0, "msg" => "Appointment Not Found","data"=>array());
                     }
                   
           }
           return Response::json($response);
     }

     public function listofdoctorbydepartment(Request $request){
       $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'department_id' => 'required'          
                    ];                    
            $messages = array(
                      'department_id.required' => "department_id is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {      
                     if($request->get("department_id")==0){
                          $doctor=Doctor::paginate(5);
                     }else{
                          $doctor=Doctor::select("user_id","id","image","name","about_us","facebook_id","twitter_id","google_id","instagram_id","department_id")->where("department_id",$request->get("department_id"))->paginate(5);
                     }
                     if($doctor){
                          foreach ($doctor as $k) {
                             $dep=Department::find($k->department_id);
                             $k->department_name=$dep->name;
                             $k->image=asset('public/upload/doctor').'/'.$k->image;
                             unset($k->department_id);
                             $k->id=$k->user_id;
                             unset($k->user_id);
                          }
                          $response = array("status" =>1, "msg" => "Doctors Get Successfully","data"=>$doctor);
                     }else{
                         $response = array("status" =>0, "msg" => "Doctors Not Found","data"=>array());
                     }
                   
           }
           return Response::json($response);
     }


      public function showlogin(Request $request){
         
           $response = array("success" => "0", "register" => "Validation error");
           $rules = [
                          'login_type' => 'required',
                          'device_token' => 'required',
                          'device_type'=>'required',
                          'email' => 'required',    
                    ];
                    if($request->input('login_type')=='1'){
                           $rules['password'] = 'required';
                    }
                    if($request->input('login_type')=='2'||$request->input('login_type')=='3'){
                            $rules['name']='required';
                    }
                   
            $messages = array(
                      'login_type.required' => "login_type is required",
                      'device_token.required' => "device_token is required",
                      'device_type.required' => "device_type is required",
                      'email.required' => "email is required",
                      'password.required'=>"password is required",                     
                      "name.required"=>"name is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['register'] = $message;
            } else {
                         if($request->input('login_type')=='1'){
                                $user=User::where("email",$request->get("email"))->where("password",$request->get("password"))->first();
                                    if($user){
                                                 Auth::login($user);
                                                 $gettoken=Token::where("token",$request->get("device_token"))->first();
                                                  if(!$gettoken){
                                                         $store=new Token();
                                                         $store->token=$request->get("device_token");
                                                         $store->type=$request->get("device_type");
                                                         $store->user_id=$user->id;
                                                         $store->save();
                                                  }
                                                  else{
                                                         $gettoken->user_id=$user->id;
                                                         $gettoken->save();
                                                  }
                                                  
                                                   
                                                  $gettoken=json_decode($this->pushgettoken($request->get("device_type"))); 
                                                  $user->token=$gettoken->token;                             
                                                  $user->profile_pic=asset('public/upload/profile').'/'.$user->profile_pic;
                                                  $response = array("status" =>1, "msg" => "Login Successfully","data"=>$user);
                                 
                                    }
                                  else{
                                       $response = array("status" =>0, "msg" => "Login Credentials Are Wrong");
                                  }
                         }
                         if($request->input('login_type')=='2' || $request->input('login_type')=='3'||$request->input('login_type')=='4'){
                                $checkuser=User::where("email",$request->get("email"))->first();
                                if($checkuser){//login
                                  $imgdata=$checkuser->profile_pic;
                                  Auth::login($checkuser);
                                  $png_url="";
                                  $gettoken=Token::where("token",$request->get("device_token"))->first();
                                          if(!$gettoken){
                                                 $store=new Token();
                                                 $store->token=$request->get("device_token");
                                                 $store->type=$request->get("device_type");
                                                 $store->user_id=$checkuser->id;
                                                 $store->save();
                                          }
                                           else{
                                                 $gettoken->user_id=$checkuser->id;
                                                 $gettoken->save();
                                          }
                            
                            
                                    
                                       if($request->get("image")!=""){
                                           $png_url = "profile-".mt_rand(100000, 999999).".png";
                                           $path = public_path().'/upload/profile/' . $png_url;
                                           $content=$this->file_get_contents_curl($request->get("image"));
                                              $savefile = fopen($path, 'w');
                                              fwrite($savefile, $content);
                                              fclose($savefile);
                                              $img=public_path().'/upload/profile/' . $png_url;
                                            $checkuser->profile_pic=$png_url;
                                       }
                                       if($imgdata!=$png_url){
                                           $image_path = public_path() ."/upload/profile/".$imgdata;
                                              if(file_exists($image_path)&&$imgdata!="") {
                                                    try {
                                                           unlink($image_path);
                                                    }
                                                    catch(Exception $e) {
                                                    }                        
                                              }
                                       }
                                      
                                       $gettoken=json_decode($this->pushgettoken($request->get("device_type"))); 
                                       // $checkuser->token=$gettoken->token;
                                      $checkuser->login_type=$request->input('login_type');
                                      $checkuser->save();
                                       $checkuser->token=$gettoken->token;
                                      $checkuser->profile_pic=asset('public/upload/profile').'/'.$checkuser->profile_pic;
                                     
                             $response = array("status" =>1, "msg" => "Login Successfully","data"=>$checkuser);
                             return Response::json($response);
                          }
                        else{//register
                           
                                $png_url="";
                                if($request->get("image")!=""){
                                     $png_url = "profile-".mt_rand(100000, 999999).".png";
                                     $path = public_path().'/upload/profile/' . $png_url;
                                     $content=$this->file_get_contents_curl($request->get("image"));
                                                $savefile = fopen($path, 'w');
                                                fwrite($savefile, $content);
                                                fclose($savefile);
                                                $img=public_path().'/upload/profile/' . $png_url;
                                }
                                $str=explode(" ", $request->get("name"));
                                $store=new User();
                                $store->name=isset($str[0])?$str[0]:"";
                                $store->email=$request->get("email");
                                $store->login_type=$request->get("login_type");
                                $store->profile_pic=$png_url;
                                $store->usertype='1';
                                $store->save();
                                Auth::login($user);
                                
                                                  $gettoken=json_decode($this->gettoken()); 
                                                  $store->token=$gettoken->token;   
                                                   if($store->user_sid){
                                                        try{
                                                             $setting=Setting::find(1);
                                                        $sid    = $setting->TWILIO_ACCOUNT_SID;
                                                        $token  = $setting->TWILIO_AUTH_TOKEN;
                                                              $twilio = new Client($sid, $token);
                                                              $array=array("id"=>$store->id,"name"=>$store->name,"profile"=>$store->profile_pic);
                                                              $userdata = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
                                                                   ->users($store->user_sid)
                                                                   ->update([
                                                                                "attributes" => json_encode($array)
                                                                            ]
                                                                   );
                                                        }catch(Exception $e){

                                                        }
                                                   }
                                $gettoken=Token::where("token",$request->get("token"))->update(["user_id"=>$store->id]);
                                 $users=User::find($store->id);
                                 $users->profile_pic=asset('public/upload/profile').'/'.$users->profile_pic;
                                 $gettoken=json_decode($this->pushgettoken($request->get("device_type"))); 
                                 $users->token=$gettoken->token;
                                 $response = array("status" =>1, "msg" => "Login Successfully","data"=>$users);
                                 return Response::json($response);
                          
                            
                        }
                  }
            }
            return Response::json($response);
   }

      public function file_get_contents_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
  }
   public function gethealthpackage(){
      $data=Package::all();
      if($data){
          $response = array("status" =>1, "msg" => "Package Get Successfully","data"=>$data);
      }else{
         $response = array("status" =>0, "msg" => "Package Not Found","data"=>$data);
      }
       return Response::json($response);
   }

   public function getuserpastappointment(Request $request){
      $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'user_id' => 'required'          
                    ];                    
            $messages = array(
                      'user_id.required' => "user_id is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {               
                     
                     $past=Appointment::where("user_id",$request->get("user_id"))->select("doc_id","user_id","department_id","id","name","date","time","status","phone_no",'messages','service_id')->where("date","<",date('Y-m-d'."00:00:00"))->paginate(5);
                     $data=array();
                     if($past){
                          foreach ($past as $f) {
                               
                                $getdata=Doctor::where("user_id",$f->doc_id)->first();
                                if($getdata){
                                    $departmentdata=Department::find($getdata->department_id);
                                    $getserivce=Service::find($f->service_id);
                                  
                                    if($getserivce){
                                        $f->service_name=$getserivce->name;
                                    }else{
                                        $f->service_name="";
                                    }
                                    if($departmentdata){
                                        $f->department_name=$departmentdata->name;
                                    }else{
                                        $f->department_name='';
                                    }
                                    $f->doctor_name=$getdata->name;
                                    $f->image=asset('public/upload/doctor').'/'.$getdata->image;
                                    $f->name=$f->name;
                                    $f->id=$f->id;
                                }else{
                                    $f->department_name='';
                                    $f->image='';
                                    $f->name='';
                                    $f->id='';
                                    $f->doctor_name='';
                                }
                                
                               
                                $date=date_create($f->date);
                                $f->date=date_format($date,"d,F Y");
                               // $f->time=date_create($f->time);
                                //$f->time=date_format($time,"H:i A");
                                
                                
                                if($f->status==3){
                                    $f->status="Approve";
                                }elseif($f->status==6){
                                    $f->status="Reject";
                                }elseif($f->status==0){
                                    $f->status="Absent";
                                }elseif($f->status==1){
                                    $f->status="Receive";
                                }elseif($f->status==2){
                                    $f->status="Reschedule";
                                }elseif($f->status==4){
                                    $f->status="Complete";
                                }elseif($f->status==5){
                                    $f->status="Refer Doctor";
                                }
                                //unset($f->doc_id);
                          }
                         
                          $response = array("status" =>1, "msg" => "Past Appointment Get Successfully","data"=>$past);
                     }else{
                         $response = array("status" =>0, "msg" => "Past Appointment Not Found","data"=>array());
                     }
                   
           }
           return Response::json($response);
   }
   public function getuserupconmingappointment(Request $request){
      $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'user_id' => 'required'          
                    ];                    
            $messages = array(
                      'user_id.required' => "user_id is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {               
                     $future=Appointment::where("user_id",$request->get("user_id"))->select("doc_id","user_id","department_id","id","name","date","time","phone_no","status",'messages','service_id')->where("date",">",date('Y-m-d'))->paginate(10);


                      $data=array();
                     if($future){
                          foreach ($future as $f) {
                                $getdata=Doctor::where("user_id",$f->doc_id)->first();
                                if($getdata){
                                     $f->doctor_name=$getdata->name;
                                     $departmentdata=Department::find($getdata->department_id);
                                     $f->department_name=$departmentdata->name;
                                     $f->image=asset('public/upload/doctor').'/'.$getdata->image;
                                }else{
                                    $f->doctor_name="";
                                    $f->department_name="";
                                     $f->image="";
                                }
                               
                                 $getserivce=Service::find($f->service_id);
                                  
                                    if($getserivce){
                                        $f->service_name=$getserivce->name;
                                    }else{
                                        $f->service_name="";
                                    }
                                $f->name=$f->name;
                                $f->id=$f->id;
                                $date=date_create($f->date);
                                $f->date=date_format($date,"d,F Y");
                                $f->time=$f->time;
                                //$f->time=date_format($time,"H:i A");
                                
                                if($f->status==3){
                                    $f->status="Approve";
                                }elseif($f->status==6){
                                    $f->status="Reject";
                                }elseif($f->status==0){
                                    $f->status="Absent";
                                }elseif($f->status==1){
                                    $f->status="Receive";
                                }elseif($f->status==2){
                                    $f->status="Reschedule";
                                }elseif($f->status==4){
                                    $f->status="Complete";
                                }elseif($f->status==5){
                                    $f->status="Refer Doctor";
                                }
                                unset($f->doc_id);
                          }
                         
                          $response = array("status" =>1, "msg" => "Upcoming Appointment Get Successfully","data"=>$future);
                     }else{
                         $response = array("status" =>0, "msg" => "Upcoming Appointment Not Found","data"=>array());
                     }
                   
           }
           return Response::json($response);
   }

   public function gettokendata(Request $request){
        $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'email' => 'required'          
                    ];                    
            $messages = array(
                      'email.required' => "email is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {               
                      $user=User::where("email",$request->get("email"))->first();
                      if($user){
                          $identity = $user->email;
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
                             
                          $response = array("status" =>1, "msg" => "Token Get Successfully","data"=>$token->toJWT());
                     }else{
                         $response = array("status" =>0, "msg" => "User Not Found","data"=>array());
                     }
                   
           }
           return Response::json($response);
   }

   public function chatuploadmedia(Request $request){
      // dd($request->all());
       $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'file' => 'required'
                    ];                    
            $messages = array(
                      'file.required' => "file is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {  
               
                          $img_url="";
                          $type="";       
                          // echo "<pre>";print_r($_FILES);exit;
                            if($request->file("file")){
                                
                                 $file = $request->file('file');
                                 $filename = $file->getClientOriginalName();
                                 $extension = $file->getClientOriginalExtension() ?: 'mp4';
                                 $folderName = '/upload/chat';
                                 $picture = time() . '.' . $extension;
                                 $destinationPath = public_path() . $folderName;
                                 $request->file('file')->move($destinationPath, $picture);
                                 $img_url =$picture;
                                
                                 $response = array("status" =>1, "msg" => "Media Upload Successfully","data"=>$img_url);
                                  return Response::json($response);
                             }else{
                               $response = array("status" =>0, "msg" => "Media Not Upload","data"=>array());
                                return Response::json($response);
                            }
           }
           return Response::json($response);
   }
   
   public function forgotpassword(Request $request){
       $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'email' => 'required'          
                    ];                    
            $messages = array(
                      'email.required' => "email is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {  
                     $checkmobile=User::where("email",$request->get("email"))->first();
                     if(!empty($checkmobile)){
                         $code=mt_rand(100000, 999999);
                         $store=array();
                         if(!isset($checkmobile->email)){
                              $response = array("status" =>0, "msg" => "Email Not Found");
                         }
                         $store['email']=$checkmobile->email;
                         $store['name']=$checkmobile->name;
                         $store['code']=$code;
                         $add=new Resetpassword();
                         $add->user_id=$checkmobile->id;
                         $add->code=$code;
                         $add->save();
                          try {
                                 Mail::send('email.forgotpassword', ['user' => $store], function($message) use ($store){
                                  $message->to($store['email'],$store['name'])->subject(__("messages.Forgot Password"));
                                });
                                $response = array("status" =>0, "msg" => "Mail Send Sucessfully");
                          } catch (\Exception $e) {
                              $response = array("status" =>0, "msg" => "Mail Not Send Sucessfully");
                          }
                        
                         
                     }
                     else{
                         $response = array("status" =>0, "msg" => "Email Not Found");
                     }
           }
           return Response::json($response);
   }
   
   public function healthpackage(){
       $data=Package::where("is_delete",'0')->get();
       foreach($data as $k){
           $dep=Department::find($k->department_id);
           $k->department_name=$dep->name;
       }
       $response = array("status" =>1, "msg" => "Health Package Get Sucessfully","data"=>$data);
       return Response::json($response);
       
   }
   
   public function mysubscription(Request $request){
        $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'user_id' => 'required'          
                    ];                    
            $messages = array(
                      'user_id.required' => "user_id is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {               
                     $future=Subscription::with('packagedata')->where("user_id",$request->get("user_id"))->orderby("id","DESC")->paginate(10);
                     if(count($future)>0){
                         $mainarray=array();
                         foreach($future as $f){
                               $ls=array();
                               $ls['date']=$f->date;
                               $ls['time']=$f->time;
                               $ls['amount']=$f->amount;
                               
                               if($f->packagedata){
                                    $ls['name']=$f->packagedata->name;
                               }else{
                                   $ls['name']="";
                               }
                             //  1=>receive,2=>accept,3=>cancel,4=>inprogress,5=>complete,6=>refund 
                               if($f->status==1){
                                   $ls['status']="Receive";
                               }elseif($f->status==2){
                                   $ls['status']="Accept";
                               }elseif($f->status==3){
                                   $ls['status']="Cancel";
                               }elseif($f->status==4){
                                   $ls['status']="In Progress";
                               }elseif($f->status==5){
                                   $ls['status']="Complete";
                               }else{
                                  $ls['status']="Refund"; 
                               }
                               
                               $main_array[]=$ls;
                                        
                         }
                          $response = array("status" =>1, "msg" => "Subscription Get Successfully","data"=>$main_array);
                     }else{
                         $response = array("status" =>0, "msg" => "Subscription Not Found");
                     }
                   
           }
           return Response::json($response);
   }
   
   public function addsubscription(Request $request){
        $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'user_id' => 'required',
                      'name'=>'required',
                      'package_id'=>'required',
                      'transaction_id'=>'required',
                      'date'=>'required',
                      'time'=>'required',
                      'payment_type'=>'required',
                      'amount'=>'required'
                    ];                    
            $messages = array(
                      'user_id.required' => "user_id is required",
                      'name.required' => "name is required",
                      'package_id.required' => "package_id is required",
                      'transaction_id.required' => "transaction_id is required",
                      'date.required' => "date is required",
                      'time.required' => "time is required",
                      'payment_type.required' => "payment_type is required",
                      'amount.required' => "amount is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {      
                      if($request->get("payment_type")==2){
                          $payment=Paymentgateway::find(2);
                          $subscription=Package::find($request->get('package_id'));
                          $gateway = new \Braintree\Gateway([
                                          'environment' => $payment->mode,
                                          'merchantId' => $payment->merchant_key,
                                          'publicKey' => $payment->key,
                                          'privateKey' => $payment->secert
                                     ]);
                            $amount = $subscription->price;
                            $nonce = $request->get("transaction_id");
                    
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
                                          $store->name=trim($request->get("name"));
                                          $store->user_id=$request->get("user_id");
                                          $store->package_id=$request->get('package_id');
                                          $store->transaction_id=$transaction->id;
                                          $store->date=$request->get('date');
                                          $store->time=$request->get('time');
                                          $store->payment_type='2';
                                          $store->amount=$subscription->price;
                                          $store->save(); 
                                          $response = array("status" =>1, "msg" => "Subscription Add Successfully","data"=>$store);
                            } else {
                                $errorString = "";
                    
                                foreach($result->errors->deepAll() as $error) {
                                    $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
                                }
                                $response = array("status" =>0, "msg" => $errorString);
                            }
                      }else{
                              $store=new Subscription();  
                              $store->name=$request->get("name");
                              $store->user_id=$request->get("user_id");
                              $store->package_id=$request->get("package_id");
                              $store->transaction_id=$request->get("transaction_id");
                              $store->date=$request->get("date");
                              $store->time=$request->get("time");
                              $store->payment_type=$request->get("payment_type");
                              $store->amount=$request->get("amount");
                              $store->save();
                              $response = array("status" =>1, "msg" => "Subscription Add Successfully","data"=>$store);
                      }
                     
                    
           }
           return Response::json($response);
   }
   
   
   public function editprofile(Request $request){
        $response = array("status" => 0, "msg" => "Validation error");
           $rules = [
                      'name' => 'required',
                      'phone'=>'required', 
                      'user_id'=>'required',
                      'password'=>'required',
                      'email'=>'required'
                    ];                    
            $messages = array(
                      'name.required' => "name is required",  
                      'phone.required'=>"phone is required",
                      'user_id.required'=>"user_id is required",
                      'password.required'=>"password is required",
                      "email.required"=>"email is required"
            );

           
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $message = '';
                $messages_l = json_decode(json_encode($validator->messages()), true);
                foreach ($messages_l as $msg) {
                    $message .= $msg[0] . ", ";
                }
                $response['msg'] = $message;
            }  else {
                $img_url="";
                $user =User::find($request->get("user_id")); 
                if($user){
                            $img_url=$user->profile_pic;
                            $real_img=$user->profile_pic;
                            if ($request->hasFile('image')) 
                              {
                                 $file = $request->file('image');
                                 $filename = $file->getClientOriginalName();
                                 $extension = $file->getClientOriginalExtension() ?: 'png';
                                 $folderName = '/upload/profile';
                                 $picture = 'profile_'.mt_rand(100000,999999). '.' . $extension;
                                 $destinationPath = public_path() . $folderName;
                                 $request->file('image')->move($destinationPath, $picture);
                                 $img_url = $picture;
                                  $image_path = public_path() ."/upload/profile/".$real_img;
                                    if(file_exists($image_path)) {
                                        try {
                                             unlink($image_path);
                                        }
                                        catch(Exception $e) {
                                          
                                        }                        
                                    }
                             }
                            $user->name=$request->get("name");
                            $user->phone_no=$request->get("phone");
                            $user->profile_pic=$img_url;  
                            $user->password=$request->get("password");
                            $user->save();
                             if($user->user_sid==""){
                              try{
                                  $setting=Setting::find(1);
                                  $sid    = $setting->TWILIO_ACCOUNT_SID;
                                  $token  = $setting->TWILIO_AUTH_TOKEN;
                                  $twilio = new Client($sid, $token);
                                  $userdata = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
                                           ->users
                                           ->create($user->email);
                                  $user->user_sid=$userdata->sid;
                                  $user->save();
                              }catch(Exception $e){

                              }
                             }
                             if($user->user_sid){
                                 if($img_url!=""){
                                       $image="profile/".$img_url;
                                 }
                                 else{
                                       $image="profile/profile.png";
                                 }
                                  $setting=Setting::find(1);
                                  $sid    = $setting->TWILIO_ACCOUNT_SID;
                                  $token  = $setting->TWILIO_AUTH_TOKEN;
                                  try{
                                        $twilio = new Client($sid, $token);
                                        $array=array("id"=>$user->id,"name"=>$request->get("name"),"profile"=>$image);
                                        $checkdata = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
                                             ->users($user->user_sid)
                                             ->update([
                                                          "attributes" => json_encode($array)
                                                      ]
                                             );
                                  }catch(Exception $e){

                                  }
                             }
                            $user->profile_pic=asset('public/upload/profile').'/'.$user->profile_pic;
                            $user->new_password = $request->get("password");
                            $response = array("status" =>1, "msg" => "Profile Update Successfully","data"=>$user);
                 
                }
                 else{
                  $response = array("status" =>0, "msg" => "Something wrong");
                 }                
           }
          return Response::json($response);
   }
   
   public function topdoctor(){
       $data=array();
       $data=DB::table('doctors as u')
                ->join('review as r','r.doctor_id', '=', 'u.user_id')
                ->select(DB::raw('AVG(r.ratting) as average_rating'),'u.user_id')
                ->groupBy('r.doctor_id')
                ->orderby('average_rating','DESC')
                ->take(10)->get();
                $arr=array();
                $doctols=Doctor::select('id','user_id','name','image','department_id','about_us')->get();
                foreach($data as $da){
                     foreach($doctols as $d){
                        if($d->user_id==$da->user_id){
                           
                            $dep=Department::find($d->department_id);
                             $d->image=asset('public/upload/doctor').'/'.$d->image;
                            $d->department_name=$dep->name;
                             unset($d->department_id);
                             $d->id=$d->user_id;
                             unset($d->user_id);
                            $arr[]=$d;
                            break;
                        }
                        
                    }
                }
       
       $response = array("status" =>1, "msg" => "Doctor List Successfully","data"=>$arr);
       return Response::json($response);
   }
   
   public function getdoctorandservicebydeptid(Request $request){
         $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'department_id' => 'required'          
                    ];                    
            $messages = array(
                      'department_id.required' => "department_id is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {      
                     
                     $doctor=Doctor::select("user_id","name")->where("department_id",$request->get("department_id"))->get();
                     $service=DepartService::select("id","name")->where("department_id",$request->get("department_id"))->get();
                     if($doctor){
                          $data=array("doctor"=>$doctor,"services"=>$service);
                          $response = array("status" =>1, "msg" => "Doctor And Service List Get Sucessfully","data"=>$data);
                     }else{
                         $response = array("status" =>0, "msg" => "Data Not Found","data"=>array());
                     }
                   
           }
           return Response::json($response);
   }
   
   public function contactus(Request $request){
         $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'name' => 'required',
                      'email' => 'required',
                      'phone' => 'required',
                      'topic' => 'required',
                      'message' => 'required'
                    ];                    
            $messages = array(
                      'name.required' => "name is required",
                      'email.required' => "email is required",
                      'phone.required' => "phone is required",
                      'topic.required' => "topic is required",
                      'message.required' => "message is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {      
                       
                          $store=new ContactUs();
                          $store->name=$request->get("name");
                          $store->email=$request->get("email");
                          $store->phone=$request->get("phone");
                          $store->topic=$request->get("topic");
                          $store->message=$request->get("message");
                          $store->save();
                          
                          $response = array("status" =>1, "msg" => "Your concern register successfully, we will get back to you shortly.","data"=>$store);
                   
           }
           return Response::json($response);
   }
   
   public function doctordetails(Request $request){
          $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'id' => 'required'
                    ];                    
            $messages = array(
                      'id.required' => "id is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {      
                       
                          $store=Doctor::with('TimeTabledata')->where("user_id",$request->get("id"))->first();
                          if($store){
                              $dep=Department::find($store->department_id);
                              if($dep){
                                  $store->department_name=$dep->name;
                              }else{
                                  $store->department_name='';
                              }
                            
                              $store->image=asset('public/upload/doctor').'/'.$store->image;
                              $dt = Review::where("doctor_id",$request->get("id"))->avg('ratting');
                              $store->ratting = isset($dt)?$dt:0;
                              $response = array("status" =>1, "msg" => "Doctor Get  Sucessfully","data"=>$store);
                          }
                          else{
                              $response = array("status" =>0, "msg" => "Data Not Found");
                          }
                   
           }
           return Response::json($response);
   }
   
   public function reviewlistbydoctor(Request $request){
         $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'id' => 'required'
                    ];                    
            $messages = array(
                      'id.required' => "id is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {      
                       
                          $store=Review::with('users')->where("doctor_id",$request->get("id"))->get();
                          if(count($store)>0){
                              $main=array();
                              foreach($store as $s){
                                  $ls=array();
                                  $ls['name']=isset($s->users)?$s->users->name:"";
                                  $ls['profile_pic']=isset($s->users)?asset('public/upload/profile').'/'.$s->users->profile_pic:"";
                                  $ls['user_id']=isset($s->user_id)?$s->user_id:"";
                                  $ls['ratting']=isset($s->ratting)?$s->ratting:"";
                                  $ls['review']=isset($s->review)?$s->review:"";
                                  $main[]=$ls;
                              }
                              //$store->image=asset('public/upload/profile').'/'.$store->users->profilepic;
                              $response = array("status" =>1, "msg" => "Review Get  Sucessfully","data"=>$main);
                          }
                          else{
                              $response = array("status" =>0, "msg" => "Review Not Found");
                          }
                   
           }
           return Response::json($response);
   }
   
   public function getdepartment(){
         $data=Department::select('id','name')->get();
          if($data){
               
              $response = array("status" =>1, "msg" => "Department List Get Successfully","data"=>$data);
          }else{
              $response = array("status" =>0, "msg" => "Data Not Found","data"=>$data);
          }
          return Response::json($response);
   }
   
   public function searchdoctor(Request $request){
         $response = array("status" => 0, "msg" => "Validation error");
            $rules = [
                      'term' => 'required'          
                    ];                    
            $messages = array(
                      'term.required' => "term is required"
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                  $message = '';
                  $messages_l = json_decode(json_encode($validator->messages()), true);
                  foreach ($messages_l as $msg) {
                         $message .= $msg[0] . ", ";
                  }
                  $response['msg'] = $message;
            } else {               
                     $doctols=Doctor::select('id','user_id','name','image','department_id','about_us')->Where('name', 'like', '%' . $request->get("term") . '%')->paginate(10);
                      if(count($doctols)>0){
                            foreach($doctols as $d){
                                $dep=Department::find($d->department_id);
                                 $d->image=asset('public/upload/doctor').'/'.$d->image;
                                $d->department_name=$dep->name;
                                 $d->id=$d->user_id;
                            }
                    
                      $response = array("status" =>1, "msg" => "Subscription Get Successfully","data"=>$doctols);
                      }else{
                         $response = array("status" =>0, "msg" => "Subscription Not Found");
                     }
                   
           }
           return Response::json($response);
   }
}
?>