<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Model\Department;
use App\Model\Doctor;
use App\Model\Setting;
use App\Model\Service;
use App\Model\Channel;
use App\Model\Appointment;
use Twilio\Rest\Client;
use App\Model\Review;
use App\Model\Token;
use App\Model\ContactUs;
use DateTime;
use DateTimeZone;
use Session;
class HomeController extends Controller
{
 
    public function showlogin(){
        $setting=Setting::find(1);
        Session::put("is_rtl",$setting->is_rtl);
        Session::put("is_demo",$setting->is_demo);
        return view("admin.login")->with("type",'2');
    }   

    public function showchat(){
        return view("admin.chat");
    }
	
	public function settimezone($timezone){
		Session::put("timezone",$timezone);
		return "done";
	}

  public function getcurrenttime(){
      $timezone_name = timezone_name_from_abbr("", 330*60, false);
      $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
      $date->setTimeZone(new DateTimeZone('UTC')); 
      $data=array("date"=>$date->format('Y-m-d H:i:s.u'),"timezone"=>$timezone_name);
      return json_encode($data);
  }

    public function doctorlogin(){
       return view("admin.login")->with("type",'3');
    }

    public function postlogin(Request $request){
        if($request->get("type")=='2'){
            $checkuser=User::where("email",$request->get("email"))->where("password",$request->get("password"))->where("usertype",'2')->first();
        }
        else{
            $checkuser=User::where("email",$request->get("email"))->where("password",$request->get("password"))->where("usertype",'3')->first();
        }
        
        if($checkuser){
             Auth::login($checkuser, true);
             if($request->get("rem_me")==1){
                    setcookie('email', $request->get("email"), time() + (86400 * 30), "/");
                    setcookie('password',$request->get("password"), time() + (86400 * 30), "/");
                    setcookie('rem_me',1, time() + (86400 * 30), "/");

               } 
                
              
                $doctor=Doctor::where("user_id",$checkuser->id)->first();
                
                
               if($request->get("type")=='2'){
                   Session::put("profile_pic",asset("public/upload/profile").'/'.$checkuser->profile_pic);
                   Session::put("profile_pic_path","/profile/".$checkuser->profile_pic);
                    return redirect("admin/dashboard");
               }else{
                 $doctor=Doctor::where("user_id",Auth::id())->first();

                 if($doctor){
                    if($doctor->image!=""){
                         Session::put("profile_pic",asset("public/upload/doctor").'/'.$doctor->image);
                    }else{
                         Session::put("profile_pic",asset("public/upload/profile/profile.png"));
                    }
                    Session::put("profile_pic",asset("public/upload/doctor").'/'.$doctor->image);
                         Session::put("profile_pic_path","/doctor/".$doctor->image);
                    return redirect("doctor/dashboard");
                 }               
               }
            
        }else{
            Session::flash('message',__('messages.Login credentials are wrong')); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }

    public function showdashboard(){
        $totaldepartment=count(Department::all());
        $totalservice=count(Service::all());
        $totaldoctor=count(Doctor::all());
        $leastappointment=Appointment::with('doctors','services','department')->orderBy('id','DESC')->take(10)->get();
        $leastreview=Review::with("doctors","users")->orderBy('id','DESC')->take(10)->get();
    	return view("admin.dashboard")->with("totaldepartment",$totaldepartment)->with("totalservice",$totalservice)->with("totaldoctor",$totaldoctor)->with("leastappointment",$leastappointment)->with("leastreview",$leastreview);
    }

    public function logout(){
        $user=User::find(Auth::id());
        $user->last_seen=date('Y-m-d h:i:s');
        $user->status="offline";
        $user->save();
    	Auth::logout();
    	return redirect("admin");
    }


     public function editprofile(){
        return view('admin.updateprofile');
    }

    public function updateprofile(Request $request){
        $getuser=User::where("email",$request->get("email"))->where("id",'!=',Auth::id())->get();
        if(!$getuser){
             Session::flash("message1",__('messages.Email Id Already Existe'));
             return redirect()->back();
        }
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
             }else{
                 $img_url = Auth::user()->profile_pic;
             }

             $user=Auth::user();
             $user->name=$request->get("name");
             $user->email=$request->get("email");
             $user->profile_pic=$img_url;
             $user->save();
             
             if(Auth::user()->usertype==2){
                Session::put("profile_pic",asset("public/upload/profile").'/'.$user->profile);
             }
             Session::flash("message",__('messages.Profile Update Successfully'));
             return redirect()->back();
    }

      public function changepassword(Request $request){
            return view("admin.changepassword");
      } 

     public  function check_password_same($pwd){    
             if ($pwd==Auth::user()->password)
             {
                $data=1;
             }
            else{
                $data=0; 
             }
           return json_encode($data);
    }
       
       
       public function updatepassword(Request $request){
          $user=Auth::user();
          $user->password=$request->get('npwd');
          $user->save();
          Session::flash("message",__('messages.Password Change Successfully'));
          return redirect()->back();
       }

       public function showcontactus(){
             $data=ContactUs::all();
             return view('admin.contactus')->with("data",$data);
       }
       
       public function showpatient(){
           $data=User::where("usertype",'1')->get();
           return view("admin.patient")->with("data",$data);
       }
       
       public function privacy(){
           return view("privacy_policy");
       }
}
