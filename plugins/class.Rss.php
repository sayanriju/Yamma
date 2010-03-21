<?php
//include("../../views/class.SingleItem.php");
include("configurations/conf.Rss.php");
class RssPlugin{
	public $plugin_name="rss";
	public $disabled=0;
	public $features=array('personal'=>1,'network'=>0,'update'=>0);
		
	private $feedurl;
	function __construct(){
		$this->feedurl=func_get_arg(0);//$GLOBALS['feedurl'];
	}
	public function getPersonalStatus(){
		$itemlist=array();
		$rawFeed = file_get_contents($this->feedurl);
		$xml = new SimpleXmlElement($rawFeed);
		foreach ($xml->channel->item as $fitem){
			$item=new SingleItem();
			$item->service_name='rss';
			$item->title=$fitem->title;	
			//$item->image=$status->user->profile_image_url;
			$item->source_url=$fitem->link;
			//$item->service_image="http://www.feedicons.com/favicon.ico"; // TESTING LOCALLY
			$item->content=(string) trim($fitem->description);
			$item->datetime=$fitem->pubDate;
			$itemlist[]=$item;
			unset($item);
		}
		return $itemlist;
	}
	public function getNetworkStatus(){
		return array();
	}
	
	public function setStatus(){
		return NULL;
	}
	
}
/*
$obj=new RssPlugin();
$itemlist=$obj->getPersonalStatus();
foreach($itemlist as $item)
	echo $item->generateHTML();
*/
