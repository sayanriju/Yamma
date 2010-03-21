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
include("helpers/identica.lib.php");	
include("configurations/conf.Identica.php");
//include("../../views/class.SingleItem.php");
class IdenticaPlugin extends Identica{
	public $plugin_name="identica";
	public $disabled=0;
	public $features=array('personal'=>1,'network'=>1,'update'=>1);
	
	//private $login="sayanriju";	//$GLOBALS['login'];
	//private $pass="khulja";	//$GLOBALS['pass'];
	function __construct(){
		//parent::__construct($login,$pass);
		$login=func_get_arg(0);
		$pass=func_get_arg(1);
		parent::__construct($login,$pass);		
	}
	
	private function parseXML($xml){
		$itemlist=array();
		$statuslist= new SimpleXMLElement($xml);
		foreach($statuslist->status as $status){
			$item=new SingleItem();
			$item->service_name='identica';
			$item->title=$status->user->name;	
			$item->image=$status->user->profile_image_url;
			$item->source_url="http://identica.com/".$status->user->screen_name;
			$item->service_image="http://identi.ca/favicon.ico";
			$item->content=$status->text;
			$item->datetime=$status->created_at;
			$itemlist[]=$item;
			unset($item);
		}	
		return $itemlist;
	}	
	
	public function getNetworkStatus(){
		$xml=$this->getFriendsTimeline();
		//testing locally!
		//$xml=file_get_contents('/tmp/tw.xml');
		//////
		return $this->parseXML($xml);
	}
	
	public function getPersonalStatus(){
		$xml=$this->getUserTimeline();
		return $this->parseXML($xml);
	}
	
	public function setStatus($txt){
		$txt=substr($txt,0,140);	// truncate
		$this->updateStatus("$txt");
	}
}
/*
$obj=new IdenticaPlugin('sayanriju','khulja');
$itemlist=$obj->getNetworkStatus();
foreach($itemlist as $item)
	echo $item->generateHTML();
*/
