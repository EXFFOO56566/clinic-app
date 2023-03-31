@extends('admin.layout')
@section('title')
{{__('messages.Save').' '.__('messages.Facilities')}}
@stop
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.Save').' '.__('messages.Facilities')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li>
                  <a href="{{url('admin/service')}}">{{__('messages.Facilities')}}</a>
               </li>
               <li class="active">{{__('messages.Save').' '.__('messages.Facilities')}}</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="content mt-3">
   <div class="animated fadeIn">
      <div class="row rowcenter">
         <div class="col-md-9">
            <div class="card">
               <div class="card-body">
                  @if(Session::has('message'))
                  <div class="col-sm-12">
                     <div class="alert  {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">{{ Session::get('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
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
                  <form action="{{url('admin/updateservice')}}" method="post"  enctype="multipart/form-data">
                     {{csrf_field()}}
                     <input type="hidden" name="id" id="id" value="{{$service_id}}"/>
                     <input type="hidden" name="real_image" id="real_image" value="{{ isset($servicedata->icon)?$servicedata->icon:''}}"/>
                     <div class="form-group">
                        <label for="name" class=" form-control-label">
                        {{__('messages.Name')}}
                        <span class="reqfield" >*</span>
                        </label>
                        <input type="text" id="name" placeholder="{{__('messages.Enter').' '.__('messages.Name')}}" class="form-control" required name="name" value="{{ isset($servicedata->name)?$servicedata->name:''}}">
                     </div>
                     <div class="form-group">
                        <label for="email" class=" form-control-label">
                        {{__('messages.Description')}}<span class="reqfield" >*</span>
                        </label>
                        <textarea required id="description" name="description"  class="form-control">{{ isset($servicedata->description)?$servicedata->description:''}}</textarea>
                     </div>
                     <div class="form-group">
                        <label for="file" class=" form-control-label">  
                        {{__('messages.Image')}}(80X80)<span class="reqfield" >*</span>
                        </label>
                        @if($service_id!=0)
                        <img src="{{asset('public/upload/service').'/'.$servicedata->icon}}" class="imgsize1" /> 
                        @endif
                        <div>
                           <input type="file" id="file" name="image" class="form-control-file" accept="image/*">
                        </div>
                     </div>
                     <div>
                         @if(Session::get("is_demo")=='1')
                           <button id="payment-button" type="button" onclick="disablebtn()" class="btn btn-lg btn-info floatright">
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
            </div>
         </div>
      </div>
   </div>
</div>

@stop