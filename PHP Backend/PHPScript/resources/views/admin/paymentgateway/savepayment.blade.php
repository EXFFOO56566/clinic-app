@extends('admin.layout')
@section('title')
{{__('messages.Save Payment Gateway')}}
@stop
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.Save Payment Gateway')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li>
                  <a href="{{url('admin/paymentgateway')}}">{{__('messages.Payment Gateway')}}</a>
               </li>
               <li class="active">{{__('messages.Save Payment Gateway')}}</li>
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
                  <form action="{{url('admin/updatepaymentgateway')}}" method="post" enctype="multipart/form-data">
                     {{csrf_field()}}
                     <input type="hidden" name="id" id="id" value="{{$data->id}}"/>
                     <div class="form-group">
                        <label for="name" class=" form-control-label">
                        {{__('messages.Name')}}
                        <span class="reqfield" >*</span>
                        </label>
                        <input type="text" id="name" placeholder="{{__('messages.Enter').' '.__('messages.Name')}}" class="form-control" required name="name" value="{{ isset($data->gateway_name)?$data->gateway_name:''}}">
                     </div>
                     @if($data->id=='2')
                      <div class="form-group">
                        <label for="name" class=" form-control-label">
                        {{__('messages.merchant_key')}}
                        <span class="reqfield" >*</span>
                        </label>

                        <input type="text" id="merchant_key" placeholder="{{__('messages.Enter').' '.__('messages.Key')}}" class="form-control"  name="merchant_key" value="{{ isset($data->merchant_key)?$data->merchant_key:''}}">
                     </div>
                     @endif
                      <div class="form-group">
                        <label for="name" class=" form-control-label">
                        {{__('messages.Key')}}
                        <span class="reqfield" >*</span>
                        </label>
                        <input type="text" id="key" placeholder="{{__('messages.Enter').' '.__('messages.Key')}}" class="form-control" required name="key" value="{{ isset($data->key)?$data->key:''}}">
                     </div>
                     <div class="form-group">
                        <label for="name" class=" form-control-label">
                        {{__('messages.Secert')}}
                        <span class="reqfield" >*</span>
                        </label>
                        <input type="password" id="secert" placeholder="{{__('messages.Enter').' '.__('messages.Secert')}}" class="form-control" required name="secert" value="{{ isset($data->secert)?$data->secert:''}}">
                     </div>
                     <div class="form-group">
                        <label for="name" class=" form-control-label">
                        {{__('messages.mode')}}
                        <span class="reqfield" >*</span>
                        </label>
                        <select  class="form-control"  required id="mode" name="mode">
                             <option value="test" <?= isset($data) && $data->mode == 'test' ? 'select="selected"' : "" ?> >{{__('messages.Test')}}</option>
                            <option value="live" <?= isset($data) && $data->mode == 'live' ? 'select="selected"' : "" ?>>{{__('messages.Live')}}</option>
                            <option value="sandbox" <?= isset($data) && $data->mode == 'sandbox' ? 'select="selected"' : "" ?>>{{__('messages.Sandbox')}}</option>
                        </select>
                     </div>
                     @if($data->image)
                        <img src="{{asset('public/upload/payment').'/'.$data->image}}" class="departmentimg" />
                     @endif
                     <input type="file" name="image" id="image"  class="form-control"/>
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