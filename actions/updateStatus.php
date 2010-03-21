<?php
set_include_path("../plugins/:../classes/");
session_start();
//sleep(5);
$user=$_POST['user'];
$txt=$_POST['txt'];
if(!isset($user))
	die("<h2>Cannot call this script directly!</h2>");
	//header("HTTP/1.1 406 Not Acceptable"); 
	
//get the names of plugins which are enabled for fetching feeds
$xmlstr=file_get_contents("../users/".$user."/plugins_update.xml");
$xml=new SimpleXMLElement($xmlstr);
$plugins_enabled=array();
foreach($xml->plugin as $plugin)
	$plugins_enabled[]=$plugin;
unset($xml);
// Now get the stored plugin credentials for $user
$xmlstr=(file_get_contents("../users/".$user."/plugins.xml"));
$xml=new SimpleXMLElement($xmlstr);
include("class.SingleItem.php");
//include("class.ItemsPage.php");
$itemlist=array();
foreach($xml->plugin as $plugin){
	if(in_array("$plugin->name",$plugins_enabled)){	// check if plugin is enabled
		switch("$plugin->name"){
			case('rss'):
				include_once("class.Rss.php");
				$newplug=new RssPlugin($plugin->feedurl);
				//$tmplst=$newplug->getPersonalStatus();
				break;
			case('twitter'):
				include_once("class.Twitter.php");
				$newplug=new TwitterPlugin("$plugin->oauth_access_token","$plugin->oauth_access_token_secret");
				break;
			case('identica'):
				include_once("class.Identica.php");
				$newplug=new IdenticaPlugin($plugin->username,$plugin->password);
				//$tmplst=$newplug->getPersonalStatus();
				break;
		}
		//update status
		$newplug->setStatus("$txt") or die('error');
		unset($newplug)	;
	}
}
