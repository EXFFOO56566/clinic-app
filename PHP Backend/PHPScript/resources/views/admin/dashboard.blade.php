@extends('admin.layout')
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
      <a href="{{url('admin/appointment/0/0')}}"><div class="card-body">
         <div class="stat-widget-one">
            <div class="stat-icon dib"><i class="fa fa-ambulance  text-primary border-primary"></i></div>
            <div class="stat-content dib">
               <div class="stat-text">{{__('messages.Total')}}</div>
			   <div class="stat-text">{{__('messages.Appointment')}}</div>
               <div class="stat-digit">{{$totaldoctor}}</div>
            </div>
         </div>
      </div></a>
   </div>
</div>
<div class="col-xl-3 col-lg-6">
   <div class="card">
     <a href="{{url('admin/department')}}"> <div class="card-body">
         <div class="stat-widget-one">
            <div class="stat-icon dib"><i class="ti-layout-grid2  text-success border-success"></i></div>
            <div class="stat-content dib">
               <div class="stat-text">{{__('messages.Total')}}</div>
               <div class="stat-text">{{__('messages.Department')}}
               </div>
               <div class="stat-digit">{{$totaldepartment}}</div>
            </div>
         </div>
      </div></a>
   </div>
</div>
<div class="col-xl-3 col-lg-6">
   <div class="card">
      <a href="{{url('admin/service')}}"><div class="card-body">
         <div class="stat-widget-one">
            <div class="stat-icon dib"><i class="ti-book  text-warning border-warning"></i></div>
            <div class="stat-content dib">
               <div class="stat-text">{{__('messages.Total')}}</div>
               <div class="stat-text">{{__('messages.Facilities')}}</div>
               <div class="stat-digit">{{$totalservice}}</div>
            </div>
         </div>
      </div></a>
   </div>
</div>
<div class="col-xl-3 col-lg-6">
   <div class="card">
     <a href="{{url('admin/doctor')}}"> <div class="card-body">
         <div class="stat-widget-one">
            <div class="stat-icon dib"><i class="fa fa-user-md  text-danger border-danger"></i></div>
            <div class="stat-content dib">
               <div class="stat-text">{{__('messages.Total')}}</div>
               <div class="stat-text">{{__('messages.Doctors')}}</div>
               <div class="stat-digit">{{$totaldoctor}}</div>
            </div>
         </div>
      </div></a>
   </div>
</div>
<div class="col-md-12 flt">
   <div class="row">
      <div class="col-md-12 col-xl-6 col-12 flat-r">
         <div class="card">
            <div class="card-body">
               <h4 class="orderh4">
                  <i class="fa fa-ambulance" aria-hidden="true"></i>{{__('messages.Latest').' '.__('messages.Appointment')}} 
                  <a class="btn btn-primary btn-flat m-b-30 m-t-30 elec textorder" href="{{url('admin/appointment/0/0')}}">{{__('messages.Show All')}}</a>
               </h4>
               <div class="table-responsive dtdiv">
                  <table id="latestorderTable" class="table table-striped dttablewidth">
                     <thead>
                        <tr>
                           <th>{{__('messages.Id')}}</th>
                           <th>{{__('messages.Doctor').' '.__('messages.Name')}}</th>
                           <th>{{__('messages.Patient').' '.__('messages.Name')}}</th>
                           <th>{{__('messages.Department').' '.__('messages.Name')}}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if(count($leastappointment)>0)
                            @foreach($leastappointment as $d)
                                  <tr>
                                     <td>{{$d->id}}</td>
                                     <td>{{isset($d->doctors)?$d->doctors->name:""}}</td>
                                     <td>{{isset($d->name)?$d->name:""}}</td>
                                     <td>{{isset($d->department)?$d->department->name:""}}</td>
                                  </tr>
                            @endforeach
                        @endif
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-12 col-xl-6 col-12 flat-r">
         <div class="card">
            <div class="card-body">
               <h4 class="orderh4">
                  <i class="fa fa-comments-o" aria-hidden="true"></i>{{__('messages.Latest').' '.__('messages.Review')}}
                  <a class="btn btn-primary btn-flat m-b-30 m-t-30 elec textorder" href="{{url('admin/review')}}">{{__('messages.Show All')}}</a>
               </h4>
               <div class="table-responsive dtdiv">
                  <table id="myTablereview" class="table table-striped dttablewidth">
                     <thead>
                        <tr>
                           <th>{{__('messages.Id')}}</th>
                           <th>{{__('messages.Doctor').' '.__('messages.Name')}}</th>
                           <th>{{__('messages.Patient').' '.__('messages.Name')}}</th>
                           <th>{{__('messages.Ratting')}}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if(count($leastreview)>0)
                            @foreach($leastreview as $d)
                                <tr>
                                   <th>{{$d->id}}</th>
                                   <td>{{isset($d->doctors)?$d->doctors->name:""}}</td>
                                   <td>{{isset($d->users)?$d->users->name:""}}</td>
                                   <td>{{isset($d->ratting)?$d->ratting:""}}</td>
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
@stop
@section('footer')
<script type="text/javascript">adminchangelogin()</script>
@stop