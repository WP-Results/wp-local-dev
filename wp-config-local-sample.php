<?php
/*
COPY THIS INTO YOUR ROOT FOLDER and include from wp-config.php

Below, configure your:
 * databaes connection
 * root path
 * .htaccess as needed
 * Any debugging messages to skip
*/


/*
===========================
BEGIN CONFIG
===========================
*/

/* 
You definitely need to configure your database connection. 
*/
define('DB_NAME', 'wordpress');
define('DB_USER', 'dev');
define('DB_PASSWORD', 'dev');
define('DB_HOST', 'localhost');

/*
Debugging messages to skip. dprint() prints out hashes of messages so you can easily
identfy them and skip them if desired.

You can also provide a regular expression that, if found in the debug message, will
suppress it.
*/
$WPR_DEBUG_SKIP = array(
  '2dd2d83fd686c65a725f278a757512fa', // SimplePie
);

/*
Set your script debugging here.
*/
define('WP_DEBUG', true);
define('SCRIPT_DEBUG', true);
error_reporting(E_ALL);
ini_set('display_errors','Off');
ini_set('error_log',dirname(__FILE__).'/error.log');

/*
You probably don't need to change this, but you can set your base URL here explicitly
if you desire.

It should look like: http://my.domain.com/path/to/wp/root
*/
$wp_url = sprintf("http://%s%s",
  $_SERVER['SERVER_NAME'],
  substr(dirname(__FILE__), strlen($_SERVER['DOCUMENT_ROOT']))
);

/*
You will probably want to change the email address.
*/
$meta = array(
  'siteurl'=>$wp_url,
  'home'=>$wp_url,
  'admin_email'=>'you@domain.com',
);

/*
You may need to define this to allow uploads.
*/
define('FS_METHOD', 'direct');

/*
===========================
END CONFIG
===========================
*/

// Include dprint() if available, otherwise define it.
$debug_fname = dirname(__FILE__).'/wp-local-dev/debug.php';
if(file_exists($debug_fname))
{
  require($debug_fname);
} else {
  function dprint($s,$should_exit=false)
  {
    error_log("Debugging not available. Install git@github.com:/WP-Results/wp-local-dev.git");
  }
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Update all meta info
foreach($meta as $k=>$v)
{
  $mysqli->query("UPDATE wp_options SET option_value = '{$v}' WHERE option_name = '{$k}'");
}

// Reset the admin password to 'admin'
$mysqli->query(sprintf("update wp_users set user_email = '%s', user_pass='%s' where user_login = 'admin'",
  $meta['admin_email'],
  '$P$BeTcuDVW8ptcglqKUAVb1yV4jZhjl..'
));


if(isset($_SERVER['REMOTE_ADDR']))
{
  function getRemoteAddress() {
          $hostname = $_SERVER['REMOTE_ADDR'];
  
          $headers = apache_request_headers();
          foreach($headers as $k => $v) {
                  if(strcasecmp($k, "x-forwarded-for"))
                          continue;
  
                  $hostname = explode(",", $v);
                  $hostname = trim($hostname[0]);
                  break;
          }
  
          return $hostname;
  }
  
  $_SERVER["REMOTE_ADDR"] = getRemoteAddress();
}
