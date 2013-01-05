<div class="wrap">
	<h2>Google Maps Plugin Options</h2>


<p>The Google Map plugin adds a “Add Map” icon to your visual editor. &nbsp;Once you’ve created your new map it is inserted into write Post / Page area as shortcode which looks like this: [map id="1"].</p>
<p>It also adds a widget so you can add maps to your sidebar (see Appearance &gt; Widgets).</p>
<?php if (!is_multisite()) { ?>
	<p>For more detailed instructions on how to use refer to <a target="_blank" href="http://premium.wpmudev.org/project/wordpress-google-maps-plugin/installation/">Google Maps Installation and Use instructions</a>.</p>
<?php } ?>

	<form action="options.php" <?php echo apply_filters('agm_google_maps-settings_form_options', '');?> method="post">
	<?php settings_fields('agm_google_maps'); ?>
	<?php do_settings_sections('agm_google_maps_options_page'); ?>
	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
	</p>
	</form>
</div>

<script type="text/javascript">
(function ($) {

$(function () {

	// Set up contextual help inline triggers
	$("[data-agm_contextual_trigger]").each(function () {
		var $me = $(this),
			$target = $($me.attr("data-agm_contextual_trigger"))
		;
		if (!$target.length) return false;
		$me.on("click", function () {
			$("#contextual-help-link").click();
			$target.find("a").click();
			$(window).scrollTop(0);
			return false;
		});
	});
});

})(jQuery);
</script>