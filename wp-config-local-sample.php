<?php
/*
COPY THIS INTO YOUR ROOT FOLDER and include from wp-config.php
*/

$WPR_DEBUG_SKIP = array(
  '2dd2d83fd686c65a725f278a757512fa', // SimplePie
);

define('WP_DEBUG', true);
define('SCRIPT_DEBUG', true);

error_reporting(E_ALL);
ini_set('display_errors','Off');
ini_set('error_log',dirname(__FILE__).'/error.log');

@require(dirname(__FILE__).'/wp-local-dev/debug.php');
if(!function_exists('dprint'))
{
  function dprint($s,$should_exit=false)
  {
    error_log("Debugging not available. Install git@github.com:/WP-Results/wp-local-dev.git");
  }
}

define('DB_NAME', 'wordpress');
define('DB_USER', 'dev');
define('DB_PASSWORD', 'dev');
define('DB_HOST', 'localhost');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$wp_url = 'http://localhost';
$meta = array(
  'siteurl'=>$wp_url,
  'home'=>$wp_url,
  'admin_email'=>'you@yourdomain.com',
);
foreach($meta as $k=>$v)
{
  $mysqli->query("UPDATE wp_options SET option_value = '{$v}' WHERE option_name = '{$k}'");
}

$mysqli->query(sprintf("update wp_users set user_email = '%s', user_pass='%s' where user_login = 'admin'",
  $meta['admin_email'],
  '$P$BeTcuDVW8ptcglqKUAVb1yV4jZhjl..'
));

define('FS_METHOD', 'direct');

