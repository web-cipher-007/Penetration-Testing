<?php
$output = [];
exec('ls', $output);
echo "<pre>" . implode("\n", $output) . "</pre>";
?>
