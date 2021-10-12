<?php
if(empty($_POST['filename']) || empty($_POST['content'])){
    exit;
}

// Sanitizing the filename:
$filename = preg_replace('/[^a-z0-9\-\_\.]/i','',$_POST['filename']);

// Outputting headers:
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Pragma: public');

echo $_POST['content'];