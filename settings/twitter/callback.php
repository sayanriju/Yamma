<?php
session_start();
$user=$_SESSION['loggedinuser'];
if(!isset($user))
	echo "<script>window.location='../../index.php'</script>";
	
include '../../plugins/helpers/EpiCurl.php';
include '../../plugins/helpers/EpiOAuth.php';
include '../../plugins/helpers/EpiTwitter.php';
include '../../plugins/configurations/conf.Twitter.php';

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);  

$twitterObj->setToken($_GET['oauth_token']);
$token = $twitterObj->getAccessToken();
//var_dump($token);
//$twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
$oauth_token=$token->oauth_token;
$oauth_token_secret=$token->oauth_token_secret;
//echo '***'.$ouath_token;
// Load plugins list
$xmlstr=file_get_contents("../../users/$user/plugins.xml");
$xml=new SimpleXMLElement($xmlstr);
//First check if the same plugin (twitter) already exists
foreach($xml->plugin as $plugin){
	$count=0;
	if($plugin->name=='twitter'){
		unset($xml->plugin[$count]);	// remove old credentials
		break;
	}
}
// Now add credentials for new instance of plugin
$newplugin=$xml->addChild('plugin');
$newplugin->addChild('name','twitter');
$newplugin->addChild('oauth_access_token',"$oauth_token");
$newplugin->addChild('oauth_access_token_secret',"$oauth_token_secret");

// write to list of activated plugins
$newxmlstr=$xml->asXML();
file_put_contents("../../users/$user/plugins.xml",$newxmlstr);
unset($xml,$xmlstr,$newxmlstr);
// By default, enable posting 
$xmlstr=file_get_contents("../../users/$user/plugins_update.xml");
$xml=new SimpleXMLElement($xmlstr);
//First check if the same plugin (twitter) already exists
foreach($xml as $plugin){
	$count=0;
	if($plugin=='twitter'){
		unset($plugin[$count]);	// remove old entry
		break;
	}
}
// now add new entry
$xml->addChild('plugin','twitter');
$xml->asXML("../../users/$user/plugins_update.xml");
unset($xml,$xmlstr);

// By default, enable fecthing feeds
$xmlstr=file_get_contents("../../users/$user/plugins_feeds.xml");
$xml=new SimpleXMLElement($xmlstr);
//First check if the same plugin (twitter) already exists
foreach($xml as $plugin){
	$count=0;
	if($plugin=='twitter'){
		unset($plugin[$count]);	// remove old credentials
		break;
	}
}
// noew add new entry
$xml->addChild('plugin','twitter');
$xml->asXML("../../users/$user/plugins_feeds.xml");
unset($xml,$xmlstr);
?>
<body style="width:80%;margin:1em auto;text-align:center;" onunload="javascript:window.opener.location.reload();">
Settings Saved.<br/>
You may now <a href="javascript:window.close();">Close</a> this window.
</body>

