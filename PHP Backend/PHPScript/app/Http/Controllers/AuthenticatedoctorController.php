<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Model\Doctor;
use App\Model\Department;
use App\Model\Appointment;
use App\Model\TimeTable;
use App\Model\Review;
use App\Model\Setting;
use App\User;
use Session;
use validate;
class AuthenticatedoctorController extends Controller
{
  
   public function showdashboard(){
   	    $totalappointment=count(Appointment::with('doctors','services','department')->where('doc_id',Auth::id())->get());
        $completeappointment=count(Appointment::with('doctors','services','department')->where('doc_id',Auth::id())->where("status",4)->get());
        $pendingappointement=count(Appointment::with('doctors','services','department')->where('doc_id',Auth::id())->where("status","!=",4)->get());
        $totalreview=count(Review::with("doctors","users")->orderBy('id','DESC')->where("doctor_id",Auth::id())->get());
        $upcomingappoint=Appointment::with('doctors','services','department')->where('doc_id',Auth::id())->where("date",">=",date("Y-m-d"))->get();
    	return view("doctor.dashboard")->with("totalappointment",$totalappointment)->with("completeappointment",$completeappointment)->with("pendingappointement",$pendingappointement)->with("totalreview",$totalreview)->with("upcomingappoint",$upcomingappoint);
   }

   public function showlogout(){   	  
   	    Auth::logout();
   	    Session::forget("profile_pic");
   	    return redirect("doctor/");
   }
   
   
    public function show_product_review_user(){
        $user_arr = array();
        $doc_arr = array();
        $user_ids = User::where("usertype","1")->get();
        foreach($user_ids as $id){
            $user_arr[]=$id->id;
        }
        $data = Doctor::all();
        foreach($data as $id){
            $doc_arr[]=$id->id;
        }
        $review = array(
            "Great service ,quality products at affordable prices",
            "Superb product. Great design and works very well",
            "They have descent customer service. Overall, I rate them highly. I would use them again in the future",
            "I have had nothing but wonderful service from them. I would highly recommend them and I would hire them again.",
            "Looks good and seems durable",
            "it was a good value for the money!"
        );
        $str = array(5,4);

        foreach($doc_arr as $g){
            $random_keys=array_rand($user_arr,4);
            for($i=0;$i<4;$i++){
                $random_review = array_rand($review,1);
                $random_star = array_rand($str,1);
               // echo "<pre>";print_r($review[$random_review]);exit;
                $store=new Review();
                $store->doctor_id=$g;
                $store->user_id=$user_arr[$random_keys[$i]];
                $store->review=$review[$random_review];
                $store->ratting=$str[$random_star];
                $store->save();
            }
        }
        
        echo "done";

    }


   public function showchat(){
        return view("doctor.chat");
    }

   public function editprofile($tab_id){
        $department=Department::all();
        $data=Doctor::where("user_id",Auth::id())->first();
        $workinghour=TimeTable::where("doctor_id",$data->id)->get();
        return view("doctor.savedoctor")->with("doctor_id",$data->id)->with("department",$department)->with("data",$data)->with("tab_id",$tab_id)->with("workinghour",$workinghour);
   }

   public function updatedoctorprofile(Request $request){
            $setting=Setting::find(1);           
            if($request->get("id")!="0"){
                $request->validate([
                    'department' => 'required',
                    'name' => 'required',
                    'password'=>'required',
                    'phone_no'=>'required',
                    'aboutus'=>'required',
                    'service'=>'required'
                ]);
              }else{
                $request->validate([
                    'department' => 'required',
                    'name' => 'required',
                    'email' => 'required|unique:users',
                    'password'=>'required',
                    'phone_no'=>'required',
                    'aboutus'=>'required',
                    'service'=>'required',
                    'image'=>'required'
                ]);
             }

             $img_url="";
            if($request->get("real_image")!=""){
                 if ($request->hasFile('image')) 
                  {
                     $file = $request->file('image');
                     $filename = $file->getClientOriginalName();
                     $extension = $file->getClientOriginalExtension() ?: 'png';
                     $folderName = '/upload/doctor';
                     $picture = 'doctor_'.mt_rand(100000,999999). '.' . $extension;
                     $destinationPath = public_path() . $folderName;
                     $request->file('image')->move($destinationPath, $picture);
                     $img_url = $picture;
                     $image_path = public_path() ."/upload/doctor/".$request->get("real_image");
                        if(file_exists($image_path)) {
                            try {
                                 unlink($image_path);
                            }
                            catch(Exception $e) {
                              
                            }                        
                        }
                 }else{
                     $img_url = $request->get("real_image");
                 }
            }else{
                if ($request->hasFile('image')) 
                  {
                     $file = $request->file('image');
                     $filename = $file->getClientOriginalName();
                     $extension = $file->getClientOriginalExtension() ?: 'png';
                     $folderName = '/upload/doctor';
                     $picture = 'doctor_'.mt_rand(100000,999999). '.' . $extension;
                     $destinationPath = public_path() . $folderName;
                     $request->file('image')->move($destinationPath, $picture);
                     $img_url = $picture;
                 }
            }
            if($request->get("id")!="0"){
               
                $store=Doctor::find($request->get("id"));
                $msg=__('messages.Doctor Update Successfully');
                $checkemail=User::where("email",$request->get("email"))->where("id",'!=',Auth::id())->first();
                if($checkemail){
                        Session::flash('message',__('messages.Email Id Already Existe')); 
                        Session::flash('alert-class', 'alert-danger'); 
                        return redirect("dcotor/editprofile/1");
                }
                $usd=User::find(Auth::id());
                $usd->name=$request->get("name");
                $usd->email=$request->get("email");
                $usd->password=$request->get("password");
                $usd->phone_no=$request->get("phone_no");
                $usd->usertype='3';
                $usd->save();
            }else{
                $store=new Doctor();
                $usd=new User();
                $usd->name=$request->get("name");
                $usd->email=$request->get("email");
                $usd->password=$request->get("password");
                $usd->phone_no=$request->get("phone_no");
                $usd->usertype='3';
                $usd->save();
                $store->user_id=$usd->id;
                $msg=__('messages.Doctor Add Successfully');
            }
            $store->department_id=$request->get("department");
            $store->name=$request->get("name");
            $store->email=$request->get("email");
            $store->password=$request->get("password");
            $store->phone_no=$request->get("phone_no");
            $store->about_us=$request->get("aboutus");
            $store->service=$request->get("service");
            $store->facebook_id=$request->get("facebook");
            $store->twitter_id=$request->get("twitter_id");
            $store->google_id=$request->get("google_id");
            $store->instagram_id=$request->get("instagram_id");
            $store->image=$img_url;
            $store->save();           
            return redirect("doctor/editprofile/2");

    }
 
    public function updateworkinghours(Request $request){
              if($request->get("id")=="0"){
                    Session::flash('message',__('messages.Please Fill Up Basic Information First Then Process Ahead')); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect('doctor/editprofile/2');
              }
              $workid=$request->get("work_id");
              $day=$request->get("day");
              $from=$request->get("from");
              $to=$request->get("to");
              for($i=0;$i<7;$i++){
                    if($workid[$i]==0){
                        $data=new TimeTable();
                    }else{
                        $data=TimeTable::find($workid[$i]);
                    }
                    $data->doctor_id=$request->get("id");
                    $data->day=$day[$i];
                    $data->from=$from[$i];
                    $data->to=$to[$i];
                    $data->save();
              }
            Session::flash('message',__('messages.Doctor Save Successfully')); 
            Session::flash('alert-class', 'alert-success');
            return redirect("doctor/editprofile/1");
    }

    
    public function showreview(){
        $data=Review::with("doctors","users")->where("doctor_id",Auth::id())->get();
       return view("doctor.review")->with("data",$data);
    }

}
