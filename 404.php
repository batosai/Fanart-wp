<?php get_header('fullscreen');?>
    <div class="wrapper404">
        <div class="container404">
        	<h1 class="title404"><?php echo __('404 Error', 'theme_localization'); ?></h1>
            <form name="search_field" method="get" action="<?php echo home_url(); ?>" class="search_form search404">
                <input type="text" name="s" value="" class="field_search">
                <a href="<?php echo esc_js("javascript:document.search_field.submit()");?>" class="search_button"><i class="icon-search"></i><?php _e('Search', 'theme_localization'); ?></a>
            </form>
            <div class="clear"></div>
        </div>
    </div>
    <div class="custom_bg img_bg" style="background-position:center center;background-image: url(<?php echo gt3_get_theme_option('bg_img'); ?>); background-color:#<?php echo gt3_get_theme_option('default_bg_color'); ?>;"></div>
    <script>
		jQuery(document).ready(function(){
			jQuery('.wrapper404').css('margin-top', -1*(jQuery('.wrapper404').height()/2)+(jQuery('header.main_header').height()-30)/2);
		});
		jQuery(window).resize(function(){
			jQuery('.wrapper404').css('margin-top', -1*(jQuery('.wrapper404').height()/2)+(jQuery('header.main_header').height()-30)/2);
		});
	</script>
<?php get_footer('fullscreen'); ?>
