<?php
session_start();
$login=$_POST['login'];
//$login='default';
$pass_hash=$_POST['pass_hash'];
//$pass_hash="fe01ce2a7fbac8fafaed7c982a04e229";
//echo '************';
$xmlstr=file_get_contents("../users/login.xml");
$xml=new SimpleXMLElement($xmlstr);

$retcode=2;			// no such user in login database(default)
foreach($xml->user as $user){
	//echo "$user->login"."\n";
	if($user->login==$login){	// found required user entry
		if($user->pass_hash==$pass_hash){
			//echo $user->pass_hash,$user->login;
			$retcode=0;		// success
			$_SESSION['loggedinuser']=$login;
		}
		else
			$retcode=1;		// username/password mismatch
	
		break;				// break loop either way
	}
}

echo "$retcode";		// return $retcode to calling page (ajax)
//echo 'hello';
	
?>
