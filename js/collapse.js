jQuery(document).ready(function()
{
	jQuery('div.postbox.cd-postbox h3').click(function()
	{
		jQuery(this).parent().find('div.inside').toggle();	
	});
	
	
	jQuery('a[href=#restore]').click(function()
	{
		jQuery('#res-options').populate({"res_speed":800,"res_pause":6000,"res_auto":"true","res_continuous":"true","res_direction":"false","res_controlshow":"true","res_numeric":"true","res_nextid":"cd-res-next","res_previd":"cd-res-prev","res_nexttext":"Next &raquo;","res_prevtext":"&laquo; Previous","res_controlsid":"cd-res-controls","res_control_container_class":"cd-res-control-container"}, {resetForm:true});
		jQuery('input#res_disable_style').removeAttr('checked');
		jQuery('input#res_thumb_support').removeAttr('checked');
		jQuery('input#res_disable_script').removeAttr('checked');
	});
	
});