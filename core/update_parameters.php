<?php
#gt3_delete_theme_option("theme_version");

$theme_temp_version = gt3_get_theme_option("theme_version");

if ((int)$theme_temp_version < 5) {
	gt3_update_theme_option("custom.css_request_recompile_file", "yes");

	gt3_update_theme_option('default_fit_style', 'no_fit');
	gt3_update_theme_option('default_controls', 'on');
	gt3_update_theme_option('default_autoplay', 'on');
	gt3_update_theme_option('gallery_interval', 3000);

	gt3_update_theme_option("theme_version", 5);
}
?>