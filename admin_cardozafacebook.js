jQuery(document).ready(function()
{
	function loadfbIframe()
	{
		jQuery(".fb-preview").show();
		var fb_url=jQuery("[name=frm_url").val();
		fb_url=fb_url.replace(":","%3A");
		fb_url=fb_url.replace("/","%2F");
		fb_url="//www.facebook.com/plugins/likebox.php?href="+fb_url;
		var width=jQuery("[name=frm_width]").val();
		var height=jQuery("[name=frm_height]").val();
		var show_faces=jQuery("[name=frm_show_faces]").val();
		var header=jQuery("[name=frm_header]").val();
		var h_status="";
	
		var stream=jQuery("[name=frm_stream]").val();
		var border_color=jQuery("[name=frm_border_color]").val();
		
		if(header=="true")
			h_status="false";
		else
			h_status="true";
		
		var d_tabs='';
		if(stream=="true")
			d_tabs='&amp;tabs=timeline';
			
		
		
		var small_header=jQuery("[name=frm_small_header]").val();
	
		var frame='<iframe  src="'+fb_url+'&amp;width='+width+'&amp;height='+height+'&amp;show_posts=false&amp;show_faces='+show_faces+'&amp;stream='+stream+'&amp;hide_cover='+h_status+'&amp;small_header='+small_header + d_tabs +'"  scrolling="no"    frameborder="0" allowTransparency="true" height="'+height+'" style="border:1px solid #'+border_color+';overflow:hidden; width:'+width+'px; height:'+height+'px;" allowTransparency="true">';
		console.log("done");
		jQuery(".fb-preview").html(frame);
		
	 }
	 
	jQuery("[name='frm_url'],[name='frm_border_color'],[name='frm_width'],[name='frm_height']").blur(function()
	{
		
		loadfbIframe();
	
	});
	
	jQuery("[name='frm_show_faces'],[name='frm_stream'],[name='frm_header'],[name='frm_small_header']").change(function()
	{
		
			loadfbIframe();
	
	});
	
	loadfbIframe();
	 
});	 
	 