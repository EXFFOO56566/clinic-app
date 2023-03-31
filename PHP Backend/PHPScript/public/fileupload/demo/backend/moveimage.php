<?php

if(isset($_GET['file_name'])&&$_GET['file_name']!=""){
   $file1='/home/freaktemplate/public_html/fileupload/demo/backend/files'.'/'.$_GET['file_name'];
   $file2='/home/freaktemplate/public_html/siteimage/'.'/'.$_GET['file_name'];
   copy($file1,$file2);
   echo $_GET['file_name'];
}
else{
    echo 0;
}


?>