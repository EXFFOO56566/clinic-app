<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Model\Album;
use App\Model\Gallery;
use Session;
use validate;
class GalleryController extends Controller
{
 
     public function index(){
         $album=Album::all();
         return view("admin.gallery.default")->with("data",$album);
     }

     public function savealbum($id){
         $data=Album::find($id);
         return view("admin.gallery.savealbum")->with("id",$id)->with("data",$data);
     }

     public function updatealbum(Request $request){
        $request->validate([
                'name' => 'required'
            ]);
        if($request->get("id")!='0'){
            $store=Album::find($request->get("id"));
            $msg=__('messages.Album Update Successfully');
        }
        else{
            $store=new Album();
             $msg=__('messages.Album Add Successfully');
        }
        $store->name=$request->get("name");
        $store->save();
        Session::flash('message',$msg); 
        Session::flash('alert-class', 'alert-success');
        return redirect("admin/gallery");
     }

     public function deletealbum($id){
        $store=Album::find($id);
        $findgallery=Gallery::where("album_id",$id)->get();
        if(count($findgallery)>0){
             foreach ($findgallery as $k) {
                  $image_path = public_path() ."/upload/gallery/".$k->icon;
                  if(file_exists($image_path)) {
                        try {
                               unlink($image_path);
                        }
                        catch(Exception $e) {
                        }                        
                  }
                  $k->delete();
            }
        }       
        $store->delete();
        Session::flash('message',__('messages.Album Delete Successfully')); 
        Session::flash('alert-class', 'alert-success');
        return redirect("admin/gallery");
     }


     public function addphoto($id){
        $data=Gallery::where("album_id",$id)->orderby("id","DESC")->get();
        return view("admin.gallery.photodefault")->with("album_id",$id)->with("data",$data);
     }

    

     public function uploadimage(Request $request,$id){
            try {
                if (
                    !isset($_FILES['file']['error']) ||
                    is_array($_FILES['file']['error'])
                ) {
                    throw new RuntimeException(__('messages.Invalid parameters'));
                }

                switch ($_FILES['file']['error']) {
                    case UPLOAD_ERR_OK:
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        throw new RuntimeException(__('messages.No file sent.'));
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new RuntimeException(__('messages.Exceeded filesize limit'));
                    default:
                        throw new RuntimeException(__('messages.Unknown errors'));
                }
                $str=explode(".",$_FILES['file']['name']);
                
                     $fname=uniqid().".".$str[1];
                
                $filepath = public_path().'/upload/gallery'.'/'.$fname;
                if (!move_uploaded_file(
                    $_FILES['file']['tmp_name'],
                    $filepath
                )) {
                    throw new RuntimeException(__('messages.Failed to move uploaded file'));
                }
                
                $store=new Gallery();
                $store->album_id=$id;
                $store->image=$fname;
                $store->save();
                // All good, send the response
                echo json_encode([
                    'status' => 'ok',
                    'path' => $fname
                ]);

            } catch (RuntimeException $e) {
                // Something went wrong, send the err message as JSON
                http_response_code(400);

                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
     }

     public function deletegalleryphoto($id){
          $data=Gallery::find($id);
          $image_path = public_path() ."/upload/gallery/".$data->image;
          if(file_exists($image_path)) {
                try {
                       unlink($image_path);
                }
                catch(Exception $e) {
                }                        
          }
          $data->delete();
          Session::flash('message',__('messages.image Delete Successfully')); 
          Session::flash('alert-class', 'alert-success');
          return redirect()->back();
     }
    
  
}
