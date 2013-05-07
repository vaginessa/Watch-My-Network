<?php
/*
 *
 *  Veysi Ertekin <veysi.ertekin123@gmail.com>
 *  http://www.veysiertekin.com
 *
 */

//$nmap ="";    nmap ip range: "192.168.1.50", "192.168.1.5/24 192.168.1.30/31" etc.

//$host ="";    MySQL host address: www.example.com,  www.example.com:3307  etc.
//$user ="";    MySQL user name
//$pass ="";    MySQL password
//$db   ="";    MySQL database name

$nmap ="192.168.1.1/30";

$host ="localhost";
$user ="root"; 
$pass ="root"; 
$db   ="watchmynetwork";

mysql_connect("$host","$user","$pass") or die ("Can not connect MySQL!");
mysql_select_db("$db") or die ("Can not select database!");
mysql_query("SET NAMES 'utf8'");
