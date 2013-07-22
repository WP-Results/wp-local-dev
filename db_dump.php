<?php

require('wp-config-local.php');

$cmd = sprintf("mysqldump --user=%s --password=%s --host=%s %s > db.sql", DB_USER, DB_PASSWORD, DB_HOST, DB_NAME);
system($cmd);


