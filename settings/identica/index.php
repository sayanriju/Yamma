<?php
session_start();
$user=$_SESSION['loggedinuser'];
if(!isset($user))
	echo "<script>window.location='../../index.php'</script>";
?>

<body style="width:80%;margin:1em auto;text-align:center;" onunload="javascript:window.opener.location.reload();">
<?php
if(!isset($_POST['username'])){	// first time in page, show form
	$self=$_SERVER['PHP_SELF'];
		echo <<<EOT
		<h4><img src='../../images/favicons/identica.ico' alt='' />&nbsp;Please Enter You Credentials<br/>for login into identi.ca site</h4>
		<form name='identicaform' action ="$self" method="post">
		Username:&nbsp;<input type='text' name='username'/><br/><br/>
		Password:&nbsp;<input type='password' name='password'/><br/><br/>
		<input type='submit' value='Submit'/>
		</form>
EOT;
}
else{		//form submitted, save credentails
	$username=$_POST['username'];
	$password=$_POST['password'];
		// Load plugins list
	$xmlstr=file_get_contents("../../users/$user/plugins.xml");
	$xml=new SimpleXMLElement($xmlstr);
	//First check if the same plugin (identica) already exists
	foreach($xml->plugin as $plugin){
		$count=0;
		if($plugin->name=='identica'){
			unset($xml->plugin[$count]);	// remove old credentials
			break;
		}
	}
	// Now add credentials for new instance of plugin
	$newplugin=$xml->addChild('plugin');
	$newplugin->addChild('name','identica');
	$newplugin->addChild('username',"$username");
	$newplugin->addChild('password',"$password");

	// write to list of activated plugins
	$newxmlstr=$xml->asXML();
	file_put_contents("../../users/$user/plugins.xml",$newxmlstr);
	unset($xml,$xmlstr,$newxmlstr);
	// By default, enable posting 
	$xmlstr=file_get_contents("../../users/$user/plugins_update.xml");
	$xml=new SimpleXMLElement($xmlstr);
	//First check if the same plugin (identica) already exists
	foreach($xml as $plugin){
		$count=0;
		if($plugin=='identica'){
			unset($plugin[$count]);	// remove old entry
			break;
		}
	}
	// now add new entry
	$xml->addChild('plugin','identica');
	$xml->asXML("../../users/$user/plugins_update.xml");
	unset($xml,$xmlstr);

	// By default, enable fecthing feeds
	$xmlstr=file_get_contents("../../users/$user/plugins_feeds.xml");
	$xml=new SimpleXMLElement($xmlstr);
	//First check if the same plugin (identica) already exists
	foreach($xml as $plugin){
		$count=0;
		if($plugin=='identica'){
			unset($plugin[$count]);	// remove old credentials
			break;
		}
	}
	// noew add new entry
	$xml->addChild('plugin','identica');
	$xml->asXML("../../users/$user/plugins_feeds.xml");
	unset($xml,$xmlstr);
	
	
}
?>
</body>
