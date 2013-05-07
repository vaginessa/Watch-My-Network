<?php

/*
 *
 * Veysi Ertekin <veysi.ertekin123@gmail.com>
 * http://www.veysiertekin.com
 *
 */

error_reporting(E_ALL);

require 'functionsAndClasses.php';
require 'config.php';

if(file_exists(dirname(__FILE__)."/xmls/output.xml"))
{
    $xml = new xmlEngine();
    $xml->load(dirname(__FILE__)."/xmls/output.xml");

    //echo "<pre>";print_r($xml->getXMLArray());echo "</pre>";//xml verisi

    $timestamp = date("Y-m-d H:i:s");

    foreach($xml->getXMLArray() as $index00 => $value00)
    {
        switch ($index00)
        {
            case "@attributes":

                $result = mysql_num_rows(mysql_query("select * from browsingHistory where nmapStartstr='{$value00['startstr']}';"))+0; // Is this file worked previously?

                if($result>0)
                {
                    echo "\n<br /><font color=red>this file worked previously :(</font><br />\n";
                    exit;
                }
                else
                {
                    $result = mysql_query("INSERT INTO `browsingHistory` (`timestamp` ,`nmapStartstr` ,`instruction`)
                                             VALUES ('{$timestamp}' , '{$value00['startstr']}', '{$value00['args']}');");
                }
                break;
            case "host":
                $devicesInfos="INSERT INTO `deviceInfo` (`ID`, `timestamp`, `ipv4Address`, `ipv6Address`, `macAddress`, `os`, `macVendor`, `reason`, `hostName`, `hostType`) VALUES ";
                $portsInfos  ="INSERT INTO `portInfo` (`ID`, `timestamp`, `macAddress`, `ipv4Address`, `ipv6Address`, `portID`, `status`, `service`, `reason`, `reasonTTL`) VALUES ";
		

                foreach ($value00 as $index01 => $value01)
                {
                    if(isset($value00['status'])) // for single IP
                    {
                        $value01 = $value00;
                    }
                    if ($value01['status']['status']['state']=='down') { // device is up?
                        continue;
                    }

                    $ip="";
                    $ipv4="";
                    $ipv6="";
                    $mac ="";
                    $macVendor=("")?"":"";
                    $reason   =$value01['status']['status']['reason'];
                    $os       ="";
                    $hostname ="";
                    $hosttype ="";

                    if     (isset ($value01['os']['osmatch']['osmatch']))
                    {
                        $os = $value01['os']['osmatch']['osmatch']['name'];
                    }
                    else if(isset ($value01['os']['osmatch'][0]['osmatch']))
                    {
                        $os = $value01['os']['osmatch'][0]['osmatch']['name'];
                    }
                    if(isset ($value01['hostnames']['hostname']['hostname']))
                    {
                        $hostname =$value01['hostnames']['hostname']['hostname']['name'];
                        $hosttype =$value01['hostnames']['hostname']['hostname']['type'];
                    }

                    if(isset($value01['address']['address']))
                    {
                        switch ($value01['address']['address']['addrtype']) {
                            case "ipv4":
                                $ipv4=ip2long($value01['address']['address']['addr']);
                                $ip=$value01['address']['address']['addr'];
                                break;
                            case "ipv6":
                                if(function_exists("gmp_strval"))
                                {
                                    $ipv6=ip2long6($value01['address']['address']['addr']);
                                }
                                else
                                {
                                    $ipv6=$value01['address']['address']['addr'];
                                }
                                break;
                            case "mac":
                                $mac      =$value01['address']['address']['addr'];
                                $macVendor=$value01['address']['address']['vendor'];
                                break;
                        }
                    }
                    else
                    {
                        foreach ($value01['address'] as $key => $value)
                        {
                            switch ($value['address']['addrtype']) {
                                case "ipv4":
                                    $ipv4=ip2long($value['address']['addr']);
                                    $ip=$value['address']['addr'];
                                    break;
                                case "ipv6":
                                    if(function_exists("gmp_strval"))
                                    {
                                        $ipv6=ip2long6($value['address']['addr']);
                                    }
                                    else
                                    {
                                        $ipv6=$value['address']['addr'];
                                    }
                                    break;
                                case "mac":
                                    $mac      =$value['address']['addr'];
                                    $macVendor=$value['address']['vendor'];
                                    break;
                            }
                        }
                    }

                    if($reason=="localhost-response") // is my computer?
                    {
                        $output=array();
                        exec("ifconfig",$output);
                        foreach ($output as $key => $value) {
                            if(preg_match("/{$ip}/i", $value))
                            {
                                preg_match("/[a-fA-F0-9]{2}:[a-fA-F0-9]{2}:[a-fA-F0-9]{2}:[a-fA-F0-9]{2}:[a-fA-F0-9]{2}:[a-fA-F0-9]{2}/i", $output[$key-1],$yeni);
                                $mac=$yeni[0];
                                break;
                            }
                        }
                    }
                    $mac=strtoupper($mac);

                    $devicesInfos.="(NULL, '{$timestamp}','{$ipv4}','{$ipv6}','{$mac}','{$os}','{$macVendor}','{$reason}','{$hostname}','{$hosttype}'),";


                    if(isset ($value01['ports']['port']))
                    {
                        if(isset($value01['ports']['port']['@attributes']))
                        {
                            $portID     =$value01['ports']['port']['@attributes']['portid'];
                            if(isset($value01['ports']['port']['service']['service']))
                                $service    =$value01['ports']['port']['service']['service']['name'];
                            if(isset($value01['ports']['port']['state']['state']))
                            {
                                $status     =$value01['ports']['port']['state']['state']['state'];
                                $reason     =$value01['ports']['port']['state']['state']['reason'];
                                $reasonTTL  =$value01['ports']['port']['state']['state']['reason_ttl'];
                            }
                            $portsInfos.="(NULL, '{$timestamp}', '{$mac}', '{$ipv4}', '{$ipv6}', '{$portID}', '{$status}', '$service', '$reason', '$reasonTTL'),";
                        }
                        else
                        {
                            foreach ($value01['ports']['port'] as $index02 => $value02) //(NULL, '2011-01-05 04:02:22', 'khkg', '54', 'hgfh', 'hg', 'hg546', '6');
                            {
                                $portID     =$value02['@attributes']['portid'];
                                if(isset($value02['service']['service']))
                                    $service    =$value02['service']['service']['name'];
                                if(isset($value02['state']['state']))
                                {
                                    $status     =$value02['state']['state']['state'];
                                    $reason     =$value02['state']['state']['reason'];
                                    $reasonTTL  =$value02['state']['state']['reason_ttl'];
                                }
                                $portsInfos.="(NULL, '{$timestamp}', '{$mac}', '{$ipv4}',  '{$ipv6}', '{$portID}', '{$status}', '$service', '$reason', '$reasonTTL'),";
                            }
                        }
                    }
                    if(isset($value00['status'])) // for single IP
                    {
                        break;
                    }
                }
                $devicesInfos = rtrim($devicesInfos, ',').";";
                $portsInfos   = rtrim($portsInfos, ',').";";

                $result=mysql_query($devicesInfos);
                $result=mysql_query($portsInfos);

                //echo $devicesInfos."\n";
                //echo $portsInfos."\n";
                break;
            case "runstats":
                    $result = mysql_query("UPDATE `browsingHistory` SET `nmapEndstr` = '{$value00['finished']['finished']['timestr']}',`upIP`={$value00['hosts']['hosts']['up']},`downIP`={$value00['hosts']['hosts']['down']},`elapsed`='{$value00['finished']['finished']['elapsed']}' WHERE `timestamp` = '{$timestamp}';");
                break;
        }
    }
}
