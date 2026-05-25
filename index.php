<?php
echo "<h1>Lab is live</h1>";
echo "<p>PHP version: " . phpversion() . "</p>";
echo "<p>Server time: " . date('Y-m-d H:i:s T') . "</p>";
echo "<p>Document root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Host: " . $_SERVER['HTTP_HOST'] . "</p>";
echo "<hr>";
phpinfo(INFO_GENERAL | INFO_CONFIGURATION);
