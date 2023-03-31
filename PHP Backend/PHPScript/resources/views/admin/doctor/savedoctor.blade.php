@extends('admin.layout')
@section('title')
{{__('messages.Save Doctor')}}
@stop
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.Save Doctor')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li><a href="{{url('admin/doctor')}}">{{__('messages.Doctors')}}</a></li>
               <li class="active">{{__('messages.Save Doctor')}}</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="content mt-3">
   <div class="animated fadeIn">
      <div class="row rowcenter">
         <div class="col-md-10">
            <div class="card">
               <div class="card-body">
                  @if(Session::get("message"))
                  <div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
                     {{Session::get("message")}}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">×</span>
                     </button>
                  </div>
                  @endif
                  @if ($errors->any())
                  <div class="alert alert-danger">
                     <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                     </ul>
                  </div>
                  @endif  
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                     <li class="nav-item">
                        <a class="nav-link <?= $tab_id == 1 ? 'active' : "" ?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{__('messages.Basic Information')}}</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link <?= $tab_id == 2 ? 'active' : "" ?>" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{__('messages.Working Hours')}}</a>
                     </li>
                  </ul>
                  <div class="tab-content pl-3 p-1" id="myTabContent">
                     <div class="tab-pane fade <?= $tab_id == 1 ? 'show active' : "" ?>" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form action="{{url('admin/updatedoctorprofile')}}" method="post"  enctype="multipart/form-data">
                           {{csrf_field()}}
                           <input type="hidden" name="id" id="id" value="{{$doctor_id}}"/>
                           <input type="hidden" name="real_image" id="real_image" value="{{ isset($data->image)?$data->image:''}}"/>
                           <div class="form-group">
                              <div class="col-md-6" >
                                 <label for="name" class=" form-control-label">
                                 {{__('messages.Select Department')}}
                                 <span class="reqfield" >*</span>
                                 </label>
                                 <select id="department" name="department" required class="form-control">
                                    <option value="">{{__('messages.Select Department')}}</option>
                                    @foreach($department as $d)
                                    <option value="{{$d->id}}" <?= isset($data->department_id) && $data->department_id == $d->id ? "selected='selected'" : "" ?> >{{$d->name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                              <div class="col-md-6"> 
                                 <label for="name" class=" form-control-label">
                                 {{__('messages.Doctor Name')}}
                                 <span class="reqfield" >*</span>
                                 </label>
                                 <input type="text" id="name" placeholder="{{__('messages.Enter').' '.__('messages.Doctor Name')}}" class="form-control" required name="name" value="{{ isset($data->name)?$data->name:''}}">
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-md-6">
                                 <label for="email" class=" form-control-label">
                                 {{__('messages.Email')}}
                                 <span class="reqfield" >*</span>
                                 </label>
                                 <input type="text" id="email" placeholder="{{__('messages.Enter').' '.__('messages.Email')}}" class="form-control" required name="email" value="{{ isset($data->email)?$data->email:''}}">
                              </div>
                              <div class="col-md-6">
                                 <label for="name" class=" form-control-label">
                                 {{__('messages.Password')}}
                                 <span class="reqfield" >*</span>
                                 </label>
                                 <input type="text" id="password" placeholder="{{__('messages.Enter').' '.__('messages.Password')}}" class="form-control" required name="password" value="{{ isset($data->password)?$data->password:''}}">
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-md-6">
                                 <label for="phone" class=" form-control-label">
                                 {{__('messages.Phone No')}}
                                 <span class="reqfield" >*</span>
                                 </label>
                                 <input type="text" id="phone_no" placeholder="{{__('messages.Enter').' '.__('messages.Phone No')}}" class="form-control" required name="phone_no" value="{{ isset($data->phone_no)?$data->phone_no:''}}">
                              </div>
                              <div class="col-md-6">
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-md-6">
                                 <label for="phone" class=" form-control-label">
                                 {{__('messages.Facebook ID')}}
                                 </label>
                                 <input type="text" id="facebook" required placeholder="{{__('messages.Enter').' '.__('messages.Facebook ID')}}"  class="form-control"  name="facebook" value="{{ isset($data->facebook_id)?$data->facebook_id:''}}">
                              </div>
                              <div class="col-md-6">
                                 <label for="name" class=" form-control-label">
                                 {{__('messages.Twitter ID')}}
                                 </label>
                                 <input type="text" id="twitter_id" required placeholder="{{__('messages.Enter').' '.__('messages.Twitter ID')}}"  class="form-control"  name="twitter_id" value="{{ isset($data->twitter_id)?$data->twitter_id:''}}">
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-md-6">
                                 <label for="phone" class=" form-control-label">
                                 {{__('messages.Google ID')}}
                                 </label>
                                 <input type="text" id="google_id" required placeholder="{{__('messages.Enter').' '.__('messages.Google ID')}}"  class="form-control"  name="google_id" value="{{ isset($data->google_id)?$data->google_id:''}}">
                              </div>
                              <div class="col-md-6">
                                 <label for="name" class=" form-control-label">
                                 {{__('messages.Instagram ID')}}
                                 </label>
                                 <input type="text" id="instagram_id" required placeholder="{{__('messages.Enter').' '.__('messages.Instagram ID')}}"  class="form-control"  name="instagram_id" value="{{ isset($data->instagram_id)?$data->instagram_id:''}}">
                              </div>
                           </div>
                           <div class=" col-md-12 form-group">
                              <label for="email" class=" form-control-label">
                              {{__('messages.About us')}}<span class="reqfield" >*</span>
                              </label>
                              <textarea required id="description" name="aboutus" class="form-control">{{ isset($data->about_us)?$data->about_us:''}}</textarea>
                           </div>
                           <div class="col-md-12 form-group">
                              <label for="email" class=" form-control-label">
                              {{__('messages.Service')}}<span class="reqfield" >*</span>
                              </label>
                              <textarea required id="servicedoctor" name="service"  class="form-control">{{ isset($data->service)?$data->service:''}}</textarea>
                           </div>
                           <div class="col-md-12 form-group">
                              <label for="file" class=" form-control-label">  
                              {{__('messages.Image')}}<span class="reqfield" >*</span>
                              </label>
                              @if($doctor_id!=0)
                              <img src="{{asset('public/upload/doctor').'/'.$data->image}}" class="imgsize1 btndepartwarning" /> 
                              @endif
                              <div>
                                 <input type="file" id="file" name="image" class="form-control-file" accept="image/*">
                              </div>
                           </div>
                           <div>
                               @if(Session::get("is_demo")=='1')
                            <button id="payment-button" type="button" onclick="disablebtn()"  class="btn btn-lg btn-info floatright">
                              {{__('messages.Submit')}}
                              </button>
                        @else
                             <button id="payment-button" type="submit" class="btn btn-lg btn-info floatright">
                              {{__('messages.Submit')}}
                              </button>
                        @endif
                             
                           </div>
                        </form>
                     </div>
                     <div class="tab-pane fade <?= $tab_id == 2 ? 'show active' : "" ?> btnsaveoption" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form action="{{url('admin/updateworkinghours')}}" method="post"  enctype="multipart/form-data">
                           {{csrf_field()}}
                           <input type="hidden" name="id" id="id" value="{{$doctor_id}}"/>
                           <div class="table-responsive">
                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th>#</th>
                                    <th>{{__('messages.Day')}}</th>
                                    <th>{{__('messages.From')}}</th>
                                    <th>{{__('messages.To')}}</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php $arr = [__('messages.Monday'), __('messages.Tuesday'), __('messages.Wednesday'), __('messages.Thursday'), __('messages.Friday'), __('messages.Saturday'), __('messages.Sunday')]; ?>
                                 <?php $i = 0; ?>
                                 @if(count($workinghour)>0)
                                 @foreach($workinghour as $work)
                                 <tr>
                                    <td><input type="hidden" name="work_id[]" id="id{{$i}}" value="<?= isset($work->id) ? $work->id : 0 ?>"></td>
                                    <td><input type="hidden" name="day[]" id="day{{$i}}" readonly="" value="{{$i+1}}" class="form-control" />
                                       @if(($i+1)==1)
                                       <span>{{$arr[0]}}</span>
                                       @elseif(($i+1)==2)
                                       <span> {{$arr[1]}}</span>
                                       @elseif(($i+1)==3)
                                       <span>{{$arr[2]}}</span>
                                       @elseif(($i+1)==4)
                                       <span> {{$arr[3]}}</span>
                                       @elseif(($i+1)==5)
                                       <span> {{$arr[4]}}</span>
                                       @elseif(($i+1)==6)
                                       <span> {{$arr[5]}}</span>
                                       @else
                                       <span> {{$arr[6]}}</span>
                                       @endif
                                    </td>
                                    <td><input type="time" required name="from[]" id="from{{$i}}" class="form-control" value="<?= isset($work->from) ? $work->from : "" ?>" /></td>
                                    <td><input type="time" required name="to[]" id="to{{$i}}" value="<?= isset($work->to) ? $work->to : "" ?>" class="form-control" onchange="checktime(this.value,'{{$i}}')" /></td>
                                 </tr>
                                 <?php $i++; ?>
                                 @endforeach  
                                 @else
                                 @foreach($arr as $a)
                                 <tr>
                                    <td><input type="hidden" name="work_id[]" id="id{{$i}}" value="0"></td>
                                    <td><input type="hidden" name="day[]" id="day{{$i}}" readonly="" value="{{$i+1}}" class="form-control" />
                                       <span>{{$a}}</span>
                                    </td>
                                    <td><input type="time" required name="from[]" id="from{{$i}}" class="form-control" value="{{time()}}"  /></td>
                                    <td><input type="time" required name="to[]" id="to{{$i}}" value="" class="form-control" onchange="checktime(this.value,'{{$i}}')"  /></td>
                                 </tr>
                                 <?php $i++; ?>
                                 @endforeach 
                                 @endif
                              </tbody>
                           </table>
                        </div>
                         @if(Session::get("is_demo")=='1')
                            <button id="payment-button" type="button" onclick="disablebtn()"  class="btn btn-lg btn-info floatright">
                              {{__('messages.Submit')}}
                            </button>
                        @else
                             <button id="payment-button" type="submit" class="btn btn-lg btn-info floatright">
                              {{__('messages.Submit')}}
                             </button>
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

@stop