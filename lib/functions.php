<?php
function purgeEmpty($arr){
	foreach($arr as $key=>$value)
		if($value=='')
			unset($arr[$key]);
	return $arr;
}

function writePluginsEnabledXML($pluginlist,$filename){
	
	$xmlstr="<?xml version='1.0' encoding='iso-8859-1'?>\n<plugins_enabled>";
	foreach($pluginlist as $plugin)
		$xmlstr.="\n\t<plugin>$plugin</plugin>";
	$xmlstr.="\n</plugins_enabled>";
	$fp=fopen($filename,'w');
	fwrite($fp,$xmlstr);
	fclose($fp);
}

function writeLoginXML($newlogin,$newpass,$filename){
$xmlstr=file_get_contents($filename);
$oldxml=new SimpleXMLElement($xmlstr);
$newuser=$oldxml->addChild('user');
$newuser->addChild('login',$newlogin);
$newuser->addChild('pass_hash',$newpass);
$newxml=$oldxml->asXML();

$fp=fopen($filename,'w');
fwrite($fp,$newxml);
fclose($fp);
}

function getPluginCheckboxHTML($pluginlist,$no_of_cols=3){
$no_of_rows=count($pluginlist)/3+1;
$pcount=0;
$html="<table cols=3>\n";
	for($rownum=0;$rownum<$no_of_rows;$rownum+=1){
		$html.="<tr>\n";
		for($colnum=0;$colnum<$no_of_cols;$colnum+=1){
			$html.="<td>".$pluginlist[$pcount]."</td>\n";
			$pcount+=1;
		}
		$html.="<\tr>\n";
	}
$html.="</table>\n";
return $html;
}



