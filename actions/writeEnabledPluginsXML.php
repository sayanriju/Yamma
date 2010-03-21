<?php
session_start();
$user=$_SESSION['loggedinuser'];
if(!isset($user))
	echo "<script>window.location='index.php'</script>";

function writePluginsEnabledXML($pluginlist,$filename){	
	$xmlstr="<?xml version='1.0' encoding='iso-8859-1'?>\n<plugins_enabled>";
	foreach($pluginlist as $plugin)
		$xmlstr.="\n\t<plugin>$plugin</plugin>";
	$xmlstr.="\n</plugins_enabled>";
	file_put_contents($filename,$xmlstr);
	/*
	$fp=fopen($filename,'w');
	fwrite($fp,$xmlstr);
	fclose($fp);
	* */
}	

$feeds_plugins=array();
$posting_plugins=array();
$remove_plugins=array();
foreach(array_keys($_POST) as $p){
	$arr=explode('_',"$p");
	if($arr[0]=='posting')
		$posting_plugins[]="$arr[1]";
	elseif($arr[0]=='feeds')
		$feeds_plugins[]="$arr[1]";	
	elseif($arr[0]=='deactivate')
		$remove_plugins[]="$arr[1]";
}
if(count($remove_plugins)>0){		// there are plugins to deactivate
	foreach($remove_plugins as $rplugin){
	//remove from list of active plugins
		$xmlstr=file_get_contents("../users/$user/plugins.xml");
		$xml=new SimpleXMLElement($xmlstr);
		$count=0;
		foreach($xml as $plugin){
			if($plugin->name=="$rplugin"){
				unset($xml->plugin[$count]);
				break;
			}
			$count+=1;
		}
		file_put_contents("../users/$user/plugins.xml",$xml->asXML());
	}
}
writePluginsEnabledXML($feeds_plugins,"../users/$user/plugins_feeds.xml");
writePluginsEnabledXML($posting_plugins,"../users/$user/plugins_update.xml");
?>
<body style='text-align:center; width:70%;margin:3em auto;'>
<img src='../images/loading_bubbles.gif' alt='Saving Settings...'/>
<script>window.location.href='../settings.php'</script>
</body>
