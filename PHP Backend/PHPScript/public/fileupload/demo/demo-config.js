 
$(function(){
  /*
   * For the sake keeping the code clean and the examples simple this file
   * contains only the plugin configuration & callbacks.
   * 
   * UI functions ui_* can be located in: demo-ui.js
   */
    $.ajaxSetup({
                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),


                }
            });
  $('#drag-and-drop-zone').dmUploader({ //
    url: $("#admin_url").val()+'/uploadimage'+'/'+$("#album_id").val(),
   // maxFileSize: 3000000,
    onDragEnter: function(){
      // Happens when dragging something over the DnD area
      this.addClass('active');
    },
    onDragLeave: function(){
      // Happens when dragging something OUT of the DnD area
      this.removeClass('active');
    },
    onInit: function(){
      // Plugin is ready to use
      ui_add_log('Penguin initialized :)', 'info');
    },
    onComplete: function(){
      // All files in the queue are processed (success or error)
      ui_add_log('All pending tranfers finished');
    },
    onNewFile: function(id, file){
      // When a new file is added using the file selector or the DnD area
      ui_add_log('New file added #' + id);
      ui_multi_add_file(id, file);
    },
    onBeforeUpload: function(id){
      // about tho start uploading a file
      ui_add_log('Starting the upload of #' + id);
      ui_multi_update_file_status(id, 'uploading', 'Uploading...');
      ui_multi_update_file_progress(id, 0, '', true);
    },
    onUploadCanceled: function(id) {
      // Happens when a file is directly canceled by the user.
      ui_multi_update_file_status(id, 'warning', 'Canceled by User');
      ui_multi_update_file_progress(id, 0, 'warning', false);
    },
    onUploadProgress: function(id, percent){
      // Updating file progress

      ui_multi_update_file_progress(id, percent);
    },
    onUploadSuccess: function(id, data){
      // A file was successfully uploaded
      
      var geti=$("#totaliimage").val();
      
      var txt="<input type='hidden' id='fileuploadname"+geti+"' value="+data.path+">";
      var ni=parseInt(geti)+parseInt(1);
      //$("#fileuploadname").val(data.path);
        $("#totaliimage").val(ni);
        $("#imagediv").append(txt);
      ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
      ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');
      ui_multi_update_file_status(id, 'success', 'Upload Complete');
      ui_multi_update_file_progress(id, 100, 'success', false);
    },
    onUploadError: function(id, xhr, status, message){
      ui_multi_update_file_status(id, 'danger', message);
      ui_multi_update_file_progress(id, 0, 'danger', false);  
    },
    onFallbackMode: function(){
      // When the browser doesn't support this plugin :(
      ui_add_log('Plugin cant be used here, running Fallback callback', 'danger');
    },
    onFileSizeError: function(file){
      ui_add_log('File \'' + file.name + '\' cannot be added: size excess limit', 'danger');
    }
  });
});

function getimagelink(){
   document.getElementById("filepath").style.display="block";
   var txt="";
   for(var i=0;i<$("#totaliimage").val();i++){
        
        $.ajax({
            url: $("#site_url").val()+'/public/fileupload/demo/backend/moveimage.php',
            method:'get',
            data: {
                file_name:$("#fileuploadname"+i).val()
            },
            success: function(res) {
                console.log(res);
                if(res!=0){
                     var txt="";
                     var str=res.split(".");
                     if(str[1]=="apk"){
                          var it="https://freaktemplate.com/install/"+str[0];
                     }else{
                          var it="https://freaktemplate.com/"+str[0];
                     }
                    
                     var id=i;
                     txt=txt+'<div class="col-md-12"><input type="text" id="'+id+'" style="float:left" value="'+it+'" class="form-control col-md-10" readonly/><button type="button" onclick="myFunction('+id+')" class="btn btn-primary col-md-2"><i class="fa fa-copy"></i></button>';
                     console.log(txt);
                     $("#filepath").append(txt);
                }
                
            }
        });
      
   }
   
}

function myFunction(val) {
  var copyText = document.getElementById(val);
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
}