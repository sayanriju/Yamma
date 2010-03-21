<?php
session_start();
$user=$_SESSION['loggedinuser'];
if(!isset($user))
	echo "<script>window.location='index.php'</script>";
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
	<title>Yamma Profile</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.17" />
	<script type='text/javascript' src='lib/mootools.js'></script>
    <script type="text/javascript" src="lib/Tabs.js"></script>
	<link rel='stylesheet' type='text/css' href='styles/general.css' />
	<link rel='stylesheet' type='text/css' href='styles/tabs.css' />
	
	<script type='text/javascript'>
	window.addEvent('domready', function() {
		//alert("<?php echo $_SESSION['loggedinuser'];?>")
		showStatus('<?php echo $user;?>','personal');	// make sure it runs with first page loading
		$('textarea_update').value='';		// clean update box on refresh
		function showStatus(usertoshow,type){
			var req = new Request({
				method: 'POST',
				//url: 'includes/createItemList.php',
				url: 'actions/getStatus.php',
				data: { 'usertoshow' : usertoshow,'type':type},
				onRequest: function() { $('feeds_loadingimage').setStyle('display','block')},
				onComplete: function(response) { 
					if(response!="\n"){
						$('feeds').innerHTML=response;
						$('feeds_loadingimage').setStyle('display','none');
						var myTabs=new Tabs('tabs');
					}
					else{
						alert('Something seems to be wrong!\n\nCheck your net connection and/or your settings.');
						$('feeds_loadingimage').setStyle('display','none');
					}
				}
			}).send();
		}
		
		
		function updateStatus(user,txt){	// coupling! :=(
			var req = new Request({
				method: 'POST',
				//url: 'includes/createItemList.php',
				url: 'actions/updateStatus.php',
				data: { 'user' : user,'txt':txt},
				onRequest: function() { 
					//$('button_update').innerHTML="<img src='images/loading.gif' alt='Updating...' />";
					$('button_update').setStyle('display','none');
					$('posting_update').setStyle('display','block');
				},
				onFailure: function(){
						alert('Something seems to be wrong!\n\nCheck your net connection and/or your settings.');
				},					
				onComplete: function(response) { 
					//if(response!='\n'){
						//$('button_update').innerHTML="Update Status";
						$('textarea_update').value='';
						//$('charcount').setStyle('display','none');
						$('charcount').innerHTML='';
					/*}
					else{
						alert('Something seems to be wrong!\n\nCheck your net connection and/or your settings.');
						//$('button_update').innerHTML="Update Status";
					}*/
					$('button_update').setStyle('display','block');
					$('posting_update').setStyle('display','none');
				}
			}).send();
		}
		// Show personal feeds
		$('personal_status_link').addEvent('click', function() { 
			showStatus('<?php echo $user;?>','personal');
			/*$('personal_status_link').setStyle('text-decoration', 'underline');
			$('network_status_link').setStyle('text-decoration', 'none');*/
			$('personal_status_link').set('styles', {
				'border-right': '3px solid black',
				'border-left': '3px solid black',
				'border-bottom': '3px solid black',
			});
			$('network_status_link').setStyle('border', 'none')
		});
		// Show network feeds
		$('network_status_link').addEvent('click', function() { 
			showStatus('<?php echo $user;?>','network');
			/*$('network_status_link').setStyle('text-decoration', 'underline');
			$('personal_status_link').setStyle('text-decoration', 'none');*/
			$('network_status_link').set('styles', {
				'border-right': '3px solid black',
				'border-left': '3px solid black',
				'border-bottom': '3px solid black',
			});
			$('personal_status_link').setStyle('border', 'none')
		});
		// Update status
		$('button_update').addEvent('click', function() {
			var txt=$('textarea_update').value;
			if(txt==''){
				alert("Write something first!");
				return false;
			}				
			updateStatus('<?php echo $user;?>',txt);
		});
		// char count
		$('textarea_update').addEvent('keyup',function(){
			//$('charcount').setStyle('display','inline');
			var cnt=$('textarea_update').value.length;
			$('charcount').innerHTML=cnt+" characters";
		});
		// toggle showing postservices_box
		$('select_posting_services').addEvent('click', function(event){
			if ($('postservices_box').getStyle('display')=='none')
				$('postservices_box').setStyle('display','block');
			else
				$('postservices_box').setStyle('display','none');			
		});
		// Go to settings page
		$('settings_button').addEvent('click',function(){
			window.location='settings.php';
		});
		// Logout
		$('logout_button').addEvent('click',function(){
			<?php //session_destroy();?>
			window.location="actions/logmeout.php";
			
		});
	});
	</script>
</head>
<br/>
<body>
	<div id="box">	
<!--	<h1>Yamma</h1>
	<h4>Yet Another Microblog Mashup App!</h4>-->
	<div id="infobox"><br/>
	<img id="logo" src='images/yamma.png' alt='Yamma!' /><br/><br/>
		<span id="settings_button" class="infobox_button">
			<img src='images/settings.png' alt='settings' title='Settings'/>
		</span>
		<span id="logout_button" class="infobox_button">
			<img src='images/logout.png' alt='logout' title='Logout'/>
		</span><br/>

	</div>
		<div id="updatebox">
		<b style="margin-left:1em">What&#146;s Up, <?php echo ucfirst($user);?>?</b>
			<br/>	
			<textarea id="textarea_update" ></textarea>
			<span id='select_posting_services'></span>
			<span id='charcount'></span>			
			<br/>
			<button id="button_update" onfocus="this.blur()"; style='display:block;'>Update Status</button>
			<img id="posting_update" src="images/loading_bubbles.gif" alt="Posting Update..." style="margin-left:1em;display:none;"/>
			<!--<img src="images/configure.png" alt="Select Posting Services" />-->
		<span id='toggle_services_box_posting' class="toggle_services_box" title="Select which services you want to post to (Default: All enabled)">
		</span>

		<div id='postservices_box' style="display:none;">
		fdghu
		djfdkjg
		</div>
		<div id="status_bar">
			<span id="personal_status_link" class="status_link" title="Click here to view your own status updates" 
						style="border-bottom:3px solid black;border-left:3px solid black;border-right:3px solid black;">
				Personal
			</span>
			<span id="network_status_link" class="status_link" title="Click here to view status updates from you and all people in your network">
				Network
			</span>
		</div>
		</div>
		<div id="feedsbox">		
		<!--<span id='toggle_services_box_feeds' class="toggle_services_box" title="Select which services you want to post to (Default: All enabled)">Feeds Services</span>-->

			<div id="feeds_loadingimage" style="text-align:center;margin:2em auto;display:none;">
				<img src='images/loading_bubbles.gif' alt='Loading....'/>
			</div>
			<div id="feeds"></div>
		</div>
	</div>
</body>
</html>
