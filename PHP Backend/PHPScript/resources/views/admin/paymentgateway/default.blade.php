@extends('admin.layout')
@section('title')
{{__('messages.Payment Gateway')}}
@stop
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.Payment Gateway')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.Payment Gateway')}}</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="content mt-3">
   <div class="animated fadeIn">
      <div class="row">
         <div class="col-md-12">
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
                  
                  <div class="table-responsive">
                  <table id="service" class="table  table-striped table-bordered">
                     <thead>
                        <tr>
                           <th>{{__('messages.Id')}}</th>
                           <th>{{__('messages.Name')}}</th>
                           <th>{{__('messages.Image')}}</th>
                           <th>{{__('messages.mode')}}</th>
                           <th>{{__('messages.Status')}}</th>
                           <th>{{__('messages.Action')}}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if(count($data)>0)
                        @foreach($data as $d)
                        <tr>
                           <td>{{$d->id}}</td>
                           <td>{{$d->gateway_name}}</td>
                           <td><img src="{{asset('public/upload/payment/').'/'.$d->Image}}" class="departmentimg"/></td>
                           <td>{{$d->mode}}</td>
                           <td>
                               @if($d->is_active=='1')
                                 {{__('messages.active')}}
                              @else
                                  {{__('messages.deactive')}}
                              @endif
                           </td>
                           <td>
                                 <a href="{{url('admin/editpaymentgateway/').'/'.$d->id}}" class="btn btn-primary">{{__('messages.Edit')}}</a>
                                 @if($d->is_active&&$d->is_active=='1')
                                    <a href="{{url('admin/changestatuspayment/').'/'.$d->id.'/0'}}" class="btn btn-primary">{{__('messages.Enable')}}</a>
                                 @else
                                   <a href="{{url('admin/changestatuspayment/').'/'.$d->id.'/1'}}" class="btn btn-secondary">{{__('messages.Disable')}}</a>
                                 @endif
                           </td>
                        </tr>
                        @endforeach
                        @endif
                     </tbody>
                  </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@stop