<?php
ob_start();
error_reporting(E_ALL);
ini_set("display_errors", 1); 

if(!isset($_SESSION)){session_start();}
include_once('config.php');

include_once('Date_Diff.php');
include_once('sendmail.php');
include_once('myclass.php');


$obj = new Myclass();
$m = new Mail();

?>