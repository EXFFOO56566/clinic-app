@extends('admin.layout')
@section('title')
{{__('messages.Setting')}}
@stop
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.Setting')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.Setting')}}</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="content mt-3">
   <div class="animated fadeIn">
      <div class="row rowcenter">
         <div class="col-lg-12">
            <div class="card">
               <div class="card-body">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                     <li class="nav-item">
                        <a class="nav-link <?= $id==1?'active':""?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{__('messages.Basic Information')}}</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link <?= $id==2?'active':""?>" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{__('messages.Server Key')}}</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link <?= $id==3?'active':""?>" id="step3-tab" data-toggle="tab" href="#step3" role="tab" aria-controls="step3" aria-selected="false">{{__('messages.Setting')}}</a>
                     </li>
                      <li class="nav-item">
                        <a class="nav-link <?= $id==4?'active':""?>" id="terms-tab" data-toggle="tab" href="#terms" role="tab" aria-controls="terms" aria-selected="false">{{__('messages.Terms Condition')}}</a>
                     </li>
                      <li class="nav-item">
                        <a class="nav-link <?= $id==5?'active':""?>" id="privacy-tab" data-toggle="tab" href="#privacy" role="tab" aria-controls="privacy" aria-selected="false">{{__('messages.Privacy Policy')}}</a>
                     </li>
                      <li class="nav-item">
                        <a class="nav-link <?= $id==6?'active':""?>" id="banner-tab" data-toggle="tab" href="#banner" role="tab" aria-controls="banner" aria-selected="false">{{__('messages.Main Banner')}}</a>
                     </li>
                  </ul>
                  <div class="tab-content pl-3 p-1" id="myTabContent">
                     <div class="tab-pane fade <?= $id==1?'show active':""?>" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form method="post" action="{{url('admin/savebasicsetting')}}" class="appbtn">
                           {{csrf_field()}}   
                           <div class="form-group">
                              <label for="name" class=" form-control-label">
                              {{__('messages.Company Logo')}} (190X49)</b>
                              <span class="reqfield">*</span>
                              </label>
                              <div id="uploaded_image">
                                 <div class="upload-btn-wrapper">
                                    <button class="btn imgcatlog">
                                    <input type="hidden" name="real_logo" id="real_logo" value="<?= isset($data->logo)?$data->logo:""?>"/>
                                    <?php 
                                       if(isset($data->logo)){
                                           $path=asset('public/upload/images/')."/".$data->logo;
                                       }
                                       else{
                                           $path=asset('public/upload/images/logo1.png');
                                       }
                                       ?>
                                    <img src="{{$path}}" alt="..." class="img-thumbnail departmentimg"  id="img_logo" >
                                    </button>
                                    <input type="hidden" name="logo" id="logo"/>
                                    <input type="file" name="upload_image_logo" id="upload_image_logo" />
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="name" class=" form-control-label">
                              {{__('messages.Site RTL')}}
                              <a href="{{url('admin/changesettingstatus/is_rtl')}}">
                              @if($data->is_rtl==0)
                              <i class="fa fa-toggle-off iconde"></i>
                              @else
                              <i class="fa fa-toggle-on iconde"></i>
                              @endif
                              </a>
                              </label> 
                           </div>
                           <div class="form-group">
                              <label>{{__('messages.Address')}}</label>
                              <textarea class="form-control" name="address" id="address" placeholder="{{__('messages.Enter').' '.__('messages.Address')}}"><?= isset($data->address)?$data->address:""?></textarea>
                           </div>
                           <div class="form-group">
                              <label>{{__('messages.Phone No')}} </label>
                              <input type="text" name="phone_no" id="phone_no" class="form-control" placeholder="{{__('messages.Enter').' '.__('messages.Phone No')}}" value="<?= isset($data->phone_no)?$data->phone_no:""?>"/>
                           </div>
                           <div class="form-group">
                              <label>{{__('messages.Email')}}</label>
                              <input type="email" name="email" id="email" class="form-control" placeholder="{{__('messages.Enter').' '.__('messages.Email')}}" value="<?= isset($data->email)?$data->email:""?>" />
                           </div>
                           <div class="form-group">
                              <label for="name" class=" form-control-label">
                              {{__('messages.Default Timezone')}}
                              <span class="reqfield">*</span>
                              </label>
                              <select class="form-control" name="timezone" id="timezone" required="">
                                 <option value="">{{__('messages.Select Timezone')}}</option>
                                 @foreach($timezone as $tz=>$value)
                                 <option value="{{$tz}}" <?=$data->timezone ==$tz ? ' selected="selected"' : '';?>>{{$value}}</option>
                                 @endforeach
                              </select>
                           </div>
                           <div class="form-group">
                              <label for="name" class=" form-control-label">
                              {{__('messages.Default Currency')}}
                              <span class="reqfield">*</span>
                              </label>
                              <select class="form-control" name="currency" id="currency" required="">
                                 <option value="{{$data->currency}}" selected>{{$data->currency}}</option>
                                 @include('admin.setting.currency')
                              </select>
                           </div>
                           @if(Session::get("is_demo")=='1')
                              <button type="button" onclick="disablebtn()" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.Submit')}}</button>
                           @else
                              <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.Submit')}}</button>
                           @endif
                                         
                        </form>
                     </div>
                     <div class="tab-pane fade <?= $id==2?'show active':""?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form method="post" action="{{url('admin/saveserverkey')}}" class="btnsaveoption">
                           {{csrf_field()}}    
                           <div class="form-group">
                              <label>{{__('messages.Android Server Key')}}</label>
                              <textarea class="form-control" name="android_server_key" id="android_server_key" placeholder="{{__('messages.Enter').' '.__('messages.Android Server Key')}}"><?= isset($data->android_server_key)?$data->android_server_key:""?></textarea>
                           </div>
                           <div class="form-group">
                              <label>{{__('messages.Ios Server Key')}}</label>
                              <textarea class="form-control" name="ios_server_key" id="ios_server_key" placeholder="{{__('messages.Enter').' '.__('messages.Ios Server Key')}}"><?= isset($data->ios_server_key)?$data->ios_server_key:""?></textarea>
                           </div>
                          
                           <div class="form-group">
                              <label>{{__('messages.TWILIO_ACCOUNT_SID')}}</label>
                              <input type="text" class="form-control" name="TWILIO_ACCOUNT_SID" id="TWILIO_ACCOUNT_SID" placeholder="{{__('messages.Enter').' '.__('messages.TWILIO_ACCOUNT_SID')}}" value="<?= isset($data->TWILIO_ACCOUNT_SID)?$data->TWILIO_ACCOUNT_SID:""?>">
                           </div>
                           <div class="form-group">
                              <label>{{__('messages.TWILIO_API_KEY')}}</label>
                              <input type="text" class="form-control" name="TWILIO_API_KEY" id="TWILIO_API_KEY" placeholder="{{__('messages.Enter').' '.__('messages.TWILIO_API_KEY')}}" value="<?= isset($data->TWILIO_API_KEY)?$data->TWILIO_API_KEY:""?>">
                           </div>
                            <div class="form-group">
                              <label>{{__('messages.TWILIO_API_SECRET')}}</label>
                              <input type="text" class="form-control" name="TWILIO_API_SECRET" id="TWILIO_API_SECRET" placeholder="{{__('messages.Enter').' '.__('messages.TWILIO_API_SECRET')}}" value="<?= isset($data->TWILIO_API_SECRET)?$data->TWILIO_API_SECRET:""?>">
                           </div>
                            <div class="form-group">
                              <label>{{__('messages.TWILIO_CHAT_SERVICE_SID')}}</label>
                              <input type="text" class="form-control" name="TWILIO_CHAT_SERVICE_SID" id="TWILIO_CHAT_SERVICE_SID" placeholder="{{__('messages.Enter').' '.__('messages.TWILIO_CHAT_SERVICE_SID')}}" value="<?= isset($data->TWILIO_CHAT_SERVICE_SID)?$data->TWILIO_CHAT_SERVICE_SID:""?>">
                           </div>
                           <div class="form-group">
                              <label>{{__('messages.TWILIO_AUTH_TOKEN')}}</label>
                              <input type="text" class="form-control" name="TWILIO_AUTH_TOKEN" id="TWILIO_AUTH_TOKEN" placeholder="{{__('messages.Enter').' '.__('messages.TWILIO_AUTH_TOKEN')}}" value="<?= isset($data->TWILIO_AUTH_TOKEN)?$data->TWILIO_AUTH_TOKEN:""?>">
                           </div>
                           @if(Session::get("is_demo")=='1')
                              <button type="button" onclick="disablebtn()" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.Submit')}}</button>
                           @else
                               <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.Submit')}}</button>    
                           @endif  
                        </form>
                     </div>
                     <div class="tab-pane fade <?= $id==3?'show active':""?>" id="step3" role="tabpanel" aria-labelledby="step3-tab">
                        <form method="post" action="{{url('admin/savesitesetting')}}" class="btnsaveoption" >
                           {{csrf_field()}}    
                           <div class="form-group">
                              <label>{{__('messages.Facebook ID')}}<span class="reqfield">*</span></label>
                              <input type="text" name="facebook_id" id="facebook_id" class="form-control" placeholder="{{__('messages.Enter').' '.__('messages.Facebook ID')}}" value="<?= isset($data->facebook_id)?$data->facebook_id:""?>"/>
                           </div>
                           <div class="form-group">
                              <label>{{__('messages.Twitter ID')}}<span class="reqfield">*</span></label>
                              <input type="text" name="twitter_id" id="twitter_id" class="form-control" placeholder="{{__('messages.Enter').' '.__('messages.Twitter ID')}}" value="<?= isset($data->twitter_id)?$data->twitter_id:""?>"/>
                           </div>
                           <div class="form-group">
                              <label>{{__('messages.Google ID')}}<span class="reqfield">*</span></label>
                              <input type="text" name="google_id" id="google_id" class="form-control" placeholder="{{__('messages.Enter').' '.__('messages.Google ID')}}" value="<?= isset($data->google_id)?$data->google_id:""?>"/>
                           </div>
                           <div class="form-group">
                              <label>{{__('messages.Instagram ID')}}<span class="reqfield">*</span></label>
                              <input type="text" name="instagram_id" id="instagram_id" class="form-control" placeholder="{{__('messages.Enter').' '.__('messages.Instagram ID')}}" value="<?= isset($data->instagram_id)?$data->instagram_id:""?>"/>
                           </div>
                           <div class="form-group">
                              <label>{{__('messages.Happy Client')}}<span class="reqfield">*</span></label>
                              <input type="text" name="happy_client" id="happy_client" class="form-control" placeholder="{{__('messages.Happy Client')}}" value="<?= isset($data->happy_client)?$data->happy_client:""?>"/>
                           </div>
                           <div class="form-group">
                              <label>{{__('messages.Surgery Complete')}}<span class="reqfield">*</span></label>
                              <input type="text" name="surgery_complete" id="surgery_complete" class="form-control" placeholder="{{__('messages.Surgery Complete')}}" value="<?= isset($data->surgery_complete)?$data->surgery_complete:""?>"/>
                           </div>
                           <div class="form-group">
                              <label>{{__('messages.Expert Doctor')}}<span class="reqfield">*</span></label>
                              <input type="text" name="expert_doctor" id="expert_doctor" class="form-control" placeholder="{{__('messages.Expert Doctor')}}" value="<?= isset($data->expert_doctor)?$data->expert_doctor:""?>"/>
                           </div>
                           <div class="form-group">
                              <label>{{__('messages.Worldwide Branch')}}<span class="reqfield">*</span></label>
                              <input type="text" name="worldwide_branch" id="worldwide_branch" class="form-control" placeholder="{{__('messages.Worldwide Branch')}}" value="<?= isset($data->worldwide_branch)?$data->worldwide_branch:""?>"/>
                           </div>
                            <div class="form-group">
                              <label>{{__('messages.Working days')}}<span class="reqfield">*</span></label>
                              <input type="text" name="working_day" id="working_day" class="form-control" placeholder="{{__('messages.Working days')}}" value="<?= isset($data->working_day)?$data->working_day:""?>"/>
                           </div>
                           @if(Session::get("is_demo")=='1')
                              <button type="button" onclick="disablebtn()" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.Submit')}}</button>
                           @else
                               <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.Submit')}}</button>  
                           @endif 
                           
                        </form>
                     </div>
                     
                     <div class="tab-pane fade <?= $id==4?'show active':""?>" id="terms" role="tabpanel" aria-labelledby="terms-tab">
                        <form method="post" action="{{url('admin/saveterms')}}" class="btnsaveoption">
                           {{csrf_field()}}   
                           <div class="form-group">
                              <label>{{__('messages.Terms Condition')}}</label>
                              <textarea class="form-control" name="termscondition" id="termscondition"><?= isset($data->terms_condition)?$data->terms_condition:""?></textarea>
                           </div>
                           @if(Session::get("is_demo")=='1')
                              <button type="button" onclick="disablebtn()" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.Submit')}}</button>
                           @else
                               <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.Submit')}}</button>   
                           @endif 
                                       
                        </form>
                     </div>
                     <div class="tab-pane fade <?= $id==5?'show active':""?>" id="privacy" role="tabpanel" aria-labelledby="home-tab">
                        <form method="post" action="{{url('admin/saveprivacy')}}" class="btnsaveoption">
                           {{csrf_field()}}   
                            <div class="form-group">
                              <label>{{__('messages.Privacy Policy')}}</label>
                              <textarea class="form-control" name="privacydata" id="privacydata" placeholder=""><?= isset($data->privacy_policy)?$data->privacy_policy:""?></textarea>
                           </div>
                           @if(Session::get("is_demo")=='1')
                              <button type="button" onclick="disablebtn()" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.Submit')}}</button>
                           @else
                              <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.Submit')}}</button>   
                           @endif       
                        </form>
                     </div>

                      <div class="tab-pane fade <?= $id==6?'show active':""?>" id="banner" role="tabpanel" aria-labelledby="banner-tab">
                        <form method="post" action="{{url('admin/saveuploadsection')}}" class="btnsaveoption" enctype="multipart/form-data">
                           {{csrf_field()}}   
                            <div class="form-group">
                               <div class="row">
                                  <div class="form-group col-md-3" >
                                      <img src="{{asset('public/upload/web').'/'.$data->main_banner}}" class="webimage" />
                                  </div>
                                  <div class="form-group col-md-9">
                                      <label for="stripe_key" class=" form-control-label">{{__('messages.Main Banner')}} (100X100)<span class="reqfield">*</span></label>
                                      <input type="file" name="banner" id="handbanner" required class="form-control">
                                  </div>
                              </div>
                              
                           </div>
                            @if(Session::get("is_demo")=='1')
                              <button type="button" onclick="disablebtn()" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.Submit')}}</button>
                           @else
                              <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.Submit')}}</button>   
                           @endif              
                        </form>
                     </div>

                     </div>

                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   CKEDITOR.replace('termscondition');
   CKEDITOR.replace('privacydata');
</script>
@stop