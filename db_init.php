<?php

require('wp-config-local.php');

system(sprintf("mysql -u %s --password=%s -h %s -D %s < db.sql", DB_USER, DB_PASSWORD, DB_HOST, DB_NAME));


