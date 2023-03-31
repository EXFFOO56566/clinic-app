<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twilio\Exceptions\RestException;

use App\Events\FormSubmitted;
	use App\Events\ActionChannel;
	use App\Events\videoChannel;
	use Auth;
	use Session;
	use validate;
	use App\User;
	use App\Model\Channel;
	use App\Model\Doctor;
	use App\Model\Token;
	use App\Model\Department;
	use Twilio\Jwt\AccessToken;
	use Twilio\Jwt\Grants\VideoGrant;
	use Twilio\Jwt\Grants\SyncGrant;
	use Twilio\Jwt\Grants\ChatGrant;
	use App\Model\Setting;
	use Twilio\Rest\Client;
	use Config;
	use DateTime;
	use DateTimeZone;
	class ChatsController extends Controller
	{

		public function showchat($id){			
			 $department=Department::all();
			 if(Auth::id()&&Auth::user()->usertype=='3'){
				 $doctor=User::with('doctors')->where("usertype",'1')->get();      
			 }else if(Auth::id()&&Auth::user()->usertype=='1'){
				 $doctor=User::with('doctors')->where("usertype",'3')->get();      
			 }else if(Auth::id()&&Auth::user()->usertype=='2'){
				 $doctor=User::with('doctors')->where("usertype",'!=','2')->get();
			 }else{
			 	return redirect('/');
			 }
			 $getmessage="";
			 $createchannel="";
			 $channel_member=array();

			 if($id!=0){
			     $createchannel=$this->createchannel($id);
			 } 
			$channel_member=$this->getchannelmember();
		
             if($id!=0){
                 $getmessage=$this->getchannelmessage("",$id);
             }
             $setting = Setting::find(1);
			 if(Auth::user()->usertype=='1'){
					 return view("front.chat")->with("department",$department)->with("doctor",$doctor)->with("channel_list",$channel_member)->with("id",$id)->with("currentuserinfo",$createchannel)->with("setting",$setting)->with("getmessage",$getmessage);
			 }else if(Auth::user()->usertype=='3'){
					 return view("doctor.chat")->with("department",$department)->with("doctor",$doctor)->with("channel_list",$channel_member)->with("id",$id)->with("currentuserinfo",$createchannel)->with("getmessage",$getmessage);
			 }else{
			 	 return view("admin.chat")->with("department",$department)->with("doctor",$doctor)->with("channel_list",$channel_member)->with("id",$id)->with("currentuserinfo",$createchannel)->with("getmessage",$getmessage);
			 }
		}

		public function getchannelmember(){
			 $arr=array();
			 $setting=Setting::find(1);
			 $sid    = $setting->TWILIO_ACCOUNT_SID;
			 $token  = $setting->TWILIO_AUTH_TOKEN;
			 $twilio = new Client($sid, $token);
			 $userChannels = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
								   ->users(Auth::user()->user_sid)
									 ->userChannels
									 ->read(50);
									 
			if(count($userChannels)>0){
				foreach ($userChannels as $k) {
					 $members = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
									->channels($k->channelSid)
									->members
									->read([], 5);
					 $channel = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
								->channels($k->channelSid)
								->fetch();

					 foreach ($members as $m) {
						  if($m->identity!=Auth::user()->email){
								$d=array();
								$usersdata=User::with('doctors')->where("email",$m->identity)->first();
								if($usersdata&&$usersdata->user_sid!=""){
								    try{
								        $user = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
										 ->users($usersdata->user_sid)
										 ->fetch();
									$d['id']=$usersdata->id;
									$d['identity']=$m->identity;
									$d['channel_sid']=$channel->sid;
									$d['channel_uniquename']=$channel->uniqueName;
									$d['name']=$usersdata->name;
									$d['time']="";
									if(isset($usersdata)&&$usersdata->profile_pic!=""){
										 $d['image']=asset("public/upload/profile").'/'.$usersdata->profile_pic;
									}else if(isset($usersdata)&&isset($usersdata->doctors->image)&&$usersdata->doctors->image!=""){
										 $d['image']=asset("public/upload/doctor").'/'.$usersdata->doctors->image;
									}else{
										 $d['image']=asset("public/upload/profile/profile.png");
									}
									if($channel->links['last_message']){
										 $a=explode('/', $channel->links['last_message']);
										 $message = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
																->channels($k->channelSid)
																->messages($a[count($a)-1])
																->fetch();
																
																$arr1=json_decode($message->attributes,true);
																
																if(isset($arr1['type'])&&$arr1['type']=="image"){
																	$d['last_messages']="image";
																}elseif(isset($arr1['type'])&&$arr1['type']=="video"){
																	$d['last_messages']="video";
																}else{
																	$d['last_messages']=$message->body;
																}
																
																if(isset($arr1['date'])&&$arr1['date']!=""){
																	$timezone_offset_minutes = Session::get("timezone")?Session::get("timezone"):330;
			                                                        $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes*60, false);
																	$myDateTime = new DateTime($arr1['date'], new DateTimeZone('UTC'));
																	$myDateTime->setTimezone(new DateTimeZone($timezone_name));
																	$date=$myDateTime->format('Y-m-d');
																	$time=$myDateTime->format('H:i A');
																	$list=$this->timeformat($date,$time);
																	if($list['flag']=="Today"){
																		$d['time']=$time;
																	}elseif($list['flag']=="Yerterday"){
																		$d['time']=$time;
																	}else{
																		$d['time']=$list['flag'];
																	}
																	
																}
																
									}else{
										$d['last_messages']=$channel->links['last_message'];
									}
									 
									 if($user->isOnline=='1'){
										$d['status']="online";
									 }else{
										$d['status']="offline";
									 }
									
									
									$arr[]=$d;
									break;
								    }catch(RestException $exception){
								        
								    }
									
								}                            
						  }
						 
					 }
				}   
			}
			return $arr;
		}

		public function searchchannel($first_user_sid,$second_user_sid){
			 $setting=Setting::find(1);
			 $chls=array();
			 $chls1=array();
			 $sid    = $setting->TWILIO_ACCOUNT_SID;
			 $token  = $setting->TWILIO_AUTH_TOKEN;
			 $twilio = new Client($sid, $token);
			 $userChannels1 = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
								   ->users($first_user_sid)
									 ->userChannels
									 ->read(100);
			 foreach ($userChannels1 as $k) {
				  $chls[]=$k->channelSid;
			 }
			 $userChannels2 = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
								   ->users($second_user_sid)
									 ->userChannels
									 ->read(100);
			  foreach ($userChannels2 as $k) {
				  $chls1[]=$k->channelSid;
			 }

            
			 if(array_intersect($chls,$chls1)){
				$arr=array_values(array_intersect($chls,$chls1));
				return isset($arr[0])?$arr[0]:0;
			 }else{
				return 0;
			 }
		}


		public function createchannel($id){
						
						$getUser=User::find($id);						
						$setting=Setting::find(1);
						$sid    = $setting->TWILIO_ACCOUNT_SID;
						$token  = $setting->TWILIO_AUTH_TOKEN;
						$twilio = new Client($sid, $token);
						$searchchannel=$this->searchchannel(Auth::user()->user_sid,$getUser->user_sid);
						
						if($searchchannel=="0"){
							
							$channel_id=$this->random_strings(10);
							$user = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
								->channels
								->create();  
								 $array=array("body"=>"","date"=>"","type"=>"");    
							$channel = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
								->channels($user->sid)
								->update(["uniqueName"=>$channel_id,"type"=>"private","friendlyName"=>$channel_id,"createdBy"=>Auth::user()->email,"attributes" => json_encode($array)]);  
							$userChannels = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
										  ->channels($user->sid)
										  ->members
										  ->create(Auth::user()->email);
							$userChannels = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
										  ->channels($user->sid)
										  ->members
										  ->create($getUser->email); 
						}else{
							
							$channel = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
								->channels($searchchannel)
								->fetch();
							$channel_id=$channel->uniqueName;
						}
						$user = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
										 ->users($getUser->user_sid)
										 ->fetch();
						$arr['id']=$id;
						   $arr['name']=$getUser->name;
						   if($getUser->profile_pic!=""){
								$arr['image']=asset('public/upload/profile')."/".$getUser->profile_pic;
						   }elseif(isset($getUser->doctors)&&isset($getUser->doctors->image)){
								$arr['image']=asset('public/upload/doctor')."/".$getUser->doctors->image;
						   }else{
								$arr['image']=asset('public/upload/profile/profile.png');
						   }
						  if($user->isOnline=='1'){
										$arr['status']="online";
									 }else{
										$arr['status']="offline";
									 }
						   $arr['channel']=$channel_id;
						   $arr['channel_sid']=$channel->sid;
						   $str=explode("@",$user->identity);
						   $arr['identity']=isset($str[0])?$str[0]:"";
						return $arr;

		}

		 function random_strings($length_of_string) 
		 { 
			  
			$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
			return substr(str_shuffle($str_result),0, $length_of_string); 
		} 
		
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
					$chatGrant->setpushCredentialSid("CR63164780d6b501e77226ee99d3e4f128");
					$token->addGrant($chatGrant);
				}
				header('Content-type:application/json;charset=utf-8');
				echo json_encode(array(
					'identity' => $identity,
					'token' => $token->toJWT(),
				));
		 }

		   public function getchannelmessage($channel_id,$second_id){
			$type=$channel_id;
			$setting=Setting::find(1);
			
			$user=User::with("doctors")->where("id",$second_id)->first();
			if($channel_id==""){
				$type=$this->searchchannel($user->user_sid,Auth::user()->user_sid);
			}
			if(isset($user)&&$user->profile_pic!=""){
				$simg=asset("public/upload/profile").'/'.$user->profile_pic;
			}else if(isset($user->doctors)&&$user->doctors->image!=""){
				$simg=asset("public/upload/doctor").'/'.$user->doctors->image;
			}else{
				$simg=asset("public/upload/profile/profile.png");
			}
			$sid    = $setting->TWILIO_ACCOUNT_SID;
						$token  = $setting->TWILIO_AUTH_TOKEN;
						$twilio = new Client($sid, $token);
			$arr = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
								->channels($type)
								->messages
                                ->read([], 1000);

			
			 $txt="";
			 $i=0;
			 $timezone_offset_minutes = Session::get("timezone")?Session::get("timezone"):330;
			 $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes*60, false);
			 $last_date="";
			 foreach($arr as $a){
				$arr=json_decode($a->attributes,true);
				  if(isset($arr)&&isset($arr['date'])){
						$date=$arr['date'];
						$myDateTime = new DateTime($date, new DateTimeZone('UTC'));
						$myDateTime->setTimezone(new DateTimeZone($timezone_name));
						$date=$myDateTime->format('Y-m-d');
						$time=$myDateTime->format('H:i A');
				  }
				  else{
				         if(isset($a->date_created)){
				              $date=$a->date_created;
    						 $myDateTime = new DateTime($date, new DateTimeZone('UTC'));
    						 $myDateTime->setTimezone(new DateTimeZone($timezone_name));
    						 $date=$myDateTime->format('Y-m-d');
    						 $time=$myDateTime->format('H:i A');
				         }
						
				  } 
				


			  
				   $datetime=$date;
				   $datemsg=$time;
				   if($datetime!=$last_date){
					   $list=$this->timeformat($datetime,$datetime);
					   $txt=$txt.'<div class="message-divider sticky-top pb-2" data-label="'.$list['flag'].'">&nbsp;</div>';
					   $last_date=$datetime;
				   }	
				  				   
				if($a->from==Auth::user()->email){
					if(isset($arr)&&isset($arr['type'])&& $arr['type']=="image"){

						 $path=asset('public/upload/chat').'/'.$a->body;
						 $txt=$txt.'<div class="message self" id="newmsg'.$i.'"><div class="message-wrapper"><div class="message-content imgmsg"><img src="'.$path.'"  style="width:250px;height:250px"/></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="'.Session::get("profile_pic").'"></div><span class="message-date">'.$datemsg.'</span> <div class="dropdown"><a class="text-muted" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg class="hw-18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg></a><div class="dropdown-menu"><a class="dropdown-item d-flex align-items-center text-danger" href="javascript:deletechatmsg(\''.$a->sid.'\',\''.$a->to.'\',\''.$arr['type'].'\',\''.$i.'\')"><svg class="hw-18 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg><span>delete</span></a></div></div></div></div>';
						
					}
					else if(isset($arr)&&isset($arr['type'])&& $arr['type']=="video"){
						  $path=asset('public/upload/chat').'/'.$a->body;
						  $txt=$txt.'<div class="message self" id="newmsg'.$i.'"><div class="message-wrapper"><div class="message-content imgmsg"><video width="320" height="240" controls><source  src="'.$path.'" type="video/mp4"></video></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="'.Session::get("profile_pic").'"></div><span class="message-date">'.$datemsg.'</span><div class="dropdown"><a class="text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg class="hw-18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg></a><div class="dropdown-menu"><a class="dropdown-item d-flex align-items-center text-danger" href="javascript:deletechatmsg(\''.$a->sid.'\',\''.$a->to.'\',\''.$arr['type'].'\',\''.$i.'\')"><svg class="hw-18 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg><span>delete</span></a></div></div></div></div>';
						 
					}else{
						$txt=$txt.'<div class="message self" id="newmsg'.$i.'"><div class="message-wrapper"><div class="message-content"><span id="body'.$i.'">'.$a->body.'</span></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="'.Session::get("profile_pic").'"></div><span class="message-date">'.$datemsg.'</span><div class="dropdown"><a class="text-muted" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg class="hw-18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg></a><div class="dropdown-menu"><a class="dropdown-item d-flex align-items-center" href="javascript:updatechatmsg(\''.$a->sid.'\',\''.$a->to.'\',\''.$i.'\')"><svg class="hw-18 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg><span>Edit</span></a><a class="dropdown-item d-flex align-items-center text-danger" href="javascript:deletechatmsg(\''.$a->sid.'\',\''.$a->to.'\',\''.$arr['type'].'\',\''.$i.'\')"><svg class="hw-18 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg><span>delete</span></a></div></div></div></div>';
						
					}
					
				}else{
					$str=explode("#",$a->body);
					if(isset($arr)&&isset($arr['type'])&& $arr['type']=="image"){

						 $path=asset('public/upload/chat').'/'.$a->body;
						 $txt=$txt.'<div class="message" id="newmsg'.$i.'"><div class="message-wrapper"><div class="message-content imgmsg"><img src="'.$path.'"  style="width:250px;height:250px"/></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="'.$simg.'"></div><span class="message-date">'.$datemsg.'</span></div></div></div>';
						
					}
					else if(isset($arr)&&isset($arr['type'])&& $arr['type']=="video"){
						  $path=asset('public/upload/chat').'/'.$a->body;
						  $txt=$txt.'<div class="message" id="newmsg'.$i.'"><div class="message-wrapper"><div class="message-content imgmsg"><video width="320" height="240" controls><source  src="'.$path.'" type="video/mp4"></video></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="'.$simg.'"></div><span class="message-date">'.$datemsg.'</span></div></div>';
						  

					}else{
						$txt=$txt.'<div class="message" id="newmsg'.$i.'"><div class="message-wrapper"><div class="message-content"><span id="body'.$i.'">'.$a->body.'</span></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="'.$simg.'"></div><span class="message-date">'.$datemsg.'</span></div></div>';
						
					}
					
				}
				$i++;
			 } 
			 if($last_date!=date('Y-m-d')){
				 $txt=$txt.'<div class="message-divider sticky-top pb-2" data-label="Today">&nbsp;</div>';
			 }
			return $txt=$txt.' <input type="hidden" name="messagescount" id="messagescount" value="'.$i.'">';
		 }



		 public function chatmediaupload(Request $request){
			 $img_url="";
			 $type="";
			 if($request->file("file")){
						 $file = $request->file('file');
						 $filename = $file->getClientOriginalName();
						 $str=explode('/',$_FILES["file"]["type"]);
						 if(isset($str[0])&&$str[0]=="images"){
								  $extension = $file->getClientOriginalExtension() ?: 'png';
						 }else{
								  $extension = $file->getClientOriginalExtension() ?: 'mp4';
						 }
						 
						 $str1=explode('/', $_FILES["file"]["type"]);
						 $type=$str1[0];
						 $folderName = '/upload/chat';
						 $picture = 'doctor_'.mt_rand(100000,999999). '.' . $extension;
						 $destinationPath = public_path() . $folderName;
						 $request->file('file')->move($destinationPath, $picture);
						 $img_url = $picture;
			 }

			  return json_encode(array("type"=>$type,"image"=>$img_url));
		 }

		  public function sendnotification(Request $request){
			 $channel_id=$request->get("channelid");
			 $name=$request->get("name");
			 $message=$request->get("message");
			 $type=$request->get("type");
			 $msgdate=$request->get("date");
			 $setting=Setting::find(1);
			
			 if($type=='image'){
				$message="image";
			 }else if($type=='video'){
				$message="video";
			 }else{
				$message=$message;
			 }
             
             try{
             	    $array=array("body"=>$message,"date"=>$msgdate,"type"=>$type);
             		$setting=Setting::find(1);
					$sid    = $setting->TWILIO_ACCOUNT_SID;
					$token  = $setting->TWILIO_AUTH_TOKEN;
					$twilio = new Client($sid, $token);
             	    $channel = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
								->channels($channel_id)
								->update([
									        "attributes" => json_encode($array)
									    ]); 
             }catch(\Exception $e){

             }
			 $getreceiveruser=User::with('doctors')->where("id",Auth::id())->first();
			 $seconduser=User::find($request->get("currentid"));
			 if($getreceiveruser){
				if($getreceiveruser->profile_pic!=""){
					$image=asset('public/upload/profile')."/".$getreceiveruser->profile_pic;
			   }elseif(isset($getreceiveruser->doctors)&&isset($getreceiveruser->doctors->image)){
					$image=asset('public/upload/doctor')."/".$getreceiveruser->doctors->image;
			   }else{
					$image=asset('public/upload/profile/profile.png');
			   }
			   $timezone_offset_minutes = Session::get("timezone")?Session::get("timezone"):330;
			   $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes*60, false);
			   $date=date('Y-m-d H:i A');
			   $myDateTime = new DateTime($date, new DateTimeZone('UTC'));
						$myDateTime->setTimezone(new DateTimeZone($timezone_name));
						$date=$myDateTime->format('Y-m-d');
						$time=$myDateTime->format('H:i A');
				event(new FormSubmitted($getreceiveruser->name,$message,$seconduser->id,$image,Auth::id(),$time));
				
			 }
			
			 return $time;
		 }

		 public function videosendnotification(Request $request){
		 	 $channel_id=$request->get("channelid");
		 	 $action=$request->get("action");
		 	 $image="";
		 	  $getreceiveruser=User::with('doctors')->where("id",Auth::id())->first();
			 if($getreceiveruser){
				if($getreceiveruser->profile_pic!=""){
					$image=asset('public/upload/profile')."/".$getreceiveruser->profile_pic;
			   }elseif(isset($getreceiveruser->doctors)&&isset($getreceiveruser->doctors->image)){
					$image=asset('public/upload/doctor')."/".$getreceiveruser->doctors->image;
			   }else{
					$image=asset('public/upload/profile/profile.png');
			   }
			}
				event(new videoChannel($channel_id,$action,Auth::id(),Auth::user()->name,$image));
			
			
			 return "done";
		 }

		 public function deletechatmsg($msg_id,$channel_id,$type,$irow){
						$setting=Setting::find(1);
						$sid    = $setting->TWILIO_ACCOUNT_SID;
						$token  = $setting->TWILIO_AUTH_TOKEN;
						$twilio = new Client($sid, $token);
						$img_path="";
						  try {
							  if($type=="image"||$type="video"){
								  $userChannels = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
											 ->channels($channel_id)
											 ->messages($msg_id)
											 ->fetch();
									$img_path=asset("public/upload/chat").'/'.$userChannels->body;
							  }
							  $userChannels = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
											 ->channels($channel_id)
											 ->messages($msg_id)
											 ->delete();
							  if($img_path){
								 if(file_exists($img_path)) {
									  try {
										   unlink($img_path);
									  }
									  catch(Exception $e) {
										
									  }                        
								  }
							  }
						  }
						  catch(Exception $e) {
							
						  }
						  $channel = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
                            ->channels($channel_id)
                            ->fetch();
						event(new ActionChannel($channel->uniqueName,'','delete',$irow));
					   return 1;
		 }

		 public function updatechannelmsg(Request $request){
						$setting=Setting::find(1);
						$sid    = $setting->TWILIO_ACCOUNT_SID;
						$token  = $setting->TWILIO_AUTH_TOKEN;
						$twilio = new Client($sid, $token);
						  try {
							  $userChannels = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
											 ->channels($request->get("channel"))
											 ->messages($request->get("msg"))
											 ->update(["body" => $request->get("body")]);
						  }
						  catch(Exception $e) {
							
						  }
						 $channel = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
                            ->channels($request->get("channel"))
                            ->fetch();
						event(new ActionChannel($channel->uniqueName,$request->get("body"),'update',$request->get("irow")));
					   return 1;
		 }
		 
		 public function getmessagebody($msg_id,$channel_id){
						$setting=Setting::find(1);
						$sid    = $setting->TWILIO_ACCOUNT_SID;
						$token  = $setting->TWILIO_AUTH_TOKEN;
						$twilio = new Client($sid, $token);
						  try {
							  $userChannels = $twilio->chat->v2->services($setting->TWILIO_CHAT_SERVICE_SID)
											 ->channels($channel_id)
											 ->messages($msg_id)
											 ->fetch();
							    return $userChannels->body;
						  }
						  catch(Exception $e) {
							
						  }
		 }
		 
		 public function timeformat($date,$time){
			if($date==date('Y-m-d')){
				return array("flag"=>"Today","time"=>$time);
			}else if($date==date('Y-m-d',strtotime("-1 days"))){
				return array("flag"=>"Yerterday","time"=>$time);
			}else{
				return array("flag"=>$date,"time"=>$time);
			}
		 }
		 
		 public function timeformatfront(Request $request){
			 $datetime=$request->get("date");
			$date=date("Y-m-d",strtotime($datetime));
			$time=date("h:i A",strtotime($datetime));
			if($date==date('Y-m-d')){
				return array("flag"=>"Today","time"=>$time);
			}else if($date==date('Y-m-d',strtotime("-1 days"))){
				return array("flag"=>"Yerterday","time"=>$time);
			}else{
				return array("flag"=>$date,"time"=>$time);											
			}
		 }
	}


