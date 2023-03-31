@extends('doctor.layout')
@section('title')
{{__('messages.Review')}}
@stop
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.Review')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.Review')}}</li>
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
                  <table id="service" class="table   table-striped table-bordered">
                     <thead>
                        <tr>
                           <th>{{__('messages.Id')}}</th>
                           <th>{{__('messages.Doctor Name')}}</th>
                           <th>{{__('messages.Patient Name')}}</th>
                           <th>{{__('messages.Review')}}</th>
                           <th>{{__('messages.Ratting')}}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if(count($data)>0)
                            @foreach($data as $d)
                            <tr>
                               <td>{{$d->id}}</td>
                               <td>{{isset($d->doctors)?$d->doctors->name:""}}</td>
                               <td>{{isset($d->users)?$d->users->name:""}}</td>
                               <td>{{$d->review}}</td>
                               <td>{{$d->ratting}}</td>
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