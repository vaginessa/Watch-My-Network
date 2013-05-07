<?php
/*
 *
 * Veysi Ertekin <veysi.ertekin123{}gmail.com>
 *
 */

require 'config.php';
require 'functionsAndClasses.php';

if(isset($_GET['type']))
{
    switch ($_GET['type']) {
        case "browsingHistory":
            $data="";
            if(isset($_GET['delete']))
                $query = mysql_query ("DELETE FROM `browsingHistory` WHERE `timestamp` = '".$_GET['delete']."'");
            if(isset($_GET['page']))
                $data=  dataPagination(base64_sql_decode($_GET['sql']),$_GET['page'],11,13,"page",$_GET['type']);
            else
                $data=  dataPagination("select * from browsingHistory order by timestamp desc ",1,11,13,"page",$_GET['type']);

            echo "
            <div class='databox'>
            <h1>Browsing History</h1>
            <div class='boxheader' style='width:100px;'>Time Stamp</div>
            <div class='boxheader' style='width:260px;'>Instruction</div>
            <div class='boxheader'>Up IP</div>
            <div class='boxheader'>Down IP</div>
            <div class='boxheader'>Elapsed</div>
            <div class='boxheader'>Delete</div>
            <div style='clear:left;'></div>
            ";

            foreach ($data[0] as $value) {

                echo "
                    <div class='boxrow' style='width:100px;'>{$value['timestamp']}</div>
                    <div class='boxrow' style='width:260px;'>{$value['instruction']}</div>
                    <div class='boxrow'>{$value['upIP']}</div>
                    <div class='boxrow'>{$value['downIP']}</div>
                    <div class='boxrow'>{$value['elapsed']}</div>
                    <div class='boxrow'>
                            <a href='#' alt='type=browsingHistory&delete={$value['timestamp']}' title='Delete'>Delete</a>
                    </div>
                    <div style='clear:left;'></div>
                ";
            }
            echo "</div>".$data[1];
            break;

        case "newDeviceControl":
            $Y = (int)date("Y");
            $m = (int)date("m");
            $d = (int)date("d");
            $H = (int)date("H");
            $i = date("i");
            $s = date("s");

            switch ($_GET['time']) {
                case "01h":
                    $H -= 1;
                    break;
                case "01d":
                    $d -= 1;
                    break;
                case "10d":
                    $d -= 10;
                    break;
                case "01m":
                    $m -= 1;
                    break;
                case "06m":
                    $m -= 6;
                    break;
            }

            if($H<0)
            {
                $H=24;
                $d=$d-1;
            }
            if($d<=0)
            {
                $m=$m-1;
                if($m<=0)
                {
                    $m=12-$m*(-1);
                    $Y-=1;
                }
                $mounths = array (31,$Y%4==0?29:28,31,30,31,30,31,30,31,31,30,31);
                $d=$mounths - $d*(-1);
            }
            if($m<=0)
            {
                $m=12-$m*(-1);
                $Y-=1;
            }


            $H=($H<10)?"0".$H:"".$H;
            $d=($d<10)?"0".$d:"".$d;
            $m=($m<10)?"0".$m:"".$m;

            $timestamp = "{$Y}-{$m}-{$d} {$H}:{$i}:{$s}";

            $data="";
            if(isset($_GET['page']))
                $data=  dataPagination(base64_sql_decode($_GET['sql']),$_GET['page'],11,13,"page",$_GET['type']);
            else
                $data=  dataPagination("select distinct (macAddress) from deviceInfo where timestamp>'".$timestamp."' order by timestamp desc",1,11,13,"page",$_GET['type']);

            $new = array();

            foreach($data[0] as $value)
            {
                array_push($new,  mysql_fetch_array(mysql_query("select * from deviceInfo where macAddress='".$value['macAddress']."' order by timestamp desc limit 0,1")));
            }

            $data[0]=$new;

            echo "
            <div class='databox'>
            <h1>New Devices</h1>
            <div class='boxheader' style='width:110px;'>Last Connection Time</div>
            <div class='boxheader' style='width:65px;'>IPv4 Address</div>
            <div class='boxheader' style='width:100px;'>IPv6 Address</div>
            <div class='boxheader' style='width:100px;'>MAC Address</div>
            <div class='boxheader' style='width:100px;'>OS</div>
            <div class='boxheader'>Reason</div>
            <div class='boxheader' style='width:80px;'>Host Name</div>
            <div style='clear:left;'></div>
            ";

            foreach ($data[0] as $value) {

                echo "
                    <div class='boxrow' style='width:110px;'>{$value['timestamp']}</div>
                    <div class='boxrow' style='width:65px;'><a href='#' alt='type=portList&timestamp={$value['timestamp']}&ipv4={$value['ipv4Address']}'>".long2ip($value['ipv4Address'])."</a></div>
                    <div class='boxrow' style='width:100px;'><a href='#' alt='type=portList&timestamp={$value['timestamp']}&ipv6={$value['ipv6Address']}' title='".long2ip6($value['ipv6Address'])."'>".long2ip6($value['ipv6Address'])."</a></div>
                    <div class='boxrow' style='width:100px;'>{$value['macAddress']}</div>
                    <div class='boxrow' style='width:100px;'>{$value['os']}</div>
                    <div class='boxrow'>{$value['reason']}</div>
                    <div class='boxrow' style='width:80px;'>{$value['hostName']}</div>
                    <div style='clear:left;'></div>
                ";
            }
            echo "</div>".$data[1];
            break;
        case "portList":
            $and="";
            $header="";
            $ip;
            if(isset($_GET['ipv4']))
            {
                $header="IPv4";
                $and="ipv4Address='".$_GET['ipv4']."'";
                $ip=long2ip($_GET['ipv4']);
            }
            else
            {
                $header="IPv6";
                $and="ipv6Address='".$_GET['ipv6']."'";
                $ip=long2ip6($_GET['ipv6']);
            }

            $data="";
            if(isset($_GET['page']))
                $data=  dataPagination(base64_sql_decode($_GET['sql']),$_GET['page'],11,13,"page",$_GET['type']);
            else
                $data=  dataPagination("select * from `portInfo` WHERE `timestamp` = '".$_GET['timestamp']."' and {$and} order by timestamp desc, macAddress desc , portID asc ",1,11,13,"page",$_GET['type']);

            echo "
            <div class='databox'>
            <h1>Open Ports ".(isset($_GET['page'])?"":"(ip address : {$ip})")."</h1>
            <div class='boxheader' style='width:100px;'>Time Stamp</div>
            <div class='boxheader' style='width:100px;'>MAC Address</div>
            <div class='boxheader'>Port ID</div>
            <div class='boxheader'>Status</div>
            <div class='boxheader' style='width:150px;'>Service</div>
            <div class='boxheader'>Reason</div>
            <div class='boxheader'>TTL</div>
            <div style='clear:left;'></div>
            ";

            foreach ($data[0] as $value) {

                echo "
                    <div class='boxrow' style='width:100px;'>{$value['timestamp']}</div>
                    <div class='boxrow' style='width:100px;'>{$value['macAddress']}</div>
                    <div class='boxrow'>{$value['portID']}</div>
                    <div class='boxrow'>{$value['status']}</div>
                    <div class='boxrow' style='width:150px;'>{$value['service']}</div>
                    <div class='boxrow'>{$value['reason']}</div>
                    <div class='boxrow'>{$value['reasonTTL']}</div>
                    <div style='clear:left;'></div>
                ";
            }
            echo "</div>".$data[1];
            break;
        case "ipMacChanging":
            if (isset($_GET['data']))
            {
                $type=($_GET['data']=="ip")?"ipv4Address":"macAddress";
                $result=mysql_query("select DISTINCT {$type} from deviceInfo order by {$type} asc");
                $content="";
                while($row=  mysql_fetch_array($result))
                {
                    if($_GET['data']=="ip")
                        $content.="<option value='{$row[$type]}'>".  long2ip($row[$type])."</option>";
                    else
                        $content.="<option value='{$row[$type]}'>".  $row[$type]."</option>";
                }
                echo $content;
            }
            else
            {
                $data="";
                $type=(isset($_GET['ip']))?"ipv4Address":"macAddress";
                $goal=(isset($_GET['ip']))?"macAddress":"ipv4Address";

                $header=(isset($_GET['ip']))?"Mac Address":"IP Address";

                if(isset($_GET['page']))
                    $data=  dataPagination(base64_sql_decode($_GET['sql']),$_GET['page'],11,13,"page",$_GET['type']);
                else
                    $data=  dataPagination("select DISTINCT ({$goal}) from deviceInfo where {$type}='{$_GET['list']}' order by timestamp desc",1,11,13,"page",$_GET['type']);

                $new = array();

                foreach($data[0] as $value)
                {
                    array_push($new,  mysql_fetch_array(mysql_query("select * from deviceInfo where {$goal}='".$value[0]."' order by timestamp desc limit 0,1")));
                }

                $data[0]=$new;

                echo "
                <div class='databox'>
                <h1>Change List ".(isset($_GET['page'])?"":"({$type} : ".(isset($_GET['ip'])?long2ip($_GET['list']):$_GET['list'])." )")."</h1>
                <div class='boxheader' style='width:80px;'>Time Stamp</div>
                <div class='boxheader' style='width:120px;'>{$header}</div>
                <div class='boxheader' style='width:120px;'>MAC Vendor</div>
                <div class='boxheader' style='width:100px;'>OS</div>
                <div class='boxheader'>Reason</div>
                <div class='boxheader'>Host Name</div>
                <div class='boxheader'>Host Type</div>
                <div style='clear:left;'></div>
                ";

                foreach ($data[0] as $value) {

                    echo "
                        <div class='boxrow' style='width:80px;'>{$value['timestamp']}</div>
                        <div class='boxrow' style='width:120px;'><a href='#' alt='type=portList&timestamp={$value['timestamp']}&ipv4={$value['ipv4Address']}'>".(isset($_GET['ip'])?$value[$goal]:long2ip($value[$goal]))."</a></div>
                        <div class='boxrow' style='width:120px;'>{$value['macVendor']}</div>
                        <div class='boxrow' style='width:100px;'>{$value['os']}</div>
                        <div class='boxrow'>{$value['reason']}</div>
                        <div class='boxrow'>{$value['hostName']}</div>
                        <div class='boxrow'>{$value['hostType']}</div>
                        <div style='clear:left;'></div>
                    ";
                }
                echo "</div>".$data[1];
            }
            break;
        case "vlanData":
            $data="";

            $ip1=ip2long("".$_GET['ip1'].".".$_GET['ip2'].".".$_GET['ip3'].".".$_GET['ip4']."");
            $ip2=ip2long("".$_GET['ip5'].".".$_GET['ip6'].".".$_GET['ip7'].".".$_GET['ip8']."");

            if(isset($_GET['page']))
                $data=  dataPagination(base64_sql_decode($_GET['sql']),$_GET['page'],11,13,"page",$_GET['type']);
            else
                $data=  dataPagination("select * from deviceInfo where ipv4Address between '{$ip1}' and '{$ip2}' or ipv4Address = '{$ip1}' or ipv4Address = '{$ip2}' order by timestamp desc ",1,11,13,"page",$_GET['type']);

            echo "
            <div class='databox'>
            <h1>Vlan History</h1>
            <div class='boxheader' style='width:100px;'>Time Stamp</div>
            <div class='boxheader' style='width:150px;'>IP Address</div>
            <div class='boxheader' style='width:110px;'>Mac Address</div>
            <div class='boxheader' style='width:110px;'>Mac Vendor</div>
            <div class='boxheader'>Host Name</div>
            <div class='boxheader'>Host Type</div>
            <div style='clear:left;'></div>
            ";

            foreach ($data[0] as $value) {

                echo "
                    <div class='boxrow' style='width:100px;'>{$value['timestamp']}</div>
                    <div class='boxrow' style='width:150px;'><a href='#' alt='type=portList&timestamp={$value['timestamp']}&ipv4={$value['ipv4Address']}'>".long2ip($value['ipv4Address'])."</a></div>
                    <div class='boxrow' style='width:110px;'>{$value['macAddress']}</div>
                    <div class='boxrow' style='width:110px;'>{$value['macVendor']}</div>
                    <div class='boxrow'>{$value['hostName']}</div>
                    <div class='boxrow'>{$value['hostType']}</div>
                    <div style='clear:left;'></div>
                ";
            }
            echo "</div>".$data[1];
            break;
        case "osData":

            $string=($_GET['os2']!="")?$_GET['os2']:$_GET['os1'];

            $string=split(" ",$string);
            $keys="";

            foreach($string as $value)
            {
                $keys .= "or os like '%".$value."%' ";
            }

            $keys=trim($keys,"or");

            $data="";

            if(isset($_GET['page']))
                $data=  dataPagination(base64_sql_decode($_GET['sql']),$_GET['page'],11,13,"page",$_GET['type']);
            else if(isset($_GET['different']))
            {
                $data=  dataPagination("select DISTINCT os from deviceInfo where os <> '' order by timestamp desc ",1,11,13,"page",$_GET['type']);
                $new = array();

                foreach($data[0] as $value)
                {
                    array_push($new,  mysql_fetch_array(mysql_query("select * from deviceInfo where os='".$value[0]."' order by timestamp desc limit 0,1")));
                }

                $data[0]=$new;
            }
            else
                $data=  dataPagination("select * from deviceInfo where {$keys} and os <> ''  order by timestamp desc ",1,11,13,"page",$_GET['type']);

            echo "
            <div class='databox'>
            <h1>OS Search (total rows: ".count($data[0]).")</h1>
            <div class='boxheader' style='width:100px;'>Time Stamp</div>
            <div class='boxheader' style='width:160px;'>IPv4 Address</div>
            <div class='boxheader' style='width:110px;'>Mac Address</div>
            <div class='boxheader' style='width:300px;'>Operating System</div>
            <div style='clear:left;'></div>
            ";

            foreach ($data[0] as $value) {

                echo "
                    <div class='boxrow' style='width:100px;'>{$value['timestamp']}</div>
                    <div class='boxrow' style='width:160px;'><a href='#' alt='type=portList&timestamp={$value['timestamp']}&ipv4={$value['ipv4Address']}'>".long2ip($value['ipv4Address'])."</a></div>
                    <div class='boxrow' style='width:110px;'>{$value['macAddress']}</div>
                    <div class='boxrow' style='width:300px;'>{$value['os']}</div>
                    <div style='clear:left;'></div>
                ";
            }
            echo "</div>".$data[1];
    }
}