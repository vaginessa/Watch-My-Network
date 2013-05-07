<?php
/* 
 *
 * This file has some ipv6 functions and a XML processing class.
 *
 * Veysi Ertekin <veysi.ertekin123@gmail.com>
 * http://www.veysiertekin.com
 *
 * http://www.php.net
 * 
 */


function ip2long6($ipv6) {
    $ip_n = inet_pton($ipv6);
    $bits = 15; // 16 x 8 bit = 128bit

    while ($bits >= 0) {
        $bin = sprintf("%08b",(ord($ip_n[$bits])));
        $ipv6long = $bin.$ipv6long;
        $bits--;
    }

    return gmp_strval(gmp_init($ipv6long,2),10);
}

function long2ip6($ipv6long) {

    $bin = gmp_strval(gmp_init($ipv6long,10),2);
    if (strlen($bin) < 128) {
        $pad = 128 - strlen($bin);
        for ($i = 1; $i <= $pad; $i++) {
            $bin = "0".$bin;
        }
    }

    $bits = 0;
    while ($bits <= 7) {
        $bin_part = substr($bin,($bits*16),16);
        $ipv6 .= dechex(bindec($bin_part)).":";
        $bits++;
    }

    return inet_ntop(inet_pton(substr($ipv6,0,-1)));
}

//
////important! use with 'function_exits':
//
//$ipv6 = "1111::ffff:ffff:ffff:ffff";
//
//if(function_exists("gmp_strval"))
//{
//    $ipv6long =  ip2long6($ipv6);
//    $ipv6 = long2ip6($ipv6long);
//
//    echo $ipv6long."\n";
//    echo $ipv6;
//}

class xmlEngine
{
    private static $xml = null;

    public function __construct() {
        $xml = array();
    }

    function amstore_xmlobj2array($obj, $level=0) {
        $items = array();

        if(!is_object($obj)) return $items;

        $child = (array)$obj;

        if(sizeof($child)>1) {
            foreach($child as $aa=>$bb) {
                if(is_array($bb)) {
                    foreach($bb as $ee=>$ff) {
                        if(!is_object($ff)) {
                            $items[$aa][$ee] = $ff;
                        } else
                        if(get_class($ff)=='SimpleXMLElement') {
                            $items[$aa][$ee] = $this->amstore_xmlobj2array($ff,$level+1);
                        }
                    }
                } else
                if(!is_object($bb)) {
                    $items[$aa] = $bb;
                } else
                if(get_class($bb)=='SimpleXMLElement') {
                    $items[$aa] = $this->amstore_xmlobj2array($bb,$level+1);
                }
            }
        } else
        if(sizeof($child)>0) {
            foreach($child as $aa=>$bb) {
                if(!is_array($bb)&&!is_object($bb)) {
                    $items[$aa] = $bb;
                } else
                if(is_object($bb)) {
                    $items[$aa] = $this->amstore_xmlobj2array($bb,$level+1);
                } else {
                    foreach($bb as $cc=>$dd) {
                        if(!is_object($dd)) {
                            $items[$obj->getName()][$cc] = $dd;
                        } else
                        if(get_class($dd)=='SimpleXMLElement') {
                            $items[$obj->getName()][$cc] = $this->amstore_xmlobj2array($dd,$level+1);
                        }
                    }
                }
            }
        }

        return $items;
    }

    function load($file)
    {
        $tmp=simplexml_load_file($file);

        $this->xml = $this->amstore_xmlobj2array($tmp);
    }

    function getXMLArray()
    {
        return $this->xml;
    }
}

class Process{
    private $pid;
    private $command;

    public function __construct(){
	$this->pid="0";
	$this->command="";
    }

    public function runCom($cl =false){
        if ($cl != false && !$this->status()){
		$this->command = $cl;
		$command = 'nohup '.$this->command.' > /dev/null 2>&1 & echo $!';
		exec($command ,$op);
		$this->setPid((int)$op[0]);
        }
    }

    public function setPid($pid){
        $this->pid = $pid;
    }

    public function getPid(){
        return $this->pid;
    }

    public function status(){
        $command = 'ps -p '.$this->pid;
        exec($command,$op);
        if (!isset($op[1]))return false;
        else return true;
    }

    public function start(){
        if ($this->command != '')$this->runCom($this->command);
        else return true;
    }

    public function stop(){
        $command = 'kill '.$this->pid;
        exec($command);
        if ($this->status() == false)return true;
        else return false;
    }
}
function base64_sql_encode($input) {
    return strtr(base64_encode($input), '+/=', '-_,');
}

function base64_sql_decode($input) {
    return base64_decode(strtr($input, '-_,', '+/='));
}

function dataPagination($sql,$page=1,$number_of_buttons=11,$limit=12,$pagename="page",$type) // the number of buttons must be odd
{
	$file_path=$_SERVER['PHP_SELF'];

	$result        = mysql_query($sql);
	$number_of_rows = mysql_num_rows($result);
	$number_of_pages = (int)($number_of_rows/$limit);
	if ($number_of_rows%$limit!=0) $number_of_pages +=1;
	if($page<=$number_of_pages && $page>0 && $number_of_rows!=0)
	{
		$result  = mysql_query($sql." limit ".(($page-1)*$limit).",".$limit);
		$array_=array();
		while(1){     
			$row=mysql_fetch_array($result);
			if(!$row) break;
			else        $array_[]=$row;
		}
		
		$links="";
		$links.='<div class="pagination">';
		$links.='<span class="pages">pages :</span>';
		if($page!=1)  $links.='<a href="#" alt="type='.$type.'&sql='.base64_sql_encode($sql).'&'.$pagename.'=1"><div class="pageno">(first)</div></a>';
		if($page-1>=1)$links.='<a href="#" alt="type='.$type.'&sql='.base64_sql_encode($sql).'&'.$pagename.'='.($page-1).'"><div class="pageno"> |prev|</div></a>';
		$adet=0;
		$control1=0;
		$control2=0;
		if($page-((int)($number_of_buttons/2))<=0)            $control1=1;
		if($page+((int)($number_of_buttons/2))>$number_of_pages) $control2=1;
		if($control1==1 && $control2==1)
		{
			for($j=1;$j<$number_of_pages+1;$j++)
			{
				if($page!=$j) $links.='<a href="#" alt="type='.$type.'&sql='.base64_sql_encode($sql).'&'.$pagename.'='.$j.'">';
				$links.='<div class="pageno"> '.$j.'</div>';
				if($page!=$j) $links.='</a>';
			}
		}
		elseif ($control1==1)
		{
			for($j=1;$j<=$number_of_buttons;$j++)
			{
				if($j>$number_of_pages) break;
				
				if($page!=$j) $links.='<a href="#" alt="type='.$type.'&sql='.base64_sql_encode($sql).'&'.$pagename.'='.$j.'">';
				$links.='<div class="pageno"> '.$j.'</div>';
				if($page!=$j) $links.='</a>';
			}
		}
		elseif ($control2==1)
		{
			$writing="";
			for($j=$number_of_pages;$j>$number_of_pages-$number_of_buttons;$j--)
			{
				if($j<1) break;
				$writing2="";
				if($page!=$j) $writing2.='<a href="#" alt="type='.$type.'&sql='.base64_sql_encode($sql).'&'.$pagename.'='.$j.'">';
				$writing2.='<div class="pageno"> '.$j.'</div>';
				if($page!=$j) $writing2.='</a>';
				$writing=$writing2.$writing;
			}
			$links=$links.$writing;
		}
		else
		{
			for($j=$page-((int)($number_of_buttons/2));$j<=$page+((int)($number_of_buttons/2));$j++)
			{
				if($j>$number_of_pages) break;
				if($page!=$j) $links.='<a href="#" alt="type='.$type.'&sql='.base64_sql_encode($sql).'&'.$pagename.'='.$j.'">';
				$links.='<div class="pageno"> '.$j.'</div>';
				if($page!=$j) $links.='</a>';
			}
		}
		if($page+1<=$number_of_pages)$links.='<a href="#" alt="type='.$type.'&sql='.base64_sql_encode($sql).'&'.$pagename.'='.($page+1).'"><div class="pageno"> |next|</div></a>';
		if($page!=$number_of_pages)  $links.='<a href="#" alt="type='.$type.'&sql='.base64_sql_encode($sql).'&'.$pagename.'='.($number_of_pages).'"><div class="pageno"> (last)</div></a>';
		$links.='</div>';
		return array($array_,$links);
	}
	else
	{
		return array(null,'<div style="color:yellow;font-weight:bold;text-shadow: 2px 1px 5px #000000;text-decoration:underline;width:100%;margin:30px 0px;text-align:center;">The specified page number is incorrect or no data in the table!!</div>');
	}
}
?>
