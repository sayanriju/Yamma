<?php
session_start();
$user=$_SESSION['loggedinuser'];
if(!isset($user))
	echo "<script>window.location='index.php'</script>";
/*
 *      settings.php
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
	<title>Yamma Settings</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.17" />
	<link rel="stylesheet" type="text/css" href="styles/settings.css"/>
	<script type='text/javascript' src='lib/mootools.js'></script>
	<script type='text/javascript' src='lib/zebra.js'></script>
	<script type='text/javascript' src='lib/popup.js'></script>

	<script type='text/javascript'>
		window.addEvent('domready', function() {
			var SHOW_DEACTIVATE_WARNING=1;
			var zebraTables = new ZebraTables('zebra');
			$$('.homeicon').addEvent('click',function(){
				window.location="profile.php";
			});
			
			$$('.plugin_deactivate').addEvent('click',function(){
				var pluginname=this.name.split('_')[1];
				var deactivated=this.getProperty('checked');
				//alert(pluginname);
				if(deactivated==true){
					if(SHOW_DEACTIVATE_WARNING==1)	
						alert('If you choose to deactivate a plugin, you\'ll need to\nauthorize it again below, before you can use it!');
					SHOW_DEACTIVATE_WARNING=0;
					$('posting_'+pluginname).setProperty('disabled','disabled');;
					$('feeds_'+pluginname).setProperty('disabled','disabled');;
				}
				else{
					$('posting_'+pluginname).removeProperty('disabled');;
					$('feeds_'+pluginname).removeProperty('disabled');;
				}
			});
			
			$$('.auth_button').addEvent('click',function(){
				var plugin=this.id.split('_')[1];
				this.setProperty('disabled','disabled');
				var popup=new Popup('settings/'+plugin+'/index.php', {
					width:640,
					height:480,
					toolbar:0
				});
				(popup.window).addEvent('close',function(){
					window.location.reload();
				});
			});
		
		});
	</script>
</head>

<body>
<?php
include('plugins/inc.pluginlist.php');	// get $ALL_PLUGINS array
// get activated plugins
$active_plugins=array();
$xmlstr=file_get_contents("users/$user/plugins.xml");	
$xml= new SimpleXMLElement($xmlstr);
foreach($xml->plugin as $plugin)
	$active_plugins[]="$plugin->name";
unset($xmlstr,$xml);
// get plugins enabled for posting
$posting_plugins=array();
$xmlstr=file_get_contents("users/$user/plugins_update.xml");	
$xml= new SimpleXMLElement($xmlstr);
foreach($xml->plugin as $plugin)
	$posting_plugins[]="$plugin";
unset($xmlstr,$xml);
// get plguins enabled for fetching feeds
$feeds_plugins=array();
$xmlstr=file_get_contents("users/$user/plugins_feeds.xml");	
$xml= new SimpleXMLElement($xmlstr);
foreach($xml->plugin as $plugin)
	$feeds_plugins[]="$plugin";
unset($xmlstr,$xml);
?>


<div id='box'>
<img src='images/yamma.png' alt='Yamma!' />
<h2>Hello, <?php echo ucfirst($user);?>!</h2>
<b>Welcome to your Settings page</b><br/>
<img  class="homeicon" src="images/home.png" alt="Home" title="Return to viewing your profile" />
<div id="innerbox">
	<div id="active_services_box">
	<h2 style="text-align:left;">Active Plugins</h2>
	<p style="text-align:left;">You have activated (authorized) the following plugins:</p>
		<form id="active_services_form" method='post' action='actions/writeEnabledPluginsXML.php' >
		<table id="active_services_table" class="zebra" cols=4>
			<tr>
				<th style="width:40%;">Service Name</th>
				<th style="width:20%;">Enable Posting</th>
				<th style="width:30%;">Enable Fetching Feeds</th>
				<th style="width:30%;">Deactivate Plugin</th>
			</tr>
	<?php
		foreach($active_plugins as $plugin):
			$feed_checked=$posting_checked=''; 
			if(in_array("$plugin",$feeds_plugins))
				$feed_checked="checked='checked'";
			if(in_array("$plugin",$posting_plugins))
				$posting_checked="checked='checked'";	
	?>
			<tr>
				<td>
				<?php echo "<image src='images/favicons/$plugin.ico' alt='$plugin'/>&nbsp;&nbsp;".ucfirst($plugin);?>
				</td>
				<td>
					<input type='checkbox' name='posting_<?php echo "$plugin";?>' id='posting_<?php echo "$plugin";?>' <?php echo $posting_checked;?> />
				</td>
				<td>
					<input type='checkbox' name='feeds_<?php echo "$plugin";?>' id='feeds_<?php echo "$plugin";?>' <?php echo $feed_checked;?> />
				</td>
				<td>
					<input type='checkbox' name='deactivate_<?php echo "$plugin";?>' id='deactivate_<?php echo "$plugin";?>' class="plugin_deactivate"/>
				</td>
			</tr>	
	<?php 
		endforeach;
	?>			
		</table><br/>
		<input type='submit' id='submit_form' value='Save Settings' style="display:inline;"/>
		</form>
	</div>
	<br/><br/><br/><hr/><br/><br/>
	<div id="available_services_box" >
	<h2 style="text-align:left;">All Available Plugins</h2>
	<p style="text-align:left;">You can authorize (or RE-authorize) them below:<br/></p>
		<table id="available_services_table" class="zebra" cols=2>
			<tr>
				<th style="width:70%;">Service Name</th>
				<th style="width:30%;font-size:80%;">(Re)authorize Plugin</th>
			</tr>
		<?php
			foreach($ALL_PLUGINS as $plugin):
		?>
			<tr>
				<td>
				<?php echo "<image src='images/favicons/$plugin.ico' alt='$plugin'/>&nbsp;&nbsp;".ucfirst($plugin);?>
				</td>
				<td>
				<button id='authorize_<?php echo $plugin;?>' class="auth_button">Authorize</button>
				</td>
			</tr>		
		<?php
			endforeach;	
		?>	
		</table>
	<p style="font-size:80%;font-style:italic;"><b>Note:</b>&nbsp;A new popup window will open on clicking the authorize button</p>

	</div>
</div>
<img class="homeicon" src="images/home.png" alt="Home" title="Return to viewing your profile" />
</div>
</body>
</html>
