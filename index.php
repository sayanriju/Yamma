<?php
session_start();
if(isset($_SESSION['loggedinuser']))
	echo "<script>window.location='profile.php?user=".$_SESSION['loggedinuser']."'</script>"
	
/*
 *      profile.php
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Yamma Login</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.17" />
	<script type='text/javascript' src='lib/mootools.js'></script>
    <script type="text/javascript" src="lib/Tabs.js"></script>
	<script type="text/javascript" src="lib/md5.js"></script>
	<link rel='stylesheet' type='text/css' href='styles/general.css' />
<link rel='stylesheet' type='text/css' href='styles/tabs.css' />
	
	<script type='text/javascript'>
	window.addEvent('domready', function() {
		$('loginform').addEvent('submit',function(){
			//alert('ready');
		var login=$('login').value;
		var password=$('password').value;
		//alert(password);
		var pass_hash=MD5_hexhash(password);
		
		var req = new Request({
			method: 'POST',
			url: 'actions/logmein.php',
			data: { 'login' : login,'pass_hash':pass_hash},
			onRequest: function() { 
				//alert('request made');
				$('loginstatus').innerHTML="<img src='images/loading_bubbles.gif' alt='Login in progress...' />";
				$('loginstatus').setStyle('display','block')
			},
			onComplete: function(response) { 
				
				//alert(response);
				if(response==1){
					$('loginstatus').innerHTML="<b><red>Mismatching Username and Password!</red></b>";
					return false;
				}
				else if(response==2){
					$('loginstatus').innerHTML="<b><red>No such User!</red></b>";
					return false;
				}
				else
					window.location="profile.php?user="+login;
					
			}
			}).send();
		return false;	
		});
	});
</script>

<body>
	<div id="box" style="text-align:center;">	
	<img src='images/yamma.png' alt='Yamma!' /><br/>
	<h3>YAMMA's A Microblog Mashup App</h3>
		
		<div id='loginbox'>
			<form id='loginform' method='post' action='###'>
				<h3>Login Form</h3>
				<table rows=2 cols=2 style="margin:1em auto;">
					<tr>
						<td>Username:</td>
						<td><input id="login" type='text'/></td>
					</tr>					<tr>
						<td>Password:</td>
						<td><input id="password" type='password'/></td>
					</tr>
				</table>
				<div id='loginstatus' style='text-align:center;color:red;margin:1em;'></div>
				<input id='login_button' type='submit' value='Submit'/><br/><br/>
				<a id="registeruser" href="#" style="font-size:90%;color:black;font-weight:bold;">New User? Click here to Register.</a>
			</form>
		</div>
	</div>
</body>
</html>
