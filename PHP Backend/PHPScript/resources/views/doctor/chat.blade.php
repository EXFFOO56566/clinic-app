@extends("doctor.layout")
@section("title")
{{__('messages.Chat')}} 
@stop
@section("meta_title")
@if(Session::get("is_rtl")==""||Session::get("is_rtl")=='1')
<link rel="stylesheet" href="{{asset('public/front/css/app.min.css?v=25')}}">
@else
<link rel="stylesheet" href="{{asset('public/front/css/app.min-rtl.css')}}">
@endif
<input type="hidden" id="needtoken" value="1"/>
 <meta name="csrf-token" content="{{ csrf_token() }}">
<style type="text/css">
    a.contacts-link {
        display: flex;
        align-items: center;
        flex-grow: 1;
        max-width: 100%;
        border-radius: 0.25rem;
        transition: all .3s ease;
        padding: 15px;
        background: #e7d045;
        border: 4px solid #294b65;
        margin-bottom: 10px;
    }
    .contacts-content {
        padding-left: 0.875rem;
        transition: all .4s;
        overflow: hidden;
        max-width: 100%;
        flex: 1 1 auto;
    }
</style>
@stop
@section("content")
<div class="chats-tab-open">
   <div class="container">
      <div class="main-layout" style="margin-top: 4px;">
         <aside class="sidebar">
            <div class="tab-content">
               <div class="tab-pane active" id="chats-content">
                  <div class="d-flex flex-column h-100">
                     <div class="hide-scrollbar h-100" id="chatContactsList">
                        <div class="sidebar-header sticky-top p-2">
                           <div class="d-flex justify-content-between align-items-center">
                              <h5 class="font-weight-semibold mb-0">Chats</h5>
                              <ul class="nav flex-nowrap">
                                 <li class="nav-item list-inline-item mr-0">
                                    <div class="dropdown">
                                       <a class="nav-link text-muted px-1" href="#" role="button" title="Details" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                          </svg>
                                       </a>
                                       <div class="dropdown-menu dropdown-menu-right">
                                          <a class="dropdown-item" href="#" role="button" data-toggle="modal" data-target="#chat_new" onclick="chatnewlisthide()">New Chat</a>
                                       </div>
                                    </div>
                                 </li>
                              </ul>
                           </div>
                           <div class="sidebar-sub-header">
                              <form class="form-inline">
                                 <div class="input-group">
                                    <input type="text" class="form-control search border-right-0 transparent-bg pr-0" placeholder="Search users...">
                                    <div class="input-group-append">
                                       <div class="input-group-text transparent-bg border-left-0" role="button">
                                          <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                          </svg>
                                       </div>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                        <ul class="contacts-list" id="userchatlist" data-chat-list="" style="padding: 15px;list-style: none;">
                          
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="tab-pane" id="calls-content">
                  <div class="d-flex flex-column h-100">
                     <div class="hide-scrollbar h-100" id="callContactsList">
                        <div class="sidebar-header sticky-top p-2">
                           <div class="d-flex justify-content-between align-items-center">
                              <ul class="nav flex-nowrap">
                                 <li class="nav-item list-inline-item mr-0">
                                    <div class="dropdown">
                                       <div class="dropdown-menu dropdown-menu-right">
                                          <a class="dropdown-item" href="#" role="button" data-toggle="modal" data-target="#chat_new" onclick="chatnewlisthide()">New Chat</a>
                                       </div>
                                    </div>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </aside>
         <main class="main main-visible">
            <div class="chats">
               <div class="chat-body">
                <div id="chat_1">
                    <div style="padding: 205px;">
                         <img src="{{Session::get('profile_pic')}}" style="width: 100px;border-radius: 50px;">
                         <h5>{{isset(Auth::user()->name)?Auth::user()->name:""}}</h5>
                    </div>
                </div>
                <div id="chat_2" style="display: none">
                      <div class="chat-header">
                         <button class="btn btn-secondary btn-icon btn-minimal btn-sm text-muted d-xl-none" type="button" data-close="">
                            <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                         </button>
                         <div class="media chat-name align-items-center text-truncate">
                            <div class="avatar avatar-online d-none d-sm-inline-block mr-3" id="receiverclassstatus">
                               <img src="img/p2.jpg" alt="" id="receiverimg">
                            </div>
                            <div class="align-self-center">
                               <p id="token" style="display: none"></p>
                               <input type="hidden" name="currentchannelid" id="currentchannelid" value="">
                               <input type="hidden" name="receiveruid" id="receiveruid" value="{{Auth::user()->user_sid}}">
                               <input type="hidden" id="created_at" value="0"/>
                               <h6 class="text-truncate mb-0" id="receivername">Catherine Richardson</h6>
                               <small class="text-muted" id="receiverstatus">Online</small>
                            </div>
                         </div>
                         <ul class="nav flex-nowrap">
                         </ul>
                      </div>
                     
                      <div class="chat-content p-2" id="bodymessage" style="height: 370px;" >
                        
                     <div class="chat-finished" id="chat-finished"></div>
                  </div>
                  <div class="chat-footer">
                     <div class="form-row">
                        <div class="col">
                           <div class="input-group">
                              <div class="input-group-prepend mr-sm-2 mr-1">
                                 <label for="file-upload">
                                    <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <input type="file" id="file-upload">
                                 </label>
                              </div>
                              <div class="input-group-prepend mr-sm-2 mr-1">
                                 <button class="btn btn-secondary btn-icon btn-minimal btn-sm text-muted text-muted" type="button" id="emoji-button">
                                    <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                 </button>
                              </div>
                              <input type="text" id="sendmsg" name="sendmsg" class="form-control transparent-bg border-0 no-resize hide-scrollbar" placeholder="Write your message...">
                             
                           </div>
                        </div>
                        <div class="col-auto">
                           <div class="btn btn-primary btn-icon rounded-circle text-light mb-1" role="button" onclick="createchannel()">
                              <svg class="hw-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                              </svg>
                           </div>
                        </div>
                     </div>
                  </div>
                </div>                
               </div>
            </div>
         </main>
      </div>
   </div>
</div>

<input type="hidden" id="currenttime"/>
    <div class="mask"></div>
    <input type="hidden" id="path_url" value="{{url('/')}}">
    <input type="hidden" id="totalchat" value="0">
    <input type="hidden" id="mycurrentoken" >
    <input type="hidden" id="timezone"/>
    <input type="hidden" id="tokenflag" value="0">
    <input type="hidden" id="deletemsg" value="{{__('messages.Delete')}}">
    <input type="hidden" id="typingmsg" value="{{__('messages.Typing')}}">
   
    <input type="hidden" id="currentprofileimage" value="{{Session::get('profile_pic')}}">
@stop

@section("footer")

<script type="text/javascript" src="{{asset('public/js/chatdata.js?v=mkl')}}"></script>
<script type="text/javascript">
    doctorchangechat();
    getallusers();
</script>
@stop