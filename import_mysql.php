<?php

/*
 *
 *  Veysi Ertekin <veysi.ertekin123{}gmail.com>
 *
 */


require './watchmynetwork/config.php';
require './watchmynetwork/functionsAndClasses.php';

$querys = file_get_contents("watchmynetwork.sql");

$querys=str_replace("\n","",$querys);

$querys=split(";",$querys);

foreach($querys as $query)
{
	mysql_query($query);
}

