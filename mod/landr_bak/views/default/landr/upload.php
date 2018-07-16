<?php
/**
 * Landr upload script
 * 
 */
$error = '';
$msg = '';
$proxy = '';
$fileElementName = 'fileToUpload';
$upload_dir = elgg_get_data_path() . 'landr/'; 
$upload_file = $upload_dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = pathinfo($upload_file,PATHINFO_EXTENSION); 

if (empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none') {
	
	$error .= 'nothing'; 
	
} elseif (file_exists($upload_file)) {
    
    $error .= 'exist';  
    $proxy .= elgg_get_site_url() . 'mod/landr/proxy.php?img=' . basename( $_FILES["fileToUpload"]["name"]);

} elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif" ) {
    
    $error .= 'allowed';
    
} elseif ($_FILES["fileToUpload"]["size"] > 2000000) {
    
    $error .= "size"; 
    
} else {
    
    if(!is_dir($upload_dir)){
        mkdir($upload_dir, 0777);        
    } 
    
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $upload_file);
    $msg .= 'Success!'; 
    $proxy .= elgg_get_site_url() . 'mod/landr/proxy.php?img=' . basename( $_FILES["fileToUpload"]["name"]);

}	 

echo "{";
echo				"error: '" . $error . "',\n";
echo				"msg: '" . $msg . "',\n";
echo				"proxy: '" . $proxy . "'\n"; 
echo "}";