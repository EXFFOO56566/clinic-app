@extends('admin.layout')
@section('title')
{{__('messages.Save Help Package')}}
@stop
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.Save Help Package')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li>
                  <a href="{{url('admin/package')}}">{{__('messages.Help Package')}}</a>
               </li>
               <li class="active">{{__('messages.Save Help Package')}}</li>
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
                  <form action="{{url('admin/updatepackage')}}" method="post" novalidate="novalidate">
                     {{csrf_field()}}
                     <input type="hidden" name="id" id="id" value="{{$package_id}}"/>
                     <div class="form-group">
                        <label for="name" class=" form-control-label">
                        {{__('messages.Department')}} 
                        <span class="reqfield" >*</span>
                        </label>
                        <select class="form-control" name="department_id" id="department_id" required>
                           <option value="">{{__('messages.Select Department')}}</option>
                           @foreach($department as $d) 
                              <option value="{{$d->id}}">{{$d->name}}</option>
                           @endforeach
                        </select>
                        @foreach($department as $d)

                        @endforeach
                     </div>
                     <div class="form-group">
                        <label for="name" class=" form-control-label">
                        {{__('messages.Name')}}
                        <span class="reqfield" >*</span>
                        </label>
                        <input type="text" id="name" placeholder="{{__('messages.Enter').' '.__('messages.Name')}}" class="form-control" required name="name" value="{{ isset($data->name)?$data->name:''}}">
                     </div>
                     <div class="form-group">
                        <label for="name" class=" form-control-label">
                        {{__('messages.Price')}}
                        <span class="reqfield" >*</span>
                        </label>
                        <input type="number" step="any" id="price" placeholder="{{__('messages.Enter').' '.__('messages.Price')}}" class="form-control" required name="price" value="{{ isset($data->price)?$data->price:''}}">
                     </div>
                    
                     <div class="form-group">
                        <label for="email" class=" form-control-label">
                        {{__('messages.Description')}}<span class="reqfield" >*</span>
                        </label>
                        <textarea required id="description" name="description" class="form-control">{{ isset($data->description)?$data->description:''}}</textarea>
                     </div>
                     <div>
                         @if(Session::get("is_demo")=='1')
                           <button id="payment-button" type="button" onclick="disablebtn()"  class="btn btn-lg btn-info floatright">
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