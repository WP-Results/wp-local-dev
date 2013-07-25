<?php

$WPR_DEBUG_SKIP = array(
  '2dd2d83fd686c65a725f278a757512fa', // SimplePie
);

define('WP_DEBUG', true);
define('SCRIPT_DEBUG', true);

error_reporting(E_ALL);
ini_set('display_errors','Off');
ini_set('error_log',dirname(__FILE__).'/../error.log');

@require(dirname(__FILE__).'/wpr-dev/debug.php');

define('DB_NAME', 'wordpress');
define('DB_USER', 'dev');
define('DB_PASSWORD', 'dev');
define('DB_HOST', 'localhost');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$wp_url = 'http://dev.wordpressresults.com/clients/tomato/wondergrove';
$meta = array(
  'siteurl'=>$wp_url,
  'home'=>$wp_url,
  'admin_email'=>'you@yourdomain.com',
);
foreach($meta as $k=>$v)
{
  $mysqli->query("UPDATE wp_options SET option_value = '{$v}' WHERE option_name = '{$k}'");
}

define('FS_METHOD', 'direct');

