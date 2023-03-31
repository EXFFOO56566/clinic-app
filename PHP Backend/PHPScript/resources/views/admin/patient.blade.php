@extends('admin.layout')
@section('title')
 {{__('messages.Patient')}}
@stop
@section('content')
 <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>{{__('messages.Patient')}}</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">{{__('messages.Patient')}}</li>
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
                                            <th>{{__('messages.Email')}}</th>
                                            <th>{{__('messages.Phone No')}}</th>
                                            <th>{{__('messages.Action')}}</th>
                                        </tr>
                                    </thead> 
                                    <tbody>  
                                @if(count($data)>0)
                                        @foreach($data as $d)
                                            <tr>
                                                 <td>{{isset($d->id)?$d->id:""}}</td>
                                                 <td>{{isset($d->name)?$d->name:""}}</td>
                                                 <td>
                                                    
                                                        @if($d->profile_pic!="")
                                                            <img src="{{asset('public/upload/profile').'/'.$d->profile_pic}}" class="imgsize1 profilepic" />
                                                        @else
                                                            <img src="{{asset('public/upload/profile/profile.png')}}" class="imgsize1 profilepic" />
                                                        @endif
                                                </td>
                                                <td>{{isset($d->email)?$d->email:""}}</td>
                                                <td>{{isset($d->phone_no)?$d->phone_no:""}}</td>
                                                <td>
                                                   
                                                    <a href="" class="btn btn-danger">{{__('messages.Delete')}}</a>
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