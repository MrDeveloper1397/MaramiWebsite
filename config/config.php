<?php
error_reporting(1);
if(!isset($_SESSION)){session_start();}
/*
Befone using this config file please 
configure the below constant values 
as per your requirement.

*/

/**************login system****************************************************************************************
*********/if(!isset($_SESSION['meauth'])){define("auth", false, true);}else{define("auth", false, true);}/**********
*******************************************************************************************************************/

//sql connection
$url = $_SERVER['SERVER_NAME'];
define("SERVER_NAME", $url, true);
date_default_timezone_set('Asia/Calcutta');
/*dont change it(if you are not providing any site name in admin panel then it will call)*/
define("mysite", "CMS Test", true);//dont change it
define("myshop", "CMS Test", true);
define("ADMIN_PANEL_NAME", "Admin Panel", true);
define("ttm", 2, true);
define("mycopyright","Copyright 2010 CMS Test. All Rights Reserved.", true);
define("DEFAULT_TEMPLATE1", "default", true);
//define('SITE_NAME', ((!empty($websitename)) ? $websitename : mysite));
//define('COPYRIGHT', ((!empty($copyright)) ? $copyright : mycopyright));

$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];
//echo $docRoot.'<br />';
//print_r(array($docRoot, 'library/config.php'));
$webRoot  = str_replace(array($docRoot, 'admin/config/config.php'), '', $thisFile);
$srvRoot  = str_replace('admin/config/config.php', '', $thisFile);
$webRoot = '/'.$webRoot;
define('WEB_ROOT', $webRoot);
define('SRV_ROOT', $srvRoot);
define('ADMIN_WEB_ROOT',$webRoot.'admin/');
define('ADMIN_SRV_ROOT',$srvRoot.'admin/');
$_SESSION['SRV_ROOT'] = $srvRoot;

if($url=='localhost' || $url == 'sys6' || $url == 'sys2')
{
/***
localhost configuration...
*/
/*##############################################################*/

//define SITE_PATH AND ADMIN_PATH FOR LOCAL HOST...

define("SITE_PATH", "http://$url".WEB_ROOT, true);
define("SITE_PATH1", "http://$url".WEB_ROOT, true);
define("ADMIN_PATH", "http://$url".WEB_ROOT."admin/", true);

// define host username  and password for mysql in localhost
define("MY_SQL_HOST", "", true);
define("MY_SQL_USERNAME", "", true);
define("MY_SQL_PWD", "", true);
define("MY_SQL_DB", "", true);


}
/*##############################################################*/
else{
/***
Server configuration...
*/
define("SITE_PATH", "http://foreedge.com/", true);
define("SITE_PATH1", "http://foreedge.com/", true);
define("ADMIN_PATH", "http://foreedge.com/admin/", true);
define("IMG", "/", true);
// define host username  and password for mysql in localhost
define("MY_SQL_HOST", "", true);
define("MY_SQL_USERNAME", "", true);
define("MY_SQL_PWD", "", true);
define("MY_SQL_DB", "", true);


}

$_SESSION['SITE_PATH'] = SITE_PATH;
define("THEME_DIR", SRV_ROOT.'theme/', true);
define("THEME_DIRS", WEB_ROOT.'theme/', true);






/**************************************************************************************************/


?>