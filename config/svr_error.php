<?php
$conf_err_msg='';
$conf_err_msg .="<br /> Please check the configuration file 'config.php' <br />";
if(!$con){
	$conf_err_msg .='Could not connect to server: ' . mysql_error();	
	die($conf_err_msg);
}
if(!$db_selected){
	$conf_err_msg .="There is no database with the name '".MY_SQL_DB."' in the server. please create a database with the name of '".MY_SQL_DB."'<br />";
	die($conf_err_msg);
}


?>