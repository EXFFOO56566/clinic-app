@extends('admin.layout')
@section('title')
{{__('messages.Album Photos')}}
@stop
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1> {{__('messages.Album Photos')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.Album Photos')}}</li>
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
                  <div>
                     <meta name="csrf-token" content="{{ csrf_token() }}" />
                     <main role="main" class="container">
                        <div class="row">
                           <div class="col-md-6 col-sm-12">
                              <div id="drag-and-drop-zone" class="dm-uploader p-5">
                                 <h3 class="mb-5 mt-5 text-muted">{{__('messages.Drag & drop files here')}}</h3>
                                 <div class="btn btn-primary btn-block mb-5">
                                    <span>{{__('messages.Open the file Browser')}}</span>
                                    <input type="file" title='{{__("messages.Click to add Files")}}' />
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6 col-sm-12">
                              <div class="card h-100">
                                 <div class="card-header">
                                    {{__('messages.File List')}}
                                 </div>
                                 <input type="hidden" id="album_id" value="{{$album_id}}"/>
                                 <div id="imagediv"></div>
                                 <ul class="list-unstyled p-2 d-flex flex-column col" id="files">
                                    <li class="text-muted text-center empty">{{__('messages.No files uploaded')}}</li>
                                 </ul>
                                 <span id="filepath" class="is_hide"></span>
                              </div>
                           </div>
                        </div>
                     </main>
                  </div>
                  <div class="table-responsive">
                  <table id="service" class="table  table-striped table-bordered">
                     <thead>
                        <tr>
                           <th>{{__('messages.Id')}}</th>
                           <th>{{__('messages.Image')}}</th>
                           <th>{{__('messages.Action')}}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($data as $d)     
                        <tr>
                           <td>{{$d->id}}</td>
                           <td>
                              <img src="{{asset('public/upload/gallery').'/'.$d->image}}" class="imgsize1 departmentimg"/>
                           </td>
                           <td>
                              
                              <a href="javascript:deleterow('deletegalleryphoto','<?= $d->id ?>')" class="btn btn-danger">{{__('messages.Delete')}}</a>
                           </td>
                        </tr>
                        @endforeach
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