<?php

// Option to load plugin defaults
if ( isset( $_POST['load_defaults'], $_POST['load_defaults_confirm'] ) ) {
    ultimate_tinymce_load_defaults();
}

function ultimate_tinymce_load_defaults() {
	
	$options = get_option('jwl_options_group1');
		$options['jwl_fontselect_field_id'] = '1';
		$options['jwl_fontsizeselect_field_id'] = '1';
		$options['jwl_cut_field_id'] = '1';
		$options['jwl_copy_field_id'] = '1';
		$options['jwl_paste_field_id'] = '1';
		$options['jwl_backcolorpicker_field_id'] = '1';
		$options['jwl_forecolorpicker_field_id'] = '1';
		$options['jwl_advhr_field_id'] = '1';
		$options['jwl_visualaid_field_id'] = '1';
		$options['jwl_anchor_field_id'] = '1';
		$options['jwl_search_field_id'] = '1';
		$options['jwl_replace_field_id'] = '1';
		$options['jwl_nonbreaking_field_id'] = '1';
		$options['jwl_mailto_field_id'] = '1';
		$options['jwl_layers_field_id'] = '1';
		$options['jwl_span_field_id'] = '1';
		$options['jwl_equation_field_id'] = '1';
		
		$options['jwl_fontselect_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_fontsizeselect_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_cut_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_copy_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_paste_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_backcolorpicker_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_forecolorpicker_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_advhr_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_visualaid_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_anchor_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_search_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_replace_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_nonbreaking_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_mailto_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_layers_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_span_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_equation_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_encode_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_directionality_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_ezimage_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		$options['jwl_images_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 3";}');
		
	$options2 = get_option('jwl_options_group2');
		$options2['jwl_styleselect_field_id'] = '1';
		$options2['jwl_tableDropdown_field_id'] = '1';
		$options2['jwl_emotions_field_id'] = '1';
		$options2['jwl_image_field_id'] = '1';
		$options2['jwl_preview_field_id'] = '1';
		$options2['jwl_cite_field_id'] = '1';
		$options2['jwl_attribs_field_id'] = '1';
		$options2['jwl_styleprops_field_id'] = '1';
		$options2['jwl_code_field_id'] = '1';
		$options2['jwl_codemagic_field_id'] = '1';
		$options2['jwl_html5_field_id'] = '1';
		$options2['jwl_media_field_id'] = '1';
		$options2['jwl_youtube_field_id'] = '1';
		$options2['jwl_youtubeIframe_field_id'] = '1';
		$options2['jwl_imgmap_field_id'] = '1';
		$options2['jwl_visualchars_field_id'] = '1';
		$options2['jwl_print_field_id'] = '1';
		$options2['jwl_cursor_field_id'] = '1';
		$options2['jwl_shortcodes_field_id'] = '1';
		$options2['jwl_loremipsum_field_id'] = '1';
		$options2['jwl_w3cvalidate_field_id'] = '1';
		
		$options2['jwl_styleselect_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_tableDropdown_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_emotions_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_image_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_preview_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_cite_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_attribs_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_styleprops_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_code_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_codemagic_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_html5_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_media_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_youtube_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_youtubeIframe_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_imgmap_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_visualchars_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_print_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_shortcodes_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_loremipsum_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_w3cvalidate_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_clker_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		$options2['jwl_acheck_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		
	$options9 = get_option('jwl_options_group9');
		$options9['jwl_wp_photo_album_field_id'] = '1';
		$options9['jwl_wp_photo_album_dropdown'] = unserialize('a:1:{s:3:"row";s:5:"Row 4";}');
		
	$options3 = get_option('jwl_options_group3');
		$options3['jwl_tinycolor_css_field_id'] = unserialize('a:1:{s:9:"tinycolor";s:7:"Default";}');
		$options3['jwl_signoff_field_id'] = 'This is a signoff example.';
		
	$options4 = get_option('jwl_options_group4');
		$options4['jwl_dashboard_widget'] = '1';
		$options4['jwl_admin_bar_link'] = '1';
		
		//$options['jwl_dashboard_options'] = unserialize('a:1:{s:28:"jwl_tinymce_dashboard_widget";a:1:{s:5:"items";i:5;}}');
	
	update_option('jwl_options_group1', $options);
	update_option('jwl_options_group2', $options2);
	update_option('jwl_options_group9', $options9);
	update_option('jwl_options_group3', $options3);
	update_option('jwl_options_group4', $options4);
}

function jwl_ultimate_tinymce_load_defaults() {
	?>
    <span class="content_wrapper_title"><?php _e('Load Default Settings','jwl-ultimate-tinymce'); ?></span>
    <form method="post">
	<input id="defaults" name="defaults" type="hidden" value="ultimate-tinymce/main.php" /> <?php  // The value must match the folder/file of the plugin.
    if ( isset( $_POST['load_defaults'] ) && ! isset( $_POST['load_defaults_confirm'] ) ) { 
	?><div id="message" class="error">
  			<p><?php _e('You must also check the confirm box before default options will be loaded.','jwl-ultimate-tinymce'); ?></p>
		</div>
 	  <?php
    }
	
	?><div style="display:block;float:left;width:60%;"><?php
	_e('<ul class="help_tab_list_image"><li>Load developers suggested default settings.</li><li>Basically, two additional rows will be added to the editor (rows 3 and 4).  Each with approximately 15 to 19 buttons each.</li><li>Options are still freely customizable, and can be tweaked after default settings are loaded.</li><li>Remember you might need to click the "show/hide kitchen sink" button in row 1 of your editor to expand row 2 buttons.</li></ul>','jwl-ultimate-tinymce'); ?>
    </div>
    <div style="display:block;float:left;width:40%;">
    <center><br /><br />
	<input name="load_defaults_confirm" type="checkbox" value="1" /> <?php _e('<b>Please confirm before proceeding</b><br /><br />','jwl-ultimate-tinymce'); ?>
	<input class="button-primary" name="load_defaults" type="submit" value="<?php _e('Load Defaults','jwl-ultimate-tinymce'); ?>" />
	</form>
    </center>
    </div>
<?php
}

// admin notice for not verifying to load defaults
if ( isset( $_POST['load_defaults'] ) && ! isset( $_POST['load_defaults_confirm'] ) ) {
	function jwl_top_admin_load_defaults_notice() {
		echo '<div id="message" class="error"><p>';
		_e('You must also check the confirm box before default options will be loaded.','jwl-ultimate-tinymce');
    	echo '</p></div>';
	}
	add_action('admin_notices','jwl_top_admin_load_defaults_notice');
}
?>