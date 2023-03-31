@extends('admin.layout')
@section('title')
{{__('messages.Appointment')}}
@stop @section('content')
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
                        <form>
                            <div class="form-group col-md-12 paddiv">
                                <div class="col-md-4">
                                    <label for="cc-payment" class="control-label mb-1">
                                        {{__('messages.Start Date')}}
                                    </label>
                                    <input id="start_date" name="start_date" type="text" class="form-control" required value="<?= isset($startdate)&&$startdate!=0?$startdate:""?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="cc-payment" class="control-label mb-1">
                                        {{__('messages.End Date')}}
                                    </label>
                                    <input id="end_date" name="end_date" type="text" class="form-control" required value="<?= isset($enddate)&&$enddate!=0?$enddate:""?>">
                                </div>
                                <div class="col-md-4 appbtn">
                                    <button type="button" onclick="searchappointment()" name="searchbydate" class="btn btn-primary">{{__('messages.Search')}}</button>
                                    <button type="button" name="reset" onclick="Resetappointment()" class="btn btn-primary">{{__('messages.Reset')}}</button>
                                </div>
                            </div>
                        </form>
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
                                                  <a href="javascript:void(0)" class="btn btn-primary">{{__('messages.Approve')}}</a>
                                                  @elseif($d->status==6)
                                                  <a href="javascript:void(0)" class="btn btn-primary">{{__('messages.Reject')}}</a>
                                                  @elseif($d->status==0)
                                                  <a href="javascript:void(0)" class="btn btn-primary">{{__('messages.Absent')}}</a>
                                                  @elseif($d->status==1)
                                                  <a href="javascript:void(0)" class="btn btn-primary">{{__('messages.Receive')}}</a>
                                                  @elseif($d->status==2)
                                                  <a href="javascript:void(0)" class="btn btn-primary">{{__('messages.Reschedule')}}</a>
                                                  @elseif($d->status==4)
                                                  <a href="javascript:void(0)" class="btn btn-primary">{{__('messages.Complete')}}</a>
                                                  @elseif($d->status==5)
                                                  <a href="javascript:void(0)" class="btn btn-primary">{{__('messages.Refer Doctor')}}</a>
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
