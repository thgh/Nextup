<?
$hostname = "localhost";
$username = "root";
$password = "usbw";		
$database = "nextup";		
mysql_connect($hostname,$username,$password) or die(mysql_error());
mysql_select_db($database);
?>