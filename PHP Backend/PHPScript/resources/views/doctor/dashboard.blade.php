@extends('doctor.layout')
@section('title')
{{__('messages.Dashboard')}}
@stop
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.Dashboard')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.Dashboard')}}</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="content mt-3">
<div class="col-xl-3 col-lg-6">
   <div class="card">
      <div class="card-body">
         <div class="stat-widget-one">
            <div class="stat-icon dib"><i class="fa fa-ambulance  text-primary border-primary"></i></div>
            <div class="stat-content dib">
               <div class="stat-text">{{__('messages.Total')}}</div>
			   <div class="stat-text">{{__('messages.Appointment')}}</div>
               <div class="stat-digit">{{$totalappointment}}</div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="col-xl-3 col-lg-6">
   <div class="card">
      <div class="card-body">
         <div class="stat-widget-one">
            <div class="stat-icon dib"><i class="ti-layout-grid2  text-success border-success"></i></div>
            <div class="stat-content dib">
               <div class="stat-text">{{__('messages.Complete')}}</div>
                <div class="stat-text">{{__('messages.Appointment')}}</div>
               <div class="stat-digit">{{$completeappointment}}</div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="col-xl-3 col-lg-6">
   <div class="card">
      <div class="card-body">
         <div class="stat-widget-one">
            <div class="stat-icon dib"><i class="ti-book  text-warning border-warning"></i></div>
            <div class="stat-content dib">
               <div class="stat-text">{{__('messages.Pending')}}</div>
               <div class="stat-text"> {{__('messages.Appointment')}}</div>
               <div class="stat-digit">{{$pendingappointement}}</div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="col-xl-3 col-lg-6">
   <div class="card">
      <div class="card-body">
         <div class="stat-widget-one">
            <div class="stat-icon dib"><i class="fa fa-star  text-danger border-danger"></i></div>
            <div class="stat-content dib">
               <div class="stat-text">{{__('messages.Total')}}</div>
               <div class="stat-text">{{__('messages.Review')}}</div>
               <div class="stat-digit">{{$totalreview}}</div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="col-md-12 flt">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-body">
               <h4 class="orderh4">
                  <i class="fa fa-ambulance" aria-hidden="true"></i>{{__('messages.Upcoming Appointment')}}
                  <a class="btn btn-primary btn-flat m-b-30 m-t-30 elec textorder" href="{{url('doctor/appointment')}}">{{__('messages.Show All')}}</a>
               </h4>
               <div class="table-responsive dtdiv">
                  <table id="latestorderTable" class="table table-striped dttablewidth">
                     <thead>
                        <tr>
                           <th>{{__('messages.Id')}}</th>
                           <th>{{__('messages.Patient').' '.__('messages.Name')}}</th>
                           <th>{{__('messages.Department').' '.__('messages.Name')}}</th>
                           <th>{{__('messages.Service Name')}}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if(count($upcomingappoint)>0)
                           @foreach($upcomingappoint as $u)
                               <tr>
                                   <td>{{$u->id}}</td>
                                   <td>{{$u->name}}</td>
                                   <td>{{$u->department->name}}</td>
                                   <td>{{$u->services->name}}</td>
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
@section('footer')
<script type="text/javascript">doctorchangechat()</script>
@stop