"use strict";
function chatnewlisthide() {
	$("#userchatlistall").css("display", "none");
	$("#search-contact1").html('');
}
$("#search-contact").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$("#userchatlist li").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
	$("#search-contact1").keyup(function(){
		  var value = $(this).val().toLowerCase();
		  $("#userchatlistall").css("display","block");
		$("#chatsidebar1 ul li").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
		 if (!this.value) {
            $("#userchatlistall").css("display","none");
        }
});
function getallusers() {
	$("#userchatlistall").empty();
	var commentsRef = firebase.database().ref();
	commentsRef.on('child_added', (data) => {
		var str = data.val();
		if (data.key != $("#mycurrentuser").val()&&$("#search" + data.key).length==0&&str.usertype!=$("#usertype").val()) {
				var listdata = '';
				var str = data.val();
				if (str.profile != "") {
					var path = $("#siteurl").val() + '/public/upload/' + str.profile;
				} else {
					var path = $("#siteurl").val() + '/public/upload/profile/profile.png';
				}
				if (data.presence==true) {
					var status = "online";
				} else {
					var status = "offline";
				}

				if($("#search" + data.key).length){
					 listdata ='<a class="contacts-link" href="javascript:getreceiverinfo(\'' + data.key + '\')"><div class="avatar avatar-'+status+'"><img src="' + path + '" alt=""></div><div class="contacts-content"><div class="contacts-info"><h6 class="chat-name text-truncate">' + str.name + '</h6><div class="chat-time"></div></div><div class="contacts-texts"></div></div></a>';
					      $("#search" + data.key).html(listdata);
				}else{
					listdata ='<li class="contacts-item" id="search' + data.key + '" ><a class="contacts-link" href="javascript:getreceiverinfo(\'' + data.key + '\')"><div class="avatar avatar-'+status+'"><img src="' + path + '" alt=""></div><div class="contacts-content"><div class="contacts-info"><h6 class="chat-name text-truncate">' + str.name + '</h6><div class="chat-time"></div></div><div class="contacts-texts"></div></div></a></li>';
					     $("#userchatlistall").append(listdata);
				}
		}
	});
	commentsRef.on('child_changed', (data) => {
	});
	commentsRef.on('child_removed', (data) => {
		if (data.key != $("#mycurrentuser").val()) {
			$("#search" + data.key).remove();
		}
	});
}

function getreceiverinfo(receiverid) {
	document.getElementById("chat_1").style.display = "none";
	document.getElementById("chat_2").style.display = "block";
	$("#bodymessage").html('');
	$("#totalchat").val(0);
	var starCountRef = firebase.database().ref(receiverid);
	starCountRef.on('value', (snapshot) => {
		var data = snapshot.val();
		$("#receiveruid").val(receiverid);
		$("#receivername").html(data.name);
		if (data.profile == "") {
			$("#receiverimg").attr("src", $("#path_url").val() + "/public/upload/profile/profile.png");
		} else {
			$("#receiverimg").attr("src", $("#path_url").val() + "/public/upload/" + data.profile);
		}
		if (data.presence==true) {
			$("#receiverclassstatus").removeClass('avatar-offline');
			$("#receiverclassstatus").addClass('avatar-online');
			$("#receiverstatus").html("online");
		} else {
			$("#receiverclassstatus").addClass('avatar-offline');
			$("#receiverclassstatus").removeClass('avatar-online');
			$("#receiverstatus").html("offline");
		}
		$('#chat_new').modal('hide');
		$("#chatfooter").css("display", "block");

	});
	var starCountRef = firebase.database().ref($("#mycurrentuser").val() + '/chatlist/' + receiverid);
	starCountRef.on('value', (snapshot) => {
		var data = snapshot.val();
		if (data != null) {
			$("#currentchannelid").val(data.channelId);
			getlistofmessage(receiverid, data.channelId);
		} else {
		    $("#requestmsg").css("display","none");
			$("#currentchannelid").val('');
		}
	});
}

$("#sendmsg").keypress(function(e){
        var receiverid = $("#receiveruid").val();
        var mycurrentuser = $("#mycurrentuser").val();
        if($("#currentchannelid").val()!=""&&e.which != '13'){
          var starCountRef = firebase.database().ref(receiverid+'/chatlist/'+mycurrentuser);
              starCountRef.once('value', (snapshot) =>{
                    const data = snapshot.val();
                    if(snapshot.exists()&&data!=""){
                               firebase.database().ref(receiverid+'/chatlist/'+mycurrentuser).update({
                                      typing:1
                                });
                    }
              });
                $(document).ready(function () {
            		setTimeout(function () {
            			firebase.database().ref(receiverid + '/chatlist/' + mycurrentuser).update({
            				typing: 0
            			});
            		}, 5000);
               });  
             
      }else{
          if(e.which == '13'){
               createchannel();
          }
       
      }
});


function createchannel() {
	var receiverid = $("#receiveruid").val();
	var mycurrentuser = $("#mycurrentuser").val();
	var msg = document.querySelector('#sendmsg').value;
	if (msg == "") {
		alert("Please Enter Message");
	} else {
		var currentUserinfo = firebase.auth().currentUser;
		var channel_id = $("#currentchannelid").val();
		if (channel_id == "") {
			channel_id = mycurrentuser + receiverid;
			$("#created_at").val(1);
			$("#currentchannelid").val(channel_id);
		}
		document.querySelector('#sendmsg').value = "";
		var name = $("#currentusername").val();
		var time = $("#currenttime").val();				
		db.collection("Chats").doc(channel_id).collection('All Chat').add({
						msg: msg,
						time: time,
						type: 0,
						uid: mycurrentuser
		})
		.then(function (docRef) {
			var starCountRef = firebase.database().ref(mycurrentuser + '/chatlist/' + receiverid);
				starCountRef.once('value', (snapshot) => { //retrieve
					const data = snapshot.val();
							if (data != null) {
								firebase.database().ref(mycurrentuser + '/chatlist/' + receiverid).update({ //update
									channelId: channel_id,
									last_msg: msg,
									messageCount: 0,
									time: time,
									type: 0,
									status: 1
								});
							} else {
								firebase.database().ref(mycurrentuser + '/chatlist/' + receiverid).set({ //add
									channelId: channel_id,
									last_msg: msg,
									messageCount: 0,
									time: time,
									type: 0,
									status: 1
								});
							}
						});
						/* receiver user data add and update */
						var starCountRef = firebase.database().ref(receiverid + '/chatlist/' + mycurrentuser);
						starCountRef.once('value', (snapshot) => { //retrieve
							const data = snapshot.val();
							if (data != null) {
								var msgcount = parseInt(data.messageCount) + parseInt(1);
							
								firebase.database().ref(receiverid + '/chatlist/' + mycurrentuser).update({ //update
									channelId: channel_id,
									last_msg: msg,
									messageCount: msgcount,
									time: time,
									type: 0
								});
							} else {
								firebase.database().ref(receiverid + '/chatlist/' + mycurrentuser).set({ //add
									channelId: channel_id,
									last_msg: msg,
									messageCount: 1,
									time: time,
									type: 0,
									status: 0
								});
							}
						});
					})
					.catch(function (error) {
						console.log("Error adding document: ", error);
					});
					var starCountRef = firebase.database().ref(receiverid + "/TokenList/");
		starCountRef.on('value', (snapshot) => {
			snapshot.forEach(function (childSnapshot) {
				if (childSnapshot.val() != 0) {
					$.ajax({
						url: $("#path_url").val() + "/sendnotification",
						data: {
							token: childSnapshot.val(),
							message: msg,
							sender_name: $("#currentusername").val(),
							image : $("#path_url").val()+"/public/upload"+"/"+$("#authprofile").val()
						},
						success: function (res) {

						}
					});
				}
			});
		});
	}
}

function imagezoom(docid){
    var imgLink = $("#imagezoom"+docid).attr('src');
    $('.mask').html('<div class="img-box"><img src="'+ imgLink +'"><a class="close">&times;</a>');

    $('.mask').addClass('is-visible fadein').on('animationend', function(){
        $(this).removeClass('fadein is-visible').addClass('is-visible');
    });
    $('.close').on('click', function(){
        $(this).parents('.mask').addClass('fadeout').on('animationend', function(){
            $(this).removeClass('fadeout is-visible')
        });
    });
}

function convertUTCToTimezone(utcDt, utcDtFormat, timezone,format1) {
    if(format1!=""&&utcDt!=""){
        return moment.utc(utcDt, utcDtFormat).tz($("#timezone").val()).format(format1);
    }
    
}

function deletetoken() {
/*	var currentuser = $("#mycurrentuser").val();
	firebase.database().ref(currentuser + "/TokenList/").update({
		web: 0
	});*/
}

function isToday(date) {
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();
	var todayDate = yyyy + '-' + mm + '-' + dd;
	if (date == todayDate) {
		return true;
	}
}

function isYesterday(date) {
	var $today = new Date();
	var dd = String($today.getDate()).padStart(2, '0');
	var mm = String($today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = $today.getFullYear();
	var $yesterday = new Date($today);
	$yesterday.setDate($today.getDate() - 1);
	var $dd = $yesterday.getDate();
	var $mm = $yesterday.getMonth() + 1; //January is 0!
	var $yyyy = $yesterday.getFullYear();
	var yesterDate = $yyyy + '-' + $mm + '-' + $dd
	if (date == yesterDate) {
		
		return true;
	}
}

function urlify(text) {
	var urlRegex = /(https?:\/\/[^\s]+)/g;
	return text.replace(urlRegex, function (url) {
		return '<a href="' + url + '">' + url + '</a>';
	})
}

function gettimezone(){
    if($("#timezone").val()==""){
           var d = new Date();
           var n = d.getTimezoneOffset();
		   $.ajax({
			    url: $("#path_url").val() + "/getcurrenttime"+"/"+n,
			    success: function (res) {
			    var str=JSON.parse(res);
			    $("#currenttime").val(str.date);
			    $("#timezone").val(str.timezone);
			    
			}
		 });
    }
    return $("#timezone").val();
}




function closrchatcontent(){
    $("#caht_sidebar_p").removeClass('hide-left-sidebar');
    $(".custom-chats").removeClass('show-chatbar');
}


var currentimeref = firebase.database().ref("currenttime");
 currentimeref.once('value', (snapshot) =>{
        const data = snapshot.val();
        var time = data.time;
        var lastOnlineRef = firebase.database().ref($("#mycurrentuser").val()+'/last_seen');
        var lastOnlineRefpresence = firebase.database().ref($("#mycurrentuser").val()+'/presence');
        var connectedRef = firebase.database().ref('.info/connected');
            connectedRef.on('value', function(snap) {
                if (snap.val() === true) {
                    lastOnlineRefpresence.onDisconnect().set(false);
                    lastOnlineRef.onDisconnect().set(time)
                    lastOnlineRefpresence.set(true);
                }
            });
 });

    const   button = document.querySelector('#emoji-button');
    const   picker = new EmojiButton();
            picker.on('emoji', emoji => {
                document.querySelector('#sendmsg').value += emoji;
              });
            button.addEventListener('click', () => {
              picker.togglePicker(button);
            });

          
            messaging.onMessage(function(payload) {
                  console.log("Message received. ", payload);
                  console.log(payload.notification.title);
                  console.log(payload.notification.body); 
            });
            function requestPermission() {
                Notification.requestPermission().then((permission) => {
                  if (permission === 'granted') {
                    console.log('Notification permission granted.');
                    
                    resetUI();
                  } else {
                    console.log('Unable to get permission to notify.');      }
                });
              }
function getlistofmessage(userid, channel_id) {
    $("#caht_sidebar_p").addClass('hide-left-sidebar');
    $(".custom-chats").addClass('show-chatbar');
	$(".unread").removeClass("unread");
	$("#chat_1").css("display", "none");
	$("#chat_2").css("display", "block");
	$("#list" + userid).addClass('unread');
	var mycurrentuser = $("#mycurrentuser").val();
	firebase.database().ref(mycurrentuser + '/chatlist/' + userid).update({ //update
		messageCount: 0
	});
	var starCountRef = firebase.database().ref(mycurrentuser + '/chatlist/' + userid);
	starCountRef.once('value', (snapshot) => {
		const ownchatlist = snapshot.val();
		if (ownchatlist != null) {
			var starCountRef1 = firebase.database().ref(userid);
			starCountRef1.once('value', (snapshot1) => {
				const user = snapshot1.val();
				if (user != null) {
					$("#receiveruid").val(userid);
					$("#receivername").html(user.name);
					if (user.profile == "") {
						var path = $("#path_url").val() + "/public/upload/profile/profile.png";
						$("#receiverimg").attr("src", $("#path_url").val() + "/public/upload/profile/profile.png");
					} else {
						var path = $("#path_url").val() + "/public/upload/" + user.profile;
						$("#receiverimg").attr("src", $("#path_url").val() + "/public/upload/" + "/" + user.profile);
					}
					if (ownchatlist.status == 1) {
						$("#chatfooter").css("display", "block");
						$("#requestmsg").css("display", "none");
					} else {
						$("#chatfooter").css("display", "none");
						$("#requestmsg").css("display", "block");
						if($("#created_at").val()==0){
						    	$("#requestmsg").html('<div class="col-md-12" style="display: flex;justify-content: center;margin-top: 150px;"><div class="pl-4 pr-4 mt-0 right_sidebar_logo"><div class="text-center mb-3"><figure class="avatar avatar-xl mb-3"><img src="' + path + '" class="rounded-circle mCS_img_loaded" alt="image"></figure><h5 class="profile-name">' + user.name + '</h5><h5 class="profile-name">' + user.email + '</h5></div><div class="col-md-12" style="text-align: center !important;"><button class="btn btn-primary" type="button" onclick="changechatstatus(\'' + userid + '\',1)"> Accept </button><button class="btn btn-danger" type="button" onclick="changechatstatus(\'' + userid + '\',0)"> Reject </button></div></div></div>');
						}
					
					}
					$("#bodymessage").empty();
				}
			});
			$("#currentchannelid").val(ownchatlist.channelId);
		}
	});

	db.collection("Chats").doc(channel_id).collection('All Chat').orderBy("time", "asc").onSnapshot(function (querySnapshot) {
		querySnapshot.docChanges().forEach(function (change) {

			if (change.type === "added") {
				var msgs = change.doc.data();
				var date = msgs.time;
				if (date != null) {
					var local1 = convertUTCToTimezone(msgs.time, null, gettimezone(), 'YYYY-MM-DD');
					var title = "";
					if ($("#rightdate" + local1).length) {} else {
						if (isToday(local1)) {
							title = 'Today';
						} else if (isYesterday(local1)) {
							title = "Yesterday";
						} else {
							title = local1;
						}

						$("#bodymessage").append('<div class="chat-line" id="rightdate' + local1 + '"><span class="chat-date">' + title + '</span></div>');
					}
					var finaldate = convertUTCToTimezone(msgs.time, null, gettimezone(), 'HH:mm A');
				} else {
					var finaldate = convertUTCToTimezone($("#currenttime").val(), null, gettimezone(), 'HH:mm A');
				}
				if ($("#chatuserls" + change.doc.id).length) {} else {
					if (msgs.uid == mycurrentuser) {
						var path = $("#path_url").val()+"/public/upload"+"/"+$("#authprofile").val();
						if (msgs.type == '1') {
							var imgpath = $("#path_url").val() + '/public/upload/chat' + '/' + msgs.msg;
							$("#bodymessage").append('<div class="message self" id="chatuserls' + change.doc.id + '"><div class="message-wrapper"><div class="message-content"><img class="chatimag" src="' + imgpath + '" id="imagezoom' + change.doc.id + '"  onclick="imagezoom(\'' + change.doc.id + '\')" style="width:250px;height:250px"/></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="' + path + '"></div><span class="message-date" id="chattime' + change.doc.id + '">'+finaldate+'</span><div class="dropdown"><a class="text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg class="hw-18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg></a><div class="dropdown-menu"><a class="dropdown-item d-flex align-items-center text-danger" href="javascript:deletemessage(\'' + change.doc.id + '\',\'' + msgs.type + '\',\'' + msgs.msg + '\',' + $("#totalchat").val() + ')"><svg class="hw-18 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg><span>'+$("#deletemsg").val()+'</span></a></div></div></div></div>');
						} else if (msgs.type == '2') {
							var videopath = $("#path_url").val() + '/public/upload/chat' + '/' + msgs.msg;
							$("#bodymessage").append('<div class="message self" id="chatuserls' + change.doc.id + '"><div class="message-wrapper"><div class="message-content"><video width="320" height="240" controls><source src="' + videopath + '" type="video/mp4"></video></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="' + path + '"></div><span class="message-date" id="chattime' + change.doc.id + '">'+finaldate+'</span><div class="dropdown"><a class="text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg class="hw-18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg></a><div class="dropdown-menu"><a class="dropdown-item d-flex align-items-center text-danger" href="javascript:deletemessage(\'' + change.doc.id + '\',\'' + msgs.type + '\',\'' + msgs.msg + '\',' + $("#totalchat").val() + ')"><svg class="hw-18 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg><span>'+$("#deletemsg").val()+'</span></a></div></div></div></div>');
						} else {
							$("#bodymessage").append('<div class="message self" id="chatuserls' + change.doc.id + '"><div class="message-wrapper"><div class="message-content"><span>' + urlify(msgs.msg) + '</span></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="' + path + '"></div><span class="message-date" id="chattime' + change.doc.id + '">'+finaldate+'</span><div class="dropdown"><a class="text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg class="hw-18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg></a><div class="dropdown-menu"><a class="dropdown-item d-flex align-items-center text-danger" href="javascript:deletemessage(\'' + change.doc.id + '\',\'' + msgs.type + '\',\'' + msgs.msg + '\',' + $("#totalchat").val() + ')"><svg class="hw-18 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg><span>'+$("#deletemsg").val()+'</span></a></div></div></div></div>');
						}
					} else {
						var path = $("#receiverimg").attr("src");
						if (msgs.type == '1') {
							var imgpath = $("#path_url").val() + '/public/upload/chat' + '/' + msgs.msg;
							$("#bodymessage").append('<div class="message" id="chatuserls' + change.doc.id + '"><div class="message-wrapper"><div class="message-content"><img class="chatimag" src="' + imgpath + '" id="imagezoom' + change.doc.id + '"  onclick="imagezoom(\'' + change.doc.id + '\')" style="width:250px;height:250px"/></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="' + path + '"></div><span class="message-date" id="chattime' + change.doc.id + '">'+finaldate+'</span></div></div>');
						} else if (msgs.type == '2') {
							var videopath = $("#path_url").val() + '/public/upload/chat' + '/' + msgs.msg
							$("#bodymessage").append('<div class="message self" id="chatuserls' + change.doc.id + '"><div class="message-wrapper"><div class="message-content"><video width="320" height="240" controls><source src="' + videopath + '" type="video/mp4"></video></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="' + path + '"></div><span class="message-date" id="chattime' + change.doc.id + '">'+finaldate+'</span></div></div>');
						} else {
							var path = $("#receiverimg").attr("src");
							$("#bodymessage").append('<div class="message" id="chatuserls' + change.doc.id + '"><div class="message-wrapper"><div class="message-content"><span>' + urlify(msgs.msg) + '</span></div></div><div class="message-options"><div class="avatar avatar-sm"><img alt="" src="' + path + '"></div><span class="message-date" id="chattime' + change.doc.id + '">'+finaldate+'</span></div></div>');
						}
					}
				}
				$("#totalchat").val(parseInt($("#totalchat").val()) + parseInt(1));
				if ($("#chatuserls" + change.doc.id).length) {
					$('#chat_2').animate({
						scrollTop: $("#chatuserls" + change.doc.id).offset().top
					}, 'fast');
					document.getElementById("chatuserls" + change.doc.id).scrollIntoView();
				}
			}
			if (change.type === "modified") {
			
				var msgs = change.doc.data();
			}
			if (change.type === "removed") {
			
				$("#chatuserls" + change.doc.id).remove();
			}
		});

	});

}


$(document).ready(function (e) {
           var d = new Date();
           var n = d.getTimezoneOffset();
		   $.ajax({
			    url: $("#path_url").val() + "/getcurrenttime"+"/"+n,
			    success: function (res) {
			    var str=JSON.parse(res);
			    $("#currenttime").val(str.date);
			    $("#timezone").val(str.timezone);
			}
		 });
	var commentsRef = firebase.database().ref($("#mycurrentuser").val() + "/chatlist/").orderByChild("time");
	commentsRef.on('child_added', (data) => {
		var str = data.val();
		if (data.key != null) {
			var starCountRef = firebase.database().ref(data.key);
			starCountRef.once('value', (snapshot) => {
				const str1 = snapshot.val();
				var date ="";
				if (str1 != null) {
					if (str1.profile != "") {
						var imgpath = $("#path_url").val() + "/public/upload/" + str1.profile;
					} else {
						var imgpath = $("#path_url").val() + "/public/upload/profile/profile.png";
					}
					if (str.time != "") {
					     
						var local1 = convertUTCToTimezone(str.time, null, gettimezone(), 'YYYY-MM-DD');

						var title = "";
						if (isToday(local1)) {
							title = convertUTCToTimezone(str.time, null, gettimezone(), 'HH:mm A');
						} else if (isYesterday(local1)) {
							title = "Yesterday";
						} else {
							title = local1;
						}
						date = title;
					}
				
						if (str1.presence==true) {
							var userstatus = "online";
						} else {
							var userstatus = "offline";
						}
						if (str.messageCount == 0) {
							var laclass = "";
							var count = "";
						} else {
							var laclass = "unreadmsg";
							var count = str.messageCount;
						}
						if (str.type == '1') {
							var lmsg = "Image";
						} else if (str.type == '2') {
							var lmsg = "Video";
						} else {
							var lmsg = str.last_msg;
						}
						var txt = '<li class="contacts-item groups" id="list'+data.key+'"><a class="contacts-link" href="javascript:getlistofmessage(\'' + data.key + '\',\'' + str.channelId + '\')"><div class="avatar avatar-' + userstatus + ' text-light" id="userstatuslist' + data.key + '"><span><img src="'+imgpath+'"/></span></div><div class="contacts-content"><div class="contacts-info"><h6 class="chat-name">'+str1.name+'</h6><div class="chat-time" id="listtime' + data.key + '"><span>'+date+'</span></div></div><div class="contacts-texts"><p class="text-truncate" id="lastmsg' + data.key + '">'+lmsg+'</p><p class="text-truncate" id="typing' + data.key + '" style="display:none">'+$("#typingmsg").val()+'</p><span id="msgcount'+data.key+'"></span></div></div></a></li>';
						if ($("#list" + data.key).length) {

						} else {
							$("#userchatlist").prepend(txt);
						}
					
					


				}
			});
		}
	});
	commentsRef.on('child_changed', (data) => {
		var str = data.val();

		if (data.key != null) {
			var starCountRef = firebase.database().ref(data.key);
			starCountRef.once('value', (snapshot) => {
				const str1 = snapshot.val();
				if (str1 != null) {
					if (str1.profile != "") {
						var imgpath = $("#path_url").val() + "/public/upload/" +  str1.profile;
					} else {
						var imgpath = $("#path_url").val() + "/public/upload/profile/profile.png";
					}
					var date = "";
					if (str.time != "") {
						var local1 = convertUTCToTimezone(str.time, null, gettimezone(), 'YYYY-MM-DD');
						var title = "";
						if (isToday(local1)) {
							title = convertUTCToTimezone(str.time, null, gettimezone(), 'HH:mm A');
						} else if (isYesterday(local1)) {
							title = "Yesterday";
						} else {
							title = local1;
						}
						var date = title;
					}
				
						if (str1.presence==true) {
							var userstatus = "online";
						} else {
							var userstatus = "offline";
						}
							var laclass = "";
							var count = "";
					
						if (str.type == '1') {
							var lmsg = "Image";
						} else if (str.type == '2') {
							var lmsg = "Video";
						} else {
							var lmsg = str.last_msg;
						}
						var txt = '<li class="contacts-item groups" id="list'+data.key+'"><a class="contacts-link" href="javascript:getlistofmessage(\'' + data.key + '\',\'' + str.channelId + '\')"><div class="avatar avatar-' + userstatus + ' text-light" id="userstatuslist' + data.key + '"><span><img src="'+imgpath+'"/></span></div><div class="contacts-content"><div class="contacts-info"><h6 class="chat-name">'+str1.name+'</h6><div class="chat-time" id="listtime' + data.key + '"><span>'+date+'</span></div></div><div class="contacts-texts"><p class="text-truncate" id="lastmsg' + data.key + '">'+lmsg+'</p><p class="text-truncate" id="typing' + data.key + '" style="display:none">'+$("#typingmsg").val()+'</p><span id="msgcount'+data.key+'"></span></div></div></a></li>';
						if ($("#list" + data.key).length) {
							if ($("#lastmsg" + data.key).html() != lmsg || $("#listtime" + data.key).html != date) {
								$(".unread").removeClass("unread");
								$("#list" + data.key).remove();
								$("#userchatlist").prepend(txt);
							}
						} else {
							$("#userchatlist").prepend(txt);
						}

				}
			});
		}
	});
	commentsRef.on('child_removed', (data) => {
	
	});

});

$(document).ready(function () {
	function have_notification() {
		var receiveruid = $("#receiveruid").val();
		var checkcount = 0;
		var starCountRef = firebase.database().ref($("#mycurrentuser").val() + "/chatlist");
		starCountRef.once('value', (snapshot) => {
			snapshot.forEach(function (childSnapshot) {
				var data = childSnapshot.val();
				if (childSnapshot.key != null) {
					if (data.messageCount == 0) {
						$("#msgcount" + childSnapshot.key).removeClass('unreadmsg');
						$("#msgcount" + childSnapshot.key).html('');
					} else {
						$("#msgcount" + childSnapshot.key).addClass('unreadmsg');
						$("#msgcount" + childSnapshot.key).html(data.messageCount);
						checkcount = parseInt(checkcount) + parseInt(1);
					}
					if (data.typing != null) {
						if (data.typing == 1) {
							$("#typing" + childSnapshot.key).css("display", "block");
							$("#lastmsg" + childSnapshot.key).css("display", "none");
						} else {
							$("#typing" + childSnapshot.key).css("display", "none");
							$("#lastmsg" + childSnapshot.key).css("display", "block");
						}
					}
					var myConnectionsRef = firebase.database().ref(childSnapshot.key);
					myConnectionsRef.on('value', function (snap) {
					    var str = snap.val();
						if (snap.exists()&&str.presence==true) {
							$("#userstatuslist" + childSnapshot.key).removeClass('avatar-offline');
							$("#userstatuslist" + childSnapshot.key).addClass('avatar-online');
						} else {
							$("#userstatuslist" + childSnapshot.key).removeClass('avatar-online');
							$("#userstatuslist" + childSnapshot.key).addClass('avatar-offline');
						}
					});
					if (checkcount != 0) {
						$("#changeunreadcount").html("Chat (" + checkcount + ")");
					} else {
						$("#changeunreadcount").html("Chat");
					}
				}
			});
		});
		if ($("#receiveruid").val() != "" && $("#receiveruid").val() != $("#mycurrentuser").val() && $("#currentchannelid").val() != "") {
			firebase.database().ref($("#mycurrentuser").val() + '/chatlist/' + $("#receiveruid").val()).update({ //update
				messageCount: 0
			});
			var myConnectionsRef = firebase.database().ref($("#receiveruid").val());
			myConnectionsRef.on('value', function (snap) {
			    var str = snap.val();
				if (snap.exists()&&str.presence==true) {
					$("#receiverstatus").html("online");
					$("#receiverclassstatus").addClass("avatar-online");
					$("#receiverclassstatus").removeClass("avatar-offline");
				} else {
					var starCountRef = firebase.database().ref($("#receiveruid").val());
					starCountRef.once('value', (snapshot) => {
						const data = snapshot.val();						
						if (data != null) {
							var local1 = convertUTCToTimezone(data.last_seen, null, gettimezone(), 'YYYY-MM-DD');
							if (isToday(local1)) {
								var local = convertUTCToTimezone(data.last_seen, null, gettimezone(), 'HH:mm A');
								$("#receiverstatus").html(local);
							} else if (isYesterday(local1)) {
								var local = "Yesterday " + convertUTCToTimezone(data.last_seen, null, gettimezone(), 'HH:mm A');
								$("#receiverstatus").html(local);
							} else {
								var local = convertUTCToTimezone(data.last_seen, null, gettimezone(), 'YYYY-MM-DD HH:mm A');
								$("#receiverstatus").html(local);
							}
						}
					});
					$("#receiverclassstatus").addClass("avatar-offline");
					$("#receiverclassstatus").removeClass("avatar-online");
				}
			});

		}


	}
	have_notification();

	setInterval(function () {
		  have_notification();
		   var d = new Date();
           var n = d.getTimezoneOffset();
		  $.ajax({
			url: $("#path_url").val() + "/getcurrenttime"+"/"+n,
			success: function (res) {
			    var str=JSON.parse(res);
			    $("#currenttime").val(str.date);
			    $("#timezone").val(str.timezone);
			}
		 });
	}, 1000);
});

$(document).ready(function (e) {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$('#file-upload').change(function (e) {
		e.preventDefault();
		var fd = new FormData();
		var files = $('#file-upload')[0].files[0];
		fd.append('file',files);
		$.ajax({
			type: 'POST',
			url: $("#path_url").val() + "/mediaupload",
			data: fd,
			cache: false,
			contentType: false,
			processData: false,
			success: (data) => {
				console.log(data);
				if (data != 0) {
					var receiverid = $("#receiveruid").val();
					var mycurrentuser = $("#mycurrentuser").val();
					//var currentUserinfo = firebase.auth().currentUser;
					var channel_id = $("#currentchannelid").val();
					var name = $("#currentusername").val();
					var str = JSON.parse(data);
					if (channel_id == "") {
						channel_id = mycurrentuser + receiverid;
						$("#currentchannelid").val(channel_id);
					}
					if (str.type == 1) {
						var txt = "Image";
					} else if (str.type == 2) {
						var txt = "Video";
					} else {
						var txt = "message";
					}
					var starCountRef = firebase.database().ref(receiverid + "/TokenList/");
					starCountRef.on('value', (snapshot) => {
						snapshot.forEach(function (childSnapshot) {
							if (childSnapshot.val() != 0) {
								$.ajax({
									url: $("#path_url").val() + "/sendnotification",
									data: {
										token: childSnapshot.val(),
										message: txt,
										sender_name: $("#currentusername").val(),
										image : $("#path_url").val()+"/public/upload"+"/"+$("#authprofile").val()
									},
									success: function (data) {

									}
								});
							}
						});
					});
					var time = str.time;
						$("#currenttime").val(time);
					db.collection("Chats").doc(channel_id).collection('All Chat').add({
							msg: str.media,
							time: time,
							type: str.type,
							uid: mycurrentuser
						})
						.then(function (docRef) {
							var starCountRef = firebase.database().ref(mycurrentuser + '/chatlist/' + receiverid);
							starCountRef.once('value', (snapshot) => { //retrieve
								const data = snapshot.val();
								if (data != null) {
									firebase.database().ref(mycurrentuser + '/chatlist/' + receiverid).update({ //update
										channelId: channel_id,
										last_msg: str.media,
										messageCount: 0,
										time: time,
										type: str.type,
										status: 1
									});
								} else {

									firebase.database().ref(mycurrentuser + '/chatlist/' + receiverid).set({ //add
										channelId: channel_id,
										last_msg: str.media,
										messageCount: 0,
										time: time,
										type: str.type,
										status: 1
									});
								}
							});
							/* receiver user data add and update */
							var starCountRef = firebase.database().ref(receiverid + '/chatlist/' + mycurrentuser);
							starCountRef.once('value', (snapshot) => { //retrieve
								const data = snapshot.val();
								if (data != null) {
									var msgcount = parseInt(data.messageCount) + parseInt(1);
								
									firebase.database().ref(receiverid + '/chatlist/' + mycurrentuser).update({ //update
										channelId: channel_id,
										last_msg: str.media,
										messageCount: msgcount,
										time: time,
										type: str.type
									});
								} else {
									firebase.database().ref(receiverid + '/chatlist/' + mycurrentuser).set({ //add
										channelId: channel_id,
										last_msg: str.media,
										messageCount: 1,
										time: time,
										type: str.type,
										status: 0
									});
								}
							});
						})
						.catch(function (error) {
							console.log("Error adding document: ", error);
						});
				}
			},
			error: function (data) {
				console.log(data);
			}
		});
	});
});


function deletemessage(docid, type, msg, tchat) {
	if (type != "0") {
		$.ajax({
			type: 'get',
			url: $("#path_url").val() + "/deletemedia/",
			data: {
				name: msg
			},
			success: (data) => {
				//console.log("done");
			},
			error: (res) => {
				console.log(res);
			}
		});
	}
	db.collection("Chats").doc($("#currentchannelid").val()).collection('All Chat').doc(docid).delete().then(function () {
	
		var receiverid = $("#receiveruid").val();
		var mycurrentuser = $("#mycurrentuser").val();
		var mainchat = parseInt($("#totalchat").val()) - parseInt(1);
		if (mainchat == tchat) {
			db.collection("Chats").doc($("#currentchannelid").val()).collection('All Chat').orderBy("time", "desc").limit(1).onSnapshot(function (querySnapshot) {
				querySnapshot.docChanges().forEach(function (change) {
					msgs = change.doc.data();
					if (msgs.type == "1") {
						var msg = "image";
					} else if (msgs.type == "2") {
						var msg = "video";
					} else {
						var msg = msgs.msg;
					}
					var starCountRef = firebase.database().ref(receiverid + '/chatlist/' + mycurrentuser);
					starCountRef.once('value', (snapshot) => { //retrieve
						const data = snapshot.val();
						if (data.messageCount == 0 || data.messageCount == 1) {
							firebase.database().ref(receiverid + '/chatlist/' + mycurrentuser).update({ //update
								last_msg: msg,
								time: msgs.time,
								type: msgs.type,
								messageCount: 0
							});
						} else {
							firebase.database().ref(receiverid + '/chatlist/' + mycurrentuser).update({ //update
								last_msg: msg,
								time: msgs.time,
								type: msgs.type,
								messageCount: parseInt(data.messageCount) - parseInt(1)
							});
						}


					});
					firebase.database().ref(mycurrentuser + '/chatlist/' + receiverid).update({ //update
						last_msg: msg,
						time: msgs.time,
						type: msgs.type
					});
				});
			});
		}
	}).catch(function (error) {
		console.error("Error removing document: ", error);
	});

}

function storeusertoken() {
	var currentuser = $("#mycurrentuser").val();
	firebase.database().ref(currentuser + "/TokenList/").update({
		web: $("#mycurrentoken").val()
	});

}

