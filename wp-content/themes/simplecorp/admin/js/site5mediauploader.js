//PORTFOLIO BUTTON
jQuery(document).ready(function() {
jQuery('#snbp_pitembutton').click(function() {
	window.send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src');
		jQuery('#snbp_pitemlink').val(imgurl);
		jQuery('#snbp_pitemlink_img').attr('src',imgurl + '?' + Math.random());
		tb_remove();
	}

	formID = jQuery(this).attr('rel');
	tb_show('', 'media-upload.php?post_ID='+formID+'&type=image&TB_iframe=true');
 	return false;
});



//FEATURED BUTTON
jQuery('#snbf_fitembutton').click(function() {
	window.send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src');
		jQuery('#snbf_slideimage_src').val(imgurl);
		jQuery('#snbf_slideimage_src_img').attr('src',imgurl +'?'+Math.random());
		tb_remove();
	}

	formID = jQuery(this).attr('rel');
	tb_show('', 'media-upload.php?post_ID='+formID+'&type=image&amp;TB_iframe=true');
	return false;
});


//PAGE HEADER IMAGE BUTTON
jQuery('#snbpd_phitembutton').click(function() {
	window.send_to_editor = function(html) {

	 imgurl = jQuery('img',html).attr('src');

	 jQuery('#snbpd_phitemlink').val(imgurl);
	 jQuery('#snbpd_phitemlink_img').attr('src', '').show().attr('src',imgurl +'?'+Math.random());

	 tb_remove();
	}

	formID = jQuery(this).attr('rel');
	tb_show('', 'media-upload.php?post_ID='+formID+'&type=image&amp;TB_iframe=true');
	return false;
});

jQuery('.snbpd_remove_image').click(function() {
	jQuery('#snbpd_phitemlink').val('');
	jQuery('#snbpd_phitemlink_img').attr('src', '').slideUp();
	//jQuery('#snbpd_phitemlink_img').attr('src','');
});

});
