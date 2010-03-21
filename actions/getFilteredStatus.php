<?php
$s=$_POST['services2show'];	// received from profile.php
$type=$_POST['type'];		// received from profile.php
$user=$_POST['usertoshow'];//$user='default';
if(!isset($user)||!isset($type))
	die("<h2>Cannot call this script directly!</h2>");
$services2show=explode(',',$s);
unset($s);

$filteredlist=array();
//$list4filtering=$_SESSION['list4filetering_'.$type];
// get list from file dump
$list4filtering=unserialize(file_get_contents("../users/".$user."/list4filtering_$type.dump"));
foreach($list4filtering as $item){
	if(in_array($item->service_name,$services2show))
		$filteredlist[]=$item;
}
// now generate required html
$tab_contents=array_chunk($filteredlist,$items_per_tab);
$no_of_tabs=count($tab_contents);

echo "
<div style='text-align:center'><h2>Showing ".ucfirst($type)." Status</h2></div>
<div id='filterbox'></div>
<div id='tabs'>
	 	<ul class='tabs_title'>"; // lists the tab headings (viz the tab numbers)
		for($tabnum=1;$tabnum<=$no_of_tabs;$tabnum+=1){
			echo "<li title='$tabnum'>".$tabnum."</li>";
		}
		echo "</ul>";

	// now for the actual tab contents
	for($tabnum=1;$tabnum<=$no_of_tabs;$tabnum+=1){
		echo "<div id='$tabnum' class='tabs_panel'>";
			foreach($tab_contents[$tabnum-1] as $item){
				//echo "hi!!".$item->service_image;
				echo $item->generateHTML();
			}
		echo "</div>";
	}
echo "</div>";
?>




