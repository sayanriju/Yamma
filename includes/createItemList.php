<?php
session_start();
set_include_path("../plugins/:../classes/");
//sleep(1);
$type=$_POST['type']; //"personal";// personal or network
$user=$_POST['usertoshow'];//$user='default';
if(!isset($user)||!isset($type))
	die("<h2>Cannot call this script directly!</h2>");

//get the names of plugins which are enabled for fetching feeds
$xmlstr=file_get_contents("../users/".$user."/plugins_feeds.xml");
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
	if(in_array("$plugin->name",$plugins_enabled)){	// check if plugin is disabled
		switch("$plugin->name"){
			case('rss'):
				include_once("class.Rss.php");
				$newplug=new RssPlugin($plugin->feedurl);
				//$tmplst=$newplug->getPersonalStatus();
				break;
			case('twitter'):
				include_once("class.Twitter.php");
				$newplug=new TwitterPlugin("14475497-XVPcDfAWLEhFTGtETO2h0LBbf6zARqkd3JhlAOZue","vE52jnsJNCO61ChqZ80ZOMzd7jHfvNe7nT4mkABVg4");
				break;
			case('identica'):
				include_once("class.Identica.php");
				$newplug=new IdenticaPlugin($plugin->username,$plugin->password);
				//$tmplst=$newplug->getPersonalStatus();
				break;
		}	
		$func="get".ucfirst($type)."Status";
		$tmplst=$newplug->$func();
		unset($newplug);
		$itemlist=array_merge($itemlist,$tmplst);
	}
}
//$itemspage=new ItemsPage($itemlist);
@include("../config/site.php");
$items_per_tab=10;

$itemlist=array_slice($itemlist,0,120);		// can show maxmum 120 entries

//dump the itemlist to a file for future filtering w/o recalling API
$fp=fopen("../users/".$user."/list4filtering_$type.dump",'w');
fwrite($fp,serialize($itemlist));
fclose($fp);
//$_SESSION['list4filtering_'.$type]=$itemlist;		// store for future filetering w/o recalling API

$tab_contents=array_chunk($itemlist,$items_per_tab);
$no_of_tabs=count($tab_contents);


echo "
<!--<div style='text-align:center'><h2>Showing ".ucfirst($type)." Status</h2></div>-->
<div id='filterbox'></div>
<div id='tabs'>
	 	<ul class='tabs_title'>"; // lists the tab headings (viz the tab numbers)
		for($tabnum=1;$tabnum<=$no_of_tabs;$tabnum+=1){
			echo "<li title='$tabnum'>".$tabnum."</li>";
		}
		echo "</ul>";

	// now for the actual tab contents
	for($tabnum=1;$tabnum<=$no_of_tabs;$tabnum+=1){
		echo "<div id='$tabnum' class='tabs_panel'>";
			foreach($tab_contents[$tabnum-1] as $item){
				//echo "hi!!".$item->service_image;
				echo $item->generateHTML();
			}
		echo "</div>";
	}
echo "</div>";
?>
