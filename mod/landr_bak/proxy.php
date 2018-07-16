<?php
/**
 * Landr proxy since slider images are uploaded in the data directory
 * 
 */

require_once('../../engine/start.php'); 
// define absolute path to image folder
$image_folder = elgg_get_data_path() . 'landr/'; 
// get the image name from the query string
// and make sure it's not trying to probe your file system
if (isset($_GET['img']) && basename($_GET['img']) == $_GET['img']) {
    $img = $image_folder.$_GET['img'];
    if (file_exists($img) && is_readable($img)) {
        // get the filename extension
        $ext = substr($img, -3); 
        // set the MIME type
        switch ($ext) {
            case 'jpg':
                $mime = 'image/jpeg';
                break;
            case 'gif':
                $mime = 'image/gif';
                break;
            case 'png':
                $mime = 'image/png';
                break;
            default:
                $mime = false;
        }
        // if a valid MIME type exists, display the image
        // by sending appropriate headers and streaming the file
        if ($mime) {
            header('Content-type: '.$mime);
            header('Content-length: '.filesize($img));
            $file = @ fopen($img, 'rb');
            if ($file) {
                fpassthru($file);
                exit;
            }
        }
    }
}
?> 