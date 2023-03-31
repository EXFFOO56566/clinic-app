@extends('admin.layout')
@section('title')
{{__('messages.Change password')}}
@stop
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.Change password')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.Change password')}}</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="content mt-3">
   <div class="row rowset rowcenter">
      <div class="col-lg-6">
         <div class="card">
            <div class="card-header">
               <strong class="card-title">{{__('messages.Change password')}}</strong>
            </div>
            <div class="card-body">
               <div id="pay-invoice">
                  <div class="card-body">
                     @if(Session::get("message"))
                     <div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
                        {{Session::get("message")}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                     </div>
                     @endif
                     <form action="{{url('admin/updatepassword')}}" method="post"   enctype="multipart/form-data">
                        {{csrf_field()}}                                           
                        <div class="form-group">
                           <label for="name" class=" form-control-label">
                           {{__('messages.Enter Current Password')}}
                           <span class="reqfield">*</span>
                           </label>
                           <input type="password" id="cpwd" placeholder="{{__('messages.Enter Current Password')}}" class="form-control" name="cpwd" required="" onchange="checkcurrentpwdtest(this.value)" >
                        </div>
                        <div class="form-group">
                           <label for="name" class=" form-control-label">
                           {{__('messages.Enter New Password')}}
                           <span class="reqfield">*</span>
                           </label>
                           <input type="password" id="npwd" placeholder="{{__('messages.Enter New Password')}}" class="form-control" name="npwd" required="" >
                        </div>
                        <div class="form-group">
                           <label for="name" class=" form-control-label">
                           {{__('messages.Re-enter New Password')}}
                           <span class="reqfield">*</span>
                           </label>
                           <input type="password" id="rpwd" placeholder="{{__('messages.Re-enter New Password')}}" class="form-control" name="rpwd" onchange="changecheckboth(this.value)" required="">
                        </div>
                        <div class="form-group">
                           @if(Session::get("is_demo")=='1')
                              <button type="button" onclick="disablebtn()" class="btn btn-success btn-flat m-b-30 m-t-30">{{__('messages.Save')}}</button>
                           @else
                               <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                                   {{__('messages.Save')}}
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
</div>
@stop