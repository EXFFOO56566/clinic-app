@extends('admin.layout')
@section('title')
 {{__('messages.Subscription')}}
@stop
@section('content')
 <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>{{__('messages.Subscription')}}</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">{{__('messages.Subscription')}}</li>
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
                                            <th>{{__('messages.Package Name')}}</th>
                                            <th>{{__('messages.Payment Type')}}</th>
                                            <th>{{__('messages.Status')}}</th>
                                            <th>{{__('messages.Action')}}</th>
                                        </tr>
                                    </thead> 
                                    <tbody>  
                                    @if(count($data)>0)
                                      @foreach($data as $d)
                                          <tr>
                                                 <td>{{isset($d->id)?$d->id:""}}</td>
                                                 <td>{{isset($d->name)?$d->name:""}}</td>
                                                 <td>{{isset($d->packagedata)?$d->packagedata->name:""}}</td>
                                                 <td>{{isset($d->paymentdata)?$d->paymentdata->gateway_name:""}}</td>
                                                 <td>
                                                    @if($d->status=='1')
                                                       {{__("messages.Receive")}}
                                                    @elseif($d->status=='2')
                                                    {{__("messages.Approve")}}
                                                    @elseif($d->status=='3')
                                                    {{__("messages.Cancel")}}
                                                    @elseif($d->status=='4')
                                                    {{__('messages.In progress')}}
                                                    @elseif($d->status=='5')
                                                    {{__('messages.Complete')}}
                                                    @elseif($d->status=='6')
                                                    {{__('messages.Refund')}}
                                                    @endif
                                                 </td>
                                                 <td>
                                                    @if($d->status=='1')
                                                      <a href="{{url('admin/changepackagestatus/2').'/'.$d->id}}" class="btn btn-primary">{{__('messages.Approve')}}</a>
                                                       <a href="{{url('admin/changepackagestatus/3').'/'.$d->id}}" class="btn btn-danger">{{__('messages.Cancel')}}</a>
                                                    @elseif($d->status=='2')
                                                        <a href="{{url('admin/changepackagestatus/4').'/'.$d->id}}" class="btn btn-primary">{{__('messages.In progress')}}</a>
                                                       <a href="{{url('admin/changepackagestatus/3').'/'.$d->id}}" class="btn btn-danger">{{__('messages.Cancel')}}</a>
                                                    @elseif($d->status=='4')
                                                    <a href="{{url('admin/changepackagestatus/5').'/'.$d->id}}" class="btn btn-primary">{{__('messages.Complete')}}</a>
                                                     
                                                     @elseif($d->status=='5')
                                                     <a href="#" class="btn btn-primary">{{__('messages.Complete')}}</a>
                                                      @elseif($d->status=='3')
                                                    <a href="{{url('admin/changepackagestatus/6').'/'.$d->id}}" class="btn btn-primary">{{__('messages.Refund')}}</a>
                                                     
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