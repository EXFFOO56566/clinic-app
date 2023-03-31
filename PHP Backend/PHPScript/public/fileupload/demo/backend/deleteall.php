<?php

$folder_path = "/home/freaktemplate/public_html/fileupload/demo/backend/files"; 
   
// List of name of files inside 
// specified folder 
$files = glob($folder_path.'/*');  
// Deleting all the files in the list 
foreach($files as $file) { 
   
    if(is_file($file))  
    
        // Delete the given file 
        unlink($file);  
} 

?>