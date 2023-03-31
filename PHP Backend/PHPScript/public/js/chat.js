function deletechatmsg($msg_id,$channel_id,type,i){
	$("#newmsg"+i).remove(); 
	$.ajax({
				url: $("#siteurl").val()+"/deletechatmsg"+"/"+$msg_id+"/"+$channel_id+"/"+type+'/'+i,
				method:"get",
				success: function( data ) {              
				}
	});
}

 if($("#firstuser").length){
       createnewcontact($("#firstuser").val(),0);
 }else{
       createnewcontact($("#currentchatid").val(),0)
 }
 var i=parseInt($("#messagescount").val())-parseInt(1);
 if($("#newmsg"+i).length){
    $('html, body').animate({ scrollTop: $("#newmsg"+i).offset().top }, 'slow');
    document.getElementById("newmsg"+i).scrollIntoView();
 }

function updatechatmsg($msg_id,$channel_id,$i){
    $.ajax({
				url: $("#siteurl").val()+"/getmessagebody"+"/"+$msg_id+"/"+$channel_id,
				method:"get",
				success: function( data ) {  
				 $(".emoji-wysiwyg-editor").html(data);
					 $("#msg_id").val($msg_id);
					 $("#update_channel_id").val($channel_id);
					 $("#updatei").val($i);
				}
	});
}

function newcontactsearch() {
	var input, filter, ul, li, a, i, txtValue;
	input = document.getElementById("myInput");
	filter = input.value.toUpperCase();
	ul = document.getElementById("myUL");
	li = ul.getElementsByTagName("li");
	for (i = 0; i < li.length; i++) {
		a = li[i].getElementsByTagName("a")[0];
		txtValue = a.textContent || a.innerText;
		if (txtValue.toUpperCase().indexOf(filter) > -1) {
			li[i].style.display = "";
		} else {
			li[i].style.display = "none";
		}
	}
}

function newcontactsearch1() {
	var input, filter, ul, li, a, i, txtValue;
	input = document.getElementById("myInput1");
	filter = input.value.toUpperCase();
	ul = document.getElementById("myUL1");
	li = ul.getElementsByTagName("li");
	for (i = 0; i < li.length; i++) {
		a = li[i].getElementsByTagName("h6")[0];
		txtValue = a.textContent || a.innerText;
		if (txtValue.toUpperCase().indexOf(filter) > -1) {
			li[i].style.display = "";
		} else {
			li[i].style.display = "none";
		}
	}
}

function createnewcontact(id,iuser){
   
					 if(id!=""){
					 		  $.ajax({
									  url: $("#siteurl").val()+"/getchannelmessage"+"/"+$("#channel_sid").val()+"/"+id,
									  method:"get",
									 
									  success: function( data ) {
									  	// $("#currentid").val(iuser);
										 document.getElementById("messages").innerHTML=data;
										  var i=parseInt($("#messagescount").val())-parseInt(1);
										  console.log(i);
										  if($("#newmsg"+i).length){
										  	 $('html, body').animate({ scrollTop: $("#newmsg"+i).offset().top }, 'slow');
											document.getElementById("newmsg"+i).scrollIntoView();
										  //$("#newmsg"+i).focus();
										
											
										  }
										                
									  }
								});
					 }
					   generatetoken();     
}
function generatetoken(){
	$(function() {
		var $chatWindow = $('#messages');
		var chatClient;
		var generalChannel;
		var username;
		var identity;
		var token;
		function print(infoMessage, asHtml) {
		  var $msg = $('<div class="info">');
		  if (asHtml) {
			$msg.html(infoMessage);
		  } else {
			$msg.text(infoMessage);
		  }
		  $chatWindow.append($msg);
		}
		function printMessage(fromUser, message,createdate,type,sid) {
			var countmsg=$("#messagescount").val();
		    var $user = $('<span class="username">').text(fromUser + ':');
		    var currentuser=$("#currentid").val();
		  if (fromUser === username) {
				   $user.addClass('me');
				   var classcss="self";
				   var imguser=$("#authprofile1").val();
				   var receivername=document.getElementById("currentusername").innerHTML;
				   $.ajax({
						  url: $("#siteurl").val()+"/sendnotification",
						  method:"get",
						  data:{ sendname:username,name:receivername,channelid:$("#channel_sid").val(),message:message,type:type,date:createdate,currentid:currentuser},
						  success: function( data ) {
							 console.log(data);
						  }
					});
					var newmsg="";
					var channel_sid=$("#channel_sid").val();
					if(type=='image'||type=="video"){
						newmsg='<div class="dropdown"><a class="text-muted" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg class="hw-18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg></a><div class="dropdown-menu"><a class="dropdown-item d-flex align-items-center text-danger" href="javascript:deletechatmsg(\''+sid+'\',\''+channel_sid+'\',\''+type+'\',\''+countmsg+'\')"><svg class="hw-18 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg><span>delete</span></a></div>';
					}else{
						newmsg='<div class="dropdown"><a class="text-muted" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg class="hw-18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg></a><div class="dropdown-menu"><a class="dropdown-item d-flex align-items-center" href="javascript:updatechatmsg(\''+sid+'\',\''+channel_sid+'\',\''+countmsg+'\')"><svg class="hw-18 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg><span>Edit</span></a><a class="dropdown-item d-flex align-items-center text-danger" href="javascript:deletechatmsg(\''+sid+'\',\''+channel_sid+'\',\''+type+'\',\''+countmsg+'\')"><svg class="hw-18 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg><span>delete</span></a></div>';
					}
			}else{
					var classcss="";
					var imguser=$("#chatuserimage").attr('src');
					var newmsg="";
					console.log(generalChannel.getUnconsumedMessagesCount());
			}
			var date1=new Date(createdate);
			createdate=date1.toTimeString(); 
			var str=createdate.split(" ");
			var newstr=str[0].split(":");
			 var pr="";
			if(newstr[0]>12){
              var pr="PM";
			}else{
			  var pr="AM";
			}
			createdate=newstr[0]+":"+newstr[1]+" "+pr;
			if(type=='image'){
					var path=$("#siteurl").val()+'/public/upload/chat'+'/'+message;
					document.getElementById("msg"+currentuser).innerHTML="image";
					var $message = '<div class="message '+classcss+'" id="newmsg'+countmsg+'"><div class="message-wrapper"><div class="message-content imgmsg"><img src="'+path+'"  style="width:250px;height:250px" class="'+classcss+'"/></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="'+imguser+'"></div><span class="message-date">'+createdate+'</span>'+newmsg+'</div></div>';
			}else if(type=='video'){
					var path=$("#siteurl").val()+'/public/upload/chat'+'/'+message;
					document.getElementById("msg"+currentuser).innerHTML="video";
					var $message = '<div class="message '+classcss+'" id="newmsg'+countmsg+'"><div class="message-wrapper"><div class="message-content imgmsg"><video width="320" height="240" controls><source src="'+path+'" type="video/mp4" class="'+classcss+'"></video></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="'+imguser+'"></div><span class="message-date">'+createdate+'</span>'+newmsg+'</div></div></div>';
			}else{
					document.getElementById("msg"+currentuser).innerHTML=message;
					var $message='<div class="message '+classcss+'" id="newmsg'+countmsg+'"><div class="message-wrapper"><div class="message-content"><span id="body'+countmsg+'">'+message+'</span></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="'+imguser+'"></div><span class="message-date">'+createdate+'</span>'+newmsg+'</div></div></div>';
			}
			document.getElementById("time"+currentuser).innerHTML=createdate;
			$('#messages').append($message);
			$("#messagescount").val(parseInt(countmsg)+parseInt(1));
			document.getElementById("newmsg"+countmsg).scrollIntoView();
		}  
		$.getJSON($("#siteurl").val()+'/token', {
			  device: 'browser'
			}, function(data) {
				// $("#loader").show();
			     Twilio.Chat.Client.create(data.token).then(client => {
				     chatClient = client;
				     token=data.token;
				      username = data.identity;
				     identity=data.identity;
				     $("#usertoken").val(data.token);
				       j=0;
				      

				      var listofdate=[];
		   chatClient.getUserChannelDescriptors().then(function(paginator) {
							for (i=0; i<paginator.items.length; i++) {
							         var channel = paginator.items[i];

							         chatClient.getChannelByUniqueName(channel.uniqueName).then(function(channel) {							
							         var countclass="";
										   				var countval="";
										   				 	    
								        channel.getMembers().then(function(members){
										   	for (k=0; k<members.length; k++) {
										   		if(username==members[k].identity){
										   			channel.getUnconsumedMessagesCount().then(function(val){
										   					  
											  	 	            if(val!=null&&val!=0&&$("#channelid").val()!=channel.uniqueName){
													  	 	    	countval=val;
													  	 	    	countclass="numbercount";
													  	 	    }
													  	 	      if(val!=null&&val!=0&&$("#channelid").val()!=channel.uniqueName){
									  	 	    					   if($("#messagecount"+channel.uniqueName).length){
									  	 	    					   	   $("#messagecount"+channel.uniqueName).html(val);
									  	 	    	                       $("#messagecount"+channel.uniqueName).addClass("numbercount");
									  	 	    					   }
									  	 	    				   }else{
									  	 	    				 	     if($("#messagecount"+channel.uniqueName).length){
									  	 	    				 	     	 $("#messagecount"+channel.uniqueName).removeClass("numbercount");
									  	 	    				 	     }
										  	 	                 }
													  	 	    j++;
								  	                    });	
										   		}
										   		if(username!=members[k].identity){
										   			members[k].getUser().then(user => {
										   				var str=user.identity;
										   				 var txt="";
														if(user.attributes.id==$("#currentchatid").val()){
															txt=txt+'<li class="contacts-item active" id="list'+user.attributes.id+'">';
															
														}else{
															txt=txt+'<li class="contacts-item" id="list'+user.attributes.id+'">';
															
														}
														
														var url=$("#own_url").val()+"/chat/"+user.attributes.id;

														if(user.online==true)
														{
															var status="online";
															
															$("#cssuserstatus"+user.attributes.id).addClass("avatar-online");
															$("#cssuserstatus"+user.attributes.id).removeClass("avatar-offline");
														}else{
															var status="offline";
															
															$("#cssuserstatus"+user.attributes.id).addClass("avatar-offline");
															$("#cssuserstatus"+user.attributes.id).removeClass("avatar-online");
														}
														txt=txt+'<a class="contacts-link" style="text-decoration: none;" href="'+url+'"><div class="avatar avatar-'+status+'" id="status'+user.attributes.id+'"><img src="'+$("#siteurl").val()+'/public/upload'+'/'+user.attributes.profile+'" alt=""></div><div class="contacts-content"><div class="contacts-info"><h6 class="chat-name text-truncate">'+user.attributes.name+'</h6><div class="chat-time" id="time'+user.attributes.id+'"></div></div><input type="hidden" id="channel_list'+i+'" value="'+channel.uniqueName+'"><div class="contacts-texts"><p class="text-truncate" id="msg'+user.attributes.id+'" >'+channel.attributes.body+'</p><div style="float: right;"><span id="messagecount'+channel.uniqueName+'" class="'+countclass+'" style="color:#19bd17" >'+countval+'</span></div></div></div></a></li>';													
														
														if($("#list"+user.attributes.id).length){
															//	$("#currentstatus"+user.attributes.id).html(status);
																if(status=="online"){
																	$("#status"+user.attributes.id).addClass('avatar-online');
																	$("#status"+user.attributes.id).removeClass('avatar-offline');
																}else{
																	$("#status"+user.attributes.id).addClass('avatar-offline');
																	$("#status"+user.attributes.id).removeClass('avatar-online');
																}
																
																
														}else{
															$("#myUL1").append(txt);

														}
														listofdate.push(channel.attributes.date);
														if(channel.attributes.date!=""){
														   			   var today=new Date();
																	   var channeldate=new Date(channel.attributes.date);
																	   if(today.toLocaleDateString()==channeldate.toLocaleDateString()){
																		 	var date1=new Date(channel.attributes.date);
																			createdate=date1.toTimeString(); 
																			var str=createdate.split(" ");
																			var newstr=str[0].split(":");
																			 var pr="";
																			if(newstr[0]>12){
																              var pr="PM";
																			}else{
																			  var pr="AM";
																			}
																		    $("#time"+user.attributes.id).html(createdate=newstr[0]+":"+newstr[1]+" "+pr);
														              }
														              else{
														              	        const yesterday = new Date(today)
																				yesterday.setDate(yesterday.getDate() - 1)
																				if(yesterday.toLocaleDateString()==channeldate.toLocaleDateString()){
														   	                       $("#time"+user.attributes.id).html("Yerterday");
														   	                    }else{
														   	                    	 $("#time"+user.attributes.id).html(channeldate.toLocaleDateString());
														   	                    }
														              }
														              console.log(listofdate);

														   }
														    
										   			});
										   		}
										   	}

							  	 	     
							  	        });

							    }).catch(function() { });
							}
							 
							
				       }); 
				     if($("#currentchatid").val()!=0){
				     	chatClient.getSubscribedChannels().then(createOrJoinGeneralChannel);
				     }else{
				     	setupChannel();
				     }
				     
				    
				    
			  }).catch(error => {
				console.error(error);
			  });
		});
	   function createOrJoinGeneralChannel() {   
			 if(document.getElementById("channelid")){
				chatClient.getChannelByUniqueName($("#channelid").val())
							.then(function(channel) {
							   generalChannel = channel;
							   setupChannel();
							  
							}).catch(function() {
							    chatClient.createChannel({
								       uniqueName: "general",
								       friendlyName: "general",
								       isPrivate: true
							    }).then(function(channel) {
								       generalChannel = channel;
								       setupChannel();
							    }).catch(function(channel) {
								
							  });
				});
			}else{
					chatClient.getChannelByUniqueName("general")
							.then(function(channel) {
							  generalChannel = channel;
							  setupChannel();
						
					}).catch(function() {
							  chatClient.createChannel({
								uniqueName: "general",
								friendlyName: "general"

							  }).then(function(channel) {
								generalChannel = channel;
								setupChannel();
							  }).catch(function(channel) {
							  });
							});
			}
	    }

	 function setupChannel() {
	 	 if(generalChannel){
	 	 	 if(generalChannel.state.status!="joined"){
			     generalChannel.join().then(function(channel) {
			  });
		    }
		    
		      generalChannel.on('memberUpdated', function(event) { 
			       generalChannel.setAllMessagesConsumed();
			  });
			  generalChannel.on('messageAdded', function(message) {
				          
				  printMessage(message.author, message.body,message.attributes.date,message.attributes.type,message.sid);
			  });
			  generalChannel.on('memberJoined', function(member) {
			      console.log(member.identity + 'has joined the channel.');
			  });
			  generalChannel.on('typingStarted', function(member) {
				document.getElementById("currentstatus"+$("#currentiusers").val()).innerHTML="Typing";
			  });
			  generalChannel.on('typingEnded', function(member) {
				  document.getElementById("currentstatus"+$("#currentiusers").val()).innerHTML="";
				  console.log(member.identity + 'is currently stop.');
			  });
			  generalChannel.getMessages().then(function (messages) {
			  	  if(messages.items.length > 10) {
				        var someMessageIndex = messages.items[4].index;
				        generalChannel.updateLastConsumedMessageIndex(someMessageIndex)
				          .then(function () {
				           //generalChannel.setAllMessagesConsumed();  
				        });
		           }
		           var last_message_index = messages.items[0].index;
		           generalChannel.updateLastConsumedMessageIndex(last_message_index);
             });
	 	 }
		 $("#loader").hide();
		   $(document).ready(function(){
			       setInterval(function(){
				   j=0;
				  
				  
				   chatClient.getUserChannelDescriptors().then(function(paginator) {
							for (i=0; i<paginator.items.length; i++) {
							         var channel = paginator.items[i];
							      //   console.log(channel.state.status);
							         chatClient.getChannelByUniqueName(channel.uniqueName).then(function(channel) {							
							         var countclass="";
										   				var countval="";

										   					    
								        channel.getMembers().then(function(members){
										   	for (k=0; k<members.length; k++) {
										   		if(username==members[k].identity){
										   			channel.getUnconsumedMessagesCount().then(function(val){
										   					  
											  	 	            if(val!=null&&val!=0&&$("#channelid").val()!=channel.uniqueName){
													  	 	    	countval=val;
													  	 	    	countclass="numbercount";
													  	 	    }
													  	 	      if(val!=null&&val!=0&&$("#channelid").val()!=channel.uniqueName){
									  	 	    					   if($("#messagecount"+channel.uniqueName).length){
									  	 	    					   	   $("#messagecount"+channel.uniqueName).html(val);
									  	 	    	                       $("#messagecount"+channel.uniqueName).addClass("numbercount");
									  	 	    					   }
									  	 	    				   }else{
									  	 	    				 	     if($("#messagecount"+channel.uniqueName).length){
									  	 	    				 	     	 $("#messagecount"+channel.uniqueName).removeClass("numbercount");
									  	 	    				 	     }
										  	 	                 }
													  	 	    j++;
								  	                    });	
										   		}
										   		if(username!=members[k].identity){
										   			members[k].getUser().then(user => {
										   				  
										   				var str=user.identity;
										   				 var txt="";
														if(user.attributes.id==$("#currentchatid").val()){
															txt=txt+'<li class="contacts-item active" id="list'+user.attributes.id+'">';
															
														}else{
															txt=txt+'<li class="contacts-item" id="list'+user.attributes.id+'">';
															
														}
														
														var url=$("#own_url").val()+"/chat/"+user.attributes.id;

														if(user.online==true)
														{
															var status="online";
															
															$("#cssuserstatus"+user.attributes.id).addClass("avatar-online");
															$("#cssuserstatus"+user.attributes.id).removeClass("avatar-offline");
														}else{
															var status="offline";
															
															$("#cssuserstatus"+user.attributes.id).addClass("avatar-offline");
															$("#cssuserstatus"+user.attributes.id).removeClass("avatar-online");
														}
														txt=txt+'<a class="contacts-link" style="text-decoration: none;" href="'+url+'"><div class="avatar avatar-'+status+'" id="status'+user.attributes.name+'"><img src="'+$("#siteurl").val()+'/public/upload'+'/'+user.attributes.profile+'" alt=""></div><div class="contacts-content"><div class="contacts-info"><h6 class="chat-name text-truncate">'+user.attributes.name+'</h6><div class="chat-time" id="time'+user.attributes.id+'"></div></div><input type="hidden" id="channel_list'+i+'" value="'+channel.uniqueName+'"><div class="contacts-texts"><p class="text-truncate" id="msg'+user.attributes.id+'" >'+channel.attributes.body+'</p><div style="float: right;"><span id="messagecount'+channel.uniqueName+'" style="color:#19bd17" >'+countval+'</span></div></div></div></a></li>';													
														
														if($("#list"+user.attributes.id).length){
																//$("#currentstatus"+user.attributes.id).html(status);
																if(status=="online"){
																	$("#status"+user.attributes.id).addClass('avatar-online');
																	$("#status"+user.attributes.id).removeClass('avatar-offline');
																}else{
																	$("#status"+user.attributes.id).addClass('avatar-offline');
																	$("#status"+user.attributes.id).removeClass('avatar-online');
																}
														}else{
															$("#myUL1").append(txt);
														}
															if(channel.attributes.date!=""){
														   			   var today=new Date();
																	   var channeldate=new Date(channel.attributes.date);
																	   if(today.toLocaleDateString()==channeldate.toLocaleDateString()){
																		 	var date1=new Date(channel.attributes.date);
																			createdate=date1.toTimeString(); 
																			var str=createdate.split(" ");
																			var newstr=str[0].split(":");
																			 var pr="";
																			if(newstr[0]>12){
																              var pr="PM";
																			}else{
																			  var pr="AM";
																			}
																		    $("#time"+user.attributes.id).html(createdate=newstr[0]+":"+newstr[1]+" "+pr);
														              }
														              else{
														              	        const yesterday = new Date(today)
																				yesterday.setDate(yesterday.getDate() - 1)
																				if(yesterday.toLocaleDateString()==channeldate.toLocaleDateString()){
														   	                       $("#time"+user.attributes.id).html("Yerterday");
														   	                    }else{
														   	                    	 $("#time"+user.attributes.id).html(channeldate.toLocaleDateString());
														   	                    }
														              }
														   }
														 
										   			});
										   		}
										   	}
							  	 	     
							  	        });

							    }).catch(function() { });
							}
							 
							
				       }); 				   
	            },1000);
       		 });
		
			
				       	$("#loader").hide();
		
    }
    var $input = $('.emoji-wysiwyg-editor');
	$input.on('keydown', function(e) {		
		    generalChannel.typing();
    });
    $("#sendmessage").on('click', function(e) {
	    if ($('#chat-input').val()!="") {
			  if (generalChannel === undefined) {
				   return;
			  }
			  var date = new Date();
			  var msg=$("#msg_id").val();
			  var channel=$("#update_channel_id").val();
			  if(msg==""&&channel==""){
				  generalChannel.sendMessage($(".emoji-wysiwyg-editor").html(),{type:"text",date:date.toUTCString()});
			  }else{
				$.ajax({
						  url: $("#siteurl").val()+"/updatechannelmsg",
						  data:{ msg:msg,channel:channel,body:$(".emoji-wysiwyg-editor").html(),irow:$("#updatei").val()},
						  method:"get",
						  success: function( data ) {									
										 $("#msg_id").val("");
										 $("#update_channel_id").val("");  
										 $("#updatei").val("");
						  }
					});
			}
			$(".emoji-wysiwyg-editor").html('');
			$("#chat-input").val("");
		   
	    }
    });	
    $("#file-upload").on('change', function(e) {
			 $.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			 });
			 var fd = new FormData();
			 var files = $('#file-upload')[0].files[0];
			 fd.append('file',files);
			 $.ajax({
				url: $("#siteurl").val()+"/chatmediaupload",
				type: 'post',
				data: fd,
				contentType: false,
				processData: false,
				success: function(response){
				   var str=JSON.parse(response);
				   if(str.image!=""){
					  var date = new Date();
					  generalChannel.sendMessage(str.image,{type:str.type,date:date.toUTCString()});
				   }
				},
			});
	});
 });
}
 $(function() {
        
        window.emojiPicker = new EmojiPicker({
          emojiable_selector: '[data-emojiable=true]',
          assetsPath: '<?= asset("public/emoji/lib/img/") ?>',
          popupButtonClasses: 'fa fa-smile-o'
        });
        
        window.emojiPicker.discover();
      });









