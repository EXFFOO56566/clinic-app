"use strict"
 
function gettimezone(){
	var timezone_offset_minutes = new Date().getTimezoneOffset();
    timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
    $.ajax({
					url: $("#own_url").val()+"/settimezone"+"/"+timezone_offset_minutes,
					method:"get",
					success: function( data ) {
						 
					}
		});
}    
 $(document).ready(function() {
        $('#service').DataTable({    
             order:[[0,"DESC"]]        
        });
});

function adminchangelogin(){
      firebase.database().ref($("#mycurrentuser").val()).update({
              name: $("#currentusername").val(),
              profile:$("#authprofile").val(),
              usertype:2
          }, (error) => {
            if (error) {

            } else {
                
            }
      });
}

function logout() {
	//alert("hello");
	var currentuser = $("#authid").val();
	firebase.database().ref(currentuser + "/TokenList/").update({
		web: 0
	});
	$("#tab-lg").addClass("active");
	window.location.href = $("#own_url").val() + "/logout";
}
function doctorchangechat(){
    firebase.database().ref($("#mycurrentuser").val()).update({
              name: $("#currentusername").val(),
              profile:$("#authprofile").val(),
              usertype:3
          }, (error) => {
            if (error) {

            } else {
                
            }
      });
}
 function deleterow(url,id){
        if (confirm($("#delete_msg").val())) {  
                if($("#site_type").val()=='1'){
                    disablebtn();
                }
                else{
                    window.location.href =$("#admin_url").val()+'/'+url+'/'+id;
                }    
                 
        } else {
            window.location.reload();
        }
 }

 function play_sound() {
            var source = $("#soundnotify").val();
            var audioElement = document.createElement('audio');
            audioElement.autoplay = true;
            audioElement.load();
            audioElement.addEventListener("load", function() { 
                audioElement.play(); 
            }, true);
            audioElement.src = source;
            audioElement.id="notificationsound";
        }

          function PlaySound() {
        var source = $("#soundnotify").val();
            var audioElement = document.getElementById('notificationboxaudio');
            audioElement.autoplay = true;
            audioElement.load();
            audioElement.addEventListener("load", function() { 
                audioElement.play(); 
            }, true);
            audioElement.src = source;
    }


    function StopSound() {
        var player = document.getElementById('notificationboxaudio');
        player.Src = $("#soundnotify").val();
        player.autoplay = false;
        
                player.pause(); 
           
    }

    function isPlaying() { 
      if(!document.getElementById('notificationboxaudio').paused){
        return 1;
      }
    }



 function storeappointmentresc(id){    
    $("#app_id").val(id);
 } 

 function getdoctorapp(id){
     $.ajax({
                url: $("#doctor_url").val()+"/getreferdoctor"+"/"+id,
                method:"get",
                success: function( data ) {
                   document.getElementById("refer_id").innerHTML=data;
                   $("#appoint_id").val(id);
                }
    });
 }
 function changecheckboth(val){
        var npwd=$("#npwd").val();
        if(npwd!=val){
            alert($("#samepwd").val());
            $("#npwd").val("");
            $("#rpwd").val("");
        }
}

function checkcurrentpwdtest(val){
    $.ajax({
                url: $("#admin_url").val()+"/samepwd"+"/"+val,
                method:"get",
                success: function( data ) {
                        if(data==0){
                            alert($("#incorrectpwd").val());
                            $("#cpwd").val("");
                        }
                }
        });
}

function disablebtn(){
    alert("This Action Disable In Demo");
}

      $(document).ready(function() {
           var _URL = window.URL || window.webkitURL;
            $('#upload_image_logo').on('change', function(e) {
                 var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            var flag=0;
            img.onload = function() {
                
                if(this.width >= 200 && this.height >= 60)
                {
                     flag=1;

                }
                else
                {
                    alert($("#invaildimg").val());
                    window.location.reload();
                }
            };
              img.src = _URL.createObjectURL(file);
            readURL(this,"img_logo");
        }


                   
            });
     });
     function readURL(input,field) {  
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $("#logo").val(e.target.result);
                $('#'+field).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function checktime(currentval,idata){
        var starttime=$("#from"+idata).val();
        var endtime=$("#to"+idata).val();
        var str=starttime.split(":");
        var str1=endtime.split(":");
        if(str[0]>str1[0] || str[0]==str1[0]){
            alert($("#chkworkingtime").val());
            $("#from"+idata).val("");
            $("#to"+idata).val("");
        }
    }


     $(function() {

                $('#start_date, #end_date').datepicker({
                    showOn: "both",
                    beforeShow: customRange,
                    dateFormat: "yy-mm-dd",
                });

            });

            function customRange(input) {

                if (input.id == 'end_date') {
                    var minDate = new Date($('#start_date').val());
                    minDate.setDate(minDate.getDate() + 1)

                    return {
                        minDate: minDate

                    };
                }
                return {}
            }

            function Resetappointment(){
                window.location.href=$("#admin_url").val()+"/appointment/0/0";
            }

            function searchappointment(){
                var start=$("#start_date").val();
                var end=$("#end_date").val();
                if(start!=""&&end!=""){
                    window.location.href=$("#admin_url").val()+"/appointment"+"/"+start+"/"+end;
                }else{
                    alert($("#seldate").val());
                    $("#start_date").val("");
                    $("#start_date").val("");
                }
            }

            $(document).ready(function () {
                               $('#latestorderTable').DataTable({
                                  pageLength:5 ,
                                  bLengthChange: false,
                                  searching: false,
                                 
                                 
                                  
                                  order:[[0,"DESC"]]
                              });
                           });
                              $(document).ready(function () {
                               $('#myTablereview').DataTable({
                                  pageLength:5 ,
                                  bLengthChange: false,
                                  searching: false,
                                
                                  
                                  order:[[0,"DESC"]]
                              });
                           }); 
                         




