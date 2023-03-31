@extends('admin.layout')
@section('title')
{{__('messages.Notification')}}
@stop
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.Notification')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.Notification')}}</li>
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
                @if(Session::has('message'))
                         <div class="col-sm-12">
                            <div class="alert  {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">{{ Session::get('message') }}
                               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                               </button>
                            </div>
                         </div>
                         @endif
                  <div>
                     <a href="{{url('admin/savenotification')}}" class="btn btn-primary" data-toggle="modal" data-target="#myModal">{{__('messages.Send Notification')}}</a>
                  </div>
                  <div class="table-responsive">
                  <table id="service" class="table  table-striped table-bordered">
                     <thead>
                        <tr>
                           <th>{{__('messages.Id')}}</th>
                           <th>{{__('messages.Messages')}}</th>
                        </tr>
                     </thead>
                     <tbody>                                        
                     </tbody>
                  </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">{{__('messages.Send Notification')}}
               </h5>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <form name="menu_form_category" action="{{url('admin/savenotification')}}" method="post">
                  {{csrf_field()}}
                  <div class="form-group">
                     <label>{{__('messages.Messages')}}</label>
                     <textarea class="form-control" name="message" required>
                     </textarea>
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-6">
                        <input id="payment-button" type="submit" class="btn btn-primary btn-md form-control" value="{{__('messages.Save')}}">
                     </div>
                     <div class="col-md-6">
                        @if(Session::get("is_demo")=='1')
                          <input type="button" onclick="disablebtn()" class="btn btn-secondary btn-md form-control" data-dismiss="modal" value="{{__('messages.Close')}}">
                        @else
                          <input type="button" class="btn btn-secondary btn-md form-control" data-dismiss="modal" value="{{__('messages.Close')}}">
                        @endif                        
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@stop