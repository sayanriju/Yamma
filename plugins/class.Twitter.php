<?php
/*
 *      class.Twitter.php
 *      
 *      Copyright 2010 Sayan Chakrabarti <me@sayanriju.co.cc>
 *      
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *      
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */
?>

<?php
include 'helpers/EpiCurl.php';
include 'helpers/EpiOAuth.php';
include 'helpers/EpiTwitter.php';	
include("configurations/conf.Twitter.php");

				//include_once("../classes/class.SingleItem.php");

class TwitterPlugin extends EpiTwitter{
	public $plugin_name="twitter";
	public $disabled=0;
	public $features=array('personal'=>1,'network'=>1,'update'=>1);
	public $favicon;
	
	
	public function __construct($oauthToken,$oauthTokenSecret,$favicon='')	{
		parent::__construct($GLOBALS['consumer_key'],$GLOBALS['consumer_secret'],$oauthToken,$oauthTokenSecret);
		//		parent::__construct("ndvFZa81FGaS49OBMulBA", "GFFtJWcmobhfI4DhgVLbeSB7isyO9Oj2xMlJmApEzEU",    '14475497-XVPcDfAWLEhFTGtETO2h0LBbf6zARqkd3JhlAOZue', 'vE52jnsJNCO61ChqZ80ZOMzd7jHfvNe7nT4mkABVg4');
		if($favicon!='')
			$this->favicon=$favicon;
		else
			$this->favicon="images/favicons/".$this->plugin_name.".ico";			
	}
	

	public function getPersonalStatus(){
		$twitterInfo= $this->get_statusesUser_timeline();
		try{
			foreach($twitterInfo as $status){
				$item=new SingleItem();
				$item->service_name='twitter';
				$item->title=$status->user->name;	
				$item->image=$status->user->profile_image_url; 
				$item->source_url="http://twitter.com/".$status->user->screen_name;
				$item->service_image=$this->favicon;//"http://twitter.com/favicon.ico"; // TESTING LOCALLY
				$item->content=$status->text;
				$item->datetime=$status->created_at;
				$itemlist[]=$item;
				unset($item);
			}	
		}catch(EpiTwitterException $e){
			die();
		}
		return $itemlist;			
	}
	
	public function getNetworkStatus(){
		$twitterInfo= $this->get_statusesFriends_timeline();
		try{
			foreach($twitterInfo as $status){
				$item=new SingleItem();
				$item->service_name='twitter';
				$item->title=$status->user->name;	
				$item->image=$status->user->profile_image_url; 
				$item->source_url="http://twitter.com/".$status->user->screen_name;
				$item->service_image=$this->favicon;//"http://twitter.com/favicon.ico"; // TESTING LOCALLY
				$item->content=$status->text;
				$item->datetime=$status->created_at;
				$itemlist[]=$item;
				unset($item);
			}
		}catch(EpiTwitterException $e){
			die();
		}
		return $itemlist;			
	}
	
	function setStatus($txt){
		try{
			$this->post_statusesUpdate(array('status' => "$txt"));
		}catch(EpiTwitterException $e){
			//var_dump($e);
			die();
		}
	}
}

/*
$obj=new TwitterPlugin('14475497-XVPcDfAWLEhFTGtETO2h0LBbf6zARqkd3JhlAOZue', 'vE52jnsJNCO61ChqZ80ZOMzd7jHfvNe7nT4mkABVg4') or die('***');
//$obj->setStatus('hello');
$itemlist=$obj->getNetworkStatus();
foreach($itemlist as $item)
	echo $item->generateHTML();
/*

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret,
    '14475497-XVPcDfAWLEhFTGtETO2h0LBbf6zARqkd3JhlAOZue', 'vE52jnsJNCO61ChqZ80ZOMzd7jHfvNe7nT4mkABVg4');
$twitterInfo= $twitterObj->get_statusesFriends_timeline();
try{
  foreach($twitterInfo as $friend) {
	var_dump($friend);
	break;
  }
}catch(EpiTwitterException $e){
  echo $e->getMessage();
  echo 'oh no';
}
* */	
