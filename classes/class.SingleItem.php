<?php
class SingleItem{
	public $service_name;
	
	public $title;
	public $image='';
	public $source_url;	
	public $service_image;
	public $content;
	public $datetime;
	
	function generateHTML(){
		if($this->image!='')
			$img="<img src='$this->image' alt='image' />&nbsp;&nbsp;";
		$service_name=ucfirst($this->service_name);
		$html=<<<EOT
		<div class="single_item">
			<a href='$this->source_url'>
				<h4 class="single_item_title">$img&nbsp;$this->title</h4>
			</a>
			<hr class="single_item_hr"/>
			<span class="single_item_content">$this->content</span>
			<br/><br/>
			<img src='$this->service_image' alt='[$service_name]' title='$service_name' />&nbsp;
			Posted at:&nbsp;<span class="single_item_datetime">$this->datetime</span>			
		</div>
EOT;
		return $html;
	}
}
