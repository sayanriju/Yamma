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
$authenticateUrl = $twitterObj->getAuthenticateUrl();  
?>
<body style="width:80%;margin:1em auto;text-align:center;" onunload="javascript:window.opener.location.reload();">
<a href="<?php echo $authenticateUrl;?>">
<img src="../../images/sign-in-with-twitter.png" alt='sign in with twitter' style='border:none;outline:none;'/>
</a>
<br/><br/>
Note: You'll be prompted to login into Twitter if you are not already logged in
</body>
