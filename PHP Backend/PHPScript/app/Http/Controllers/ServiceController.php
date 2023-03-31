<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Model\Service;
use Session;
use DataTables;
use validate;
class ServiceController extends Controller
{
 
    public function index(){
         $service =Service::all();
         return view("admin.service.default")->with("data",$service);
    }

    public function saveservice($id){

        $data=Service::find($id);
        return view("admin.service.saveservice")->with("service_id",$id)->with("servicedata",$data);
    }

    public function updateservice(Request $request){
        if($request->get("id")!="0"){
            $request->validate([
                'name' => 'required',
                'description' => 'required'
            ]);
        }else{
            $request->validate([
                'name' => 'required',
                'description' => 'required',
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
                 $folderName = '/upload/service';
                 $picture = 'service_'.mt_rand(100000,999999). '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('image')->move($destinationPath, $picture);
                 $img_url = $picture;
                 $image_path = public_path() ."/upload/service/".$request->get("real_image");
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
                 $folderName = '/upload/service';
                 $picture = 'service_'.mt_rand(100000,999999). '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('image')->move($destinationPath, $picture);
                 $img_url = $picture;
             }
        }

        if($request->get("id")!="0"){
            $store=Service::find($request->get("id"));
            $msg=__('messages.Facilities Update Successfully');
        }else{
            $store=new Service();
            $msg=__("messages.Facilities Add Successfully");
        }

        $store->name=$request->get("name");
        $store->description=$request->get("description");
        $store->icon=$img_url;
        $store->save(); 
        Session::flash('message',$msg); 
        Session::flash('alert-class', 'alert-success');
        return redirect("admin/service");
    }

    public function deleteservice($id){
      $data=Service::find($id);
      $image_path = public_path() ."/upload/service/".$data->icon;
      if(file_exists($image_path)) {
            try {
                   unlink($image_path);
            }
            catch(Exception $e) {
            }                        
      }
      $data->delete();
      Session::flash('message',__("messages.Facilities Delete Successfully")); 
      Session::flash('alert-class', 'alert-success');
      return redirect("admin/service");
    }
   
  
}
