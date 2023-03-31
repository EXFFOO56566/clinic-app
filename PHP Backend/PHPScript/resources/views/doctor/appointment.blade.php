@extends('doctor.layout')
@section('title')
{{__('messages.Appointment')}}
@stop
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.Appointment')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.Appointment')}}</li>
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
                  @if ($errors->any())
                  <div class="alert alert-danger">
                     <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                     </ul>
                  </div>
                  @endif  
                  <div class="table-responsive">
                      <table id="service" class="table table table-striped table-bordered">
                     <thead>
                        <tr>
                           <th>{{__('messages.Id')}}</th>
                           <th>{{__('messages.Department Name')}}</th>
                           <th>{{__('messages.Doctor Name')}}</th>
                           <th>{{__('messages.Service Name')}}</th>
                           <th>{{__('messages.Patient Name')}}</th>
                           <th>{{__('messages.Timing')}}</th>
                           <th>{{__('messages.Messages')}}</th>
                           <th>{{__('messages.Status')}}</th>
                           <th>{{__('messages.Action')}}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if(count($data)>0)
                        @foreach($data as $d)
                        <tr>
                           <td>{{$d->id}}</td>
                           <td>{{isset($d->department)?$d->department->name:""}}</td>
                           <td>{{isset($d->doctors)?$d->doctors->name:""}}</td>
                           <td>{{isset($d->services)?$d->services->name:""}}</td>
                           <td>{{$d->name}}</td>
                           <td>{{$d->date.' '.$d->time}}</td>
                           <td>{{$d->messages}}</td>
                           <td>
                              @if($d->status==3)
                              <a href="#" class="btn btn-secondary mb-1">{{__('messages.Approve')}}</a>
                              @elseif($d->status==6)
                              <a href="#" class="btn btn-secondary mb-1">{{__('messages.Reject')}}</a>
                              @elseif($d->status==0)
                              <a href="#" class="btn btn-secondary mb-1">{{__('messages.Absent')}}</a>
                              @elseif($d->status==1)
                              <a href="#" class="btn btn-secondary mb-1">{{__('messages.Receive')}}</a>
                              @elseif($d->status==2)
                              <a href="#" class="btn btn-secondary mb-1">{{__('messages.Reschedule')}}</a>
                              @elseif($d->status==4)
                              <a href="#" class="btn btn-secondary mb-1">{{__('messages.Complete')}}</a>
                              @elseif($d->status==5)
                              <a href="#" class="btn btn-secondary mb-1">{{__('messages.Refer Doctor')}}</a>
                              @endif
                           </br>
                              @if($d->refer_to )
                                  <b>{{__('messages.Refer To')}} </b> :- {{isset($d->referdocto)?$d->referdocto->name:""}}
                              @endif
                              @if($d->refer_by)
                                 <b>{{__('messages.Refer By')}} </b> :- {{isset($d->referdocby)?$d->referdocby->name:""}}
                              @endif
                           </td>
                           <td>
                               @if($d->status==3)
                              <a href="#"  class="btn btn-warning"  class="btn btn-warning" data-toggle="modal" data-target="#referrModal" onclick="getdoctorapp('{{$d->id}}')" >{{__('messages.Refer Doctor')}}</a>
                              <a href="{{url('doctor/orderstatus/4/').'/'.$d->id}}" class="btn btn-primary">{{__('messages.Complete')}}</a>

                              @elseif($d->status==6)
                             

                              @elseif($d->status==0)
                                 <a href="#" class="btn btn-primary">{{__('messages.Absent')}}</a>

                              @elseif($d->status==1)
                                 <a href="{{url('doctor/orderstatus/3/').'/'.$d->id}}" class="btn btn-primary">{{__('messages.Approve')}}</a>
                                 <a href="" onclick="storeappointmentresc('{{$d->id}}')" class="btn btn-warning" data-toggle="modal" data-target="#mediumModal">{{__('messages.Reschedule')}}</a>
                                 <a href="{{url('doctor/orderstatus/6/').'/'.$d->id}}" class="btn btn-danger">{{__('messages.Reject')}}</a>

                              @elseif($d->status==2)
                                  <a href="{{url('doctor/orderstatus/4/').'/'.$d->id}}" class="btn btn-primary">{{__('messages.Complete')}}</a>


                              @elseif($d->status==4)
                              <a href="#" class="btn btn-primary">{{__('messages.Complete')}}</a>

                              @elseif($d->status==5)
                                 

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