<?php
echo "exec() is " . (function_exists('exec') ? "enabled" : "disabled") . "\n";
echo "popen() is " . (function_exists('popen') ? "enabled" : "disabled") . "\n";
echo "pclose() is " . (function_exists('pclose') ? "enabled" : "disabled") . "\n";
echo "posix_kill() is " . (function_exists('posix_kill') ? "enabled" : "disabled") . "\n";
?>