/* Clientcide Copyright (c) 2006-2009, http://www.clientcide.com/wiki/cnet-libraries#license*/

//Contents: Popup

//This lib: http://www.clientcide.com/js/build.php?excludeLibs[]=mootools-core&excludeLibs[]=mootools-more&require[]=Popup&compression=jsmin


Popup=new Class({Implements:[Options,Events],options:{width:500,height:300,x:50,y:50,toolbar:0,location:0,directories:0,status:0,scrollbars:'yes',resizable:1,name:'popup'},initialize:function(url,options){this.url=url||false;this.setOptions(options);if(this.options.x=='center')this.options.x=Math.floor((screen.availWidth-this.options.width)/2);if(this.options.y=='center')this.options.y=Math.floor((screen.availHeight-this.options.height)/2);if(this.url)this.openWin();},openWin:function(url){url=url||this.url;var options='toolbar='+this.options.toolbar+',location='+this.options.location+',directories='+this.options.directories+',status='+this.options.status+',scrollbars='+this.options.scrollbars+',resizable='+this.options.resizable+',width='+this.options.width+',height='+this.options.height+',top='+this.options.y+',left='+this.options.x;this.window=window.open(url,this.options.name,options);if(!this.window){this.window=window.open('',this.options.name,options);this.window.location.href=url;}
this.focus.delay(100,this);return this;},focus:function(){if(this.window)this.window.focus();else if(this.focusTries<10)this.focus.delay(100,this);else{this.blocked=true;this.fireEvent('onBlock');}
return this;},focusTries:0,blocked:null,close:function(){this.window.close();return this;}});
