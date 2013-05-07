<?php
/*
 *
 * Veysi Ertekin <veysi.ertekin123{}gmail.com>
 *
 */

require 'config.php';
require 'functionsAndClasses.php';



$commands = array(0 => "rm ".dirname(__FILE__)."/xmls/cikti.xml",
                  1 => "nmap -sS -O -oX ".dirname(__FILE__)."/xmls/output.xml ".$nmap,
                  2 => "php ".dirname(__FILE__)."/xml2mysql.php");

$i=0;

$process = new Process();

while($i<count($commands))
{
	if (!$process->status())
	{
		$process->runCom($commands[$i]);
		$i++;
	}
	else sleep(3); // sleep 3 seconds
}
