@extends('admin.layout')
@section('title')
{{__('messages.Save Album')}}
@stop
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.Save Album')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
                <li><a href="{{url('admin/gallery')}}">{{__('messages.Album')}}</a></li>
               <li class="active">{{__('messages.Save Album')}}</li>
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
                  <form action="{{url('admin/updatealbum')}}" method="post" novalidate="novalidate">
                     {{csrf_field()}}
                     <input type="hidden" name="id" id="id" value="{{$id}}"/>
                     <div class="form-group">
                        <label for="name" class=" form-control-label">
                        {{__('messages.Name')}}
                        <span class="reqfield" >*</span>
                        </label>
                        <input type="text" id="name" placeholder="{{__('messages.Enter').' '.__('messages.Name')}}" class="form-control" required name="name" value="{{ isset($data->name)?$data->name:''}}">
                     </div>
                     <div>
                        @if(Session::get("is_demo")=='1')
                           <button id="payment-button" onclick="disablebtn()"  type="button" class="btn btn-lg btn-info floatright">
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