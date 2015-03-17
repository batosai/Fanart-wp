<?php
/*
Template Name: Coming Soon
*/
if ( !post_password_required() ) {
?>
<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>">
    <?php echo((gt3_get_theme_option("responsive") == "on") ? '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">' : ''); ?>
    <link rel="shortcut icon" href="<?php echo gt3_get_theme_option('favicon'); ?>" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?php echo gt3_get_theme_option('apple_touch_57'); ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo gt3_get_theme_option('apple_touch_72'); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo gt3_get_theme_option('apple_touch_114'); ?>">
    <title><?php wp_title(); ?></title>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <script type="text/javascript">
        var gt3_ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    </script>
    <?php echo gt3_get_if_strlen(gt3_get_theme_option("custom_css"), "<style>", "</style>") . gt3_get_if_strlen(gt3_get_theme_option("code_before_head"));
    globalJsMessage::getInstance()->render();
    wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php 
$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID()); 
wp_enqueue_script('gt3_countdown', get_template_directory_uri() . '/js/jquery.countdown.min.js', array(), false, true); 
if (isset($gt3_theme_pagebuilder['countdown']['year'])) $year = $gt3_theme_pagebuilder['countdown']['year'];
if (isset($gt3_theme_pagebuilder['countdown']['day'])) $day = $gt3_theme_pagebuilder['countdown']['day'];
if (isset($gt3_theme_pagebuilder['countdown']['month'])) $month = $gt3_theme_pagebuilder['countdown']['month'];
?>

	<div class="global_count_wrapper">
    	<h1 class="count_title"><?php the_title(); ?></h1>
        <?php if (isset($year) && isset($day) && isset($month) && $year !== "" && $day !== "" && $month !== "") {?>
			<script>
                jQuery(function () {
                    var austDay = new Date();				
                    austDay = new Date(<?php echo $year ?>, <?php echo $month ?>-1, <?php echo $day ?>);
                    jQuery('#countdown').countdown({until: austDay});
                });
            </script>		
            <div id="countdown"></div>
		<?php } else {?>
        	<h1 class="count_error"><?php _e('Date has not been entered', 'theme_localization') ?></h1>
        <?php } ?>
        <?php if (isset($gt3_theme_pagebuilder['page_settings']['icons']) || (isset($gt3_theme_pagebuilder['countdown']['notify_text']) && $gt3_theme_pagebuilder['countdown']['notify_text'] !== '') || (isset($gt3_theme_pagebuilder['countdown']['shortcode']) && $gt3_theme_pagebuilder['countdown']['shortcode'] !== '')) { ?>
		<div class="count_container">
       		<?php if (isset($gt3_theme_pagebuilder['page_settings']['icons'])) {
				$ico_compile = '<div class="soc_icons">';
				foreach ($gt3_theme_pagebuilder['page_settings']['icons'] as $key => $value) {
					if ($value['link'] == '') $value['link'] = '#';
					$ico_compile .= '<a href="'.$value['link'].'" class="count_ico" title="'.$value['name'].'"><span><i class="'.$value['data-icon-code'].'"></i></span></a>';						
				}
				$ico_compile .= "</div>";
				echo $ico_compile;
           	} ?>
            <div class="form_area">
       		<?php if (isset($gt3_theme_pagebuilder['countdown']['notify_text']) && $gt3_theme_pagebuilder['countdown']['notify_text'] !== '') {
				echo "<h3 class='notify_text'>".$gt3_theme_pagebuilder['countdown']['notify_text']."</h3>";
			} ?>
       		<?php if (isset($gt3_theme_pagebuilder['countdown']['shortcode']) && $gt3_theme_pagebuilder['countdown']['shortcode'] !== '') {
				echo "<div class='notify_shortcode'>". do_shortcode($gt3_theme_pagebuilder['countdown']['shortcode'])."</div>";
			} ?>
            </div>
		</div>
		<?php }?>        
    </div>
	<?php 
	if ((isset($gt3_theme_pagebuilder['page_settings']['page_layout']['img']['attachid']) && $gt3_theme_pagebuilder['page_settings']['page_layout']['img']['attachid'] !== '') || (isset($gt3_theme_pagebuilder['page_settings']['page_layout']['color']['hash']) && $gt3_theme_pagebuilder['page_settings']['page_layout']['color']['hash'] !== '')) {?>
	    <div class="fixed_bg" style="background-image:url(<?php echo wp_get_attachment_url($gt3_theme_pagebuilder['page_settings']['page_layout']['img']['attachid']); ?>); background-color:#<?php echo esc_attr($gt3_theme_pagebuilder['page_settings']['page_layout']['color']['hash']); ?>"></div>
	<?php } else {
    	echo '<div class="fixed_bg" style="background-image:url('. gt3_get_theme_option('bg_img') .');"></div>';
	}?>    
    <script>
		jQuery(document).ready(function(){
			global_wrapper = jQuery('.global_count_wrapper');
			set_h = (jQuery(window).height()-global_wrapper.height())/2;
			if (set_h > 0) {
				global_wrapper.css('margin-top', (jQuery(window).height()-global_wrapper.height())/2);
			} else {
				global_wrapper.css('margin-top', 0);
			}
		});
		jQuery(window).resize(function(){
			global_wrapper = jQuery('.global_count_wrapper');
			set_h = (jQuery(window).height()-global_wrapper.height())/2;
			if (set_h > 0) {
				global_wrapper.css('margin-top', (jQuery(window).height()-global_wrapper.height())/2);
			} else {
				global_wrapper.css('margin-top', 0);
			}
		});
	</script>
<div class="preloader"></div>
<?php get_footer('fullscreen'); 
} else {
	get_header('fullscreen');
	echo "<div class='fixed_bg' style='background-image:url(".gt3_get_theme_option('bg_img').")'></div>";
?>
    <div class="pp_block">
        <div class="container">
        	<h1 class="pp_title"><?php  _e('THIS CONTENT IS PASSWORD PROTECTED', 'theme_localization') ?></h1>
            <div class="pp_wrapper">
				<?php the_content(); ?>
            </div>
        </div>
    </div>
    <div class="global_center_trigger"></div>	
    <script>
		jQuery(document).ready(function(){
			jQuery('.post-password-form').find('label').find('input').attr('placeholder', 'Enter The Password...');
			jQuery('html').addClass('without_border');
		});
	</script>
<?php 
	get_footer('fullscreen');
} ?>