<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Model\Package;
use App\Model\Paymentgateway;
use App\Model\Department;
use Session;
class PackageController extends Controller
{
 
    public function index(){
        $data=Package::with('deparmentdata')->where('is_delete','0')->get();
        return view("admin.package.default")->with("data",$data);
    }

    public function savepackage($id){
        $data=Package::with('deparmentdata')->find($id);

        $department=Department::all();
        return view("admin.package.savepackage")->with("package_id",$id)->with("data",$data)->with("department",$department);
    }

    public function updatepackage(Request $request){
         if($request->get("id")!="0"){
                $request->validate([
                    'name' => 'required',
                    'price' => 'required',
                    'description'=>'required',
                    'department_id'=>'required'
                ]);
              }else{
                $request->validate([
                    'name' => 'required',
                    'price' => 'required',
                    'description'=>'required',
                    'department_id'=>'required'
                ]);
             }         

            if($request->get("id")!="0"){
                $store=Package::find($request->get("id"));
                $msg=__("messages.Health Package Update Successfully");
            }else{
                $store=new Package();
                $msg=__("messages.Health Package Add Successfully");
            }

            $store->name=$request->get("name");
            $store->price=$request->get("price");
            $store->description=$request->get("description");  
            $store->department_id=$request->get("department_id");         
            $store->save(); 
            Session::flash('message',$msg); 
            Session::flash('alert-class', 'alert-success');
            return redirect("admin/package");
    }
    
    public function deletepackage($id){
        $data=Package::find($id);
        $data->is_delete='1';
        $data->save();
        Session::flash('message',__("messages.Package Delete Successfully")); 
        Session::flash('alert-class', 'alert-success');
        return redirect("admin/package");
    }
	
	public function getpaymentgateway(){
		$data=Paymentgateway::all();
		return view("admin.paymentgateway.default")->with("data",$data);
	}
	
	public function editpaymentgateway($id){
		$data=Paymentgateway::find($id);
		if($data){
			return view("admin.paymentgateway.savepayment")->with("data",$data);
		}
		return redirect("admin/dashboard");
		
	}
	
	public function updatepaymentgateway(Request $request){
		//dd($request->all());
		$store=Paymentgateway::find($request->get("id"));
		$img=$store->image;
		 if ($request->hasFile('image')) 
                  {
                     $file = $request->file('image');
                     $filename = $file->getClientOriginalName();
                     $extension = $file->getClientOriginalExtension() ?: 'png';
                     $folderName = '/upload/payment';
                     $picture = 'pay_'.mt_rand(100000,999999). '.' . $extension;
                     $destinationPath = public_path() . $folderName;
                     $request->file('image')->move($destinationPath, $picture);
                     $img_url = $picture;
                     $image_path = public_path() ."/upload/payment/".$img;
                      
                 }else{
                     $img_url = $img;
                 }
				 
				 $store->gateway_name=$request->get("name");
				 $store->key=$request->get("key");
				 $store->secert=$request->get("secert");
                 $store->merchant_key=$request->get("merchant_key");
				 $store->image=$img_url;
				 $store->mode=$request->get("mode");
				 $store->save();
				    Session::flash('message',__("messages.PaymentGateway Update Successfully")); 
					Session::flash('alert-class', 'alert-success');
					return redirect("admin/paymentgateway");
	}
	
	public function changestatuspayment($id,$status){
		$store=PaymentGateway::find($id);
		$store->is_active=$status;
		$store->save();
		 Session::flash('message',__("messages.PaymentGateway Status Successfully")); 
					Session::flash('alert-class', 'alert-success');
					return redirect("admin/paymentgateway");
	}
}












