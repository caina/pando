<?php

$encoded_file=$_POST['file'];
$decoded_file=base64_decode($encoded_file);
/*Now you can copy the uploaded file to your server.*/
file_put_contents($_POST['name'],$decoded_file);

?>