<?php 
/*
Template Name: Background Image/Video
*/
if ( !post_password_required() ) {
get_header('fullscreen');
the_post();

$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
$pf = get_post_format();
?>
	<?php 
	if (isset($gt3_theme_pagebuilder['bg_settings']['type'])) {
		if ($gt3_theme_pagebuilder['bg_settings']['type'] == 'bg_image') {
			echo "<div class='fullscreen_block fw_background bg_image' style='background-image:url(".$gt3_theme_pagebuilder['bg_settings']['image'].")'></div>";
		} else {
			$video_url = $gt3_theme_pagebuilder['bg_settings']['videourl'];
			echo "<div class='fullscreen_block fw_background bg_video'>";

			#YOUTUBE
			$is_youtube = substr_count($video_url, "youtu");
			if ($is_youtube > 0) {
				$videoid = substr(strstr($video_url, "="), 1);
				echo "<iframe width=\"100%\" height=\"100%\" src=\"http://www.youtube.com/embed/" . $videoid . "?controls=0&autoplay=1&showinfo=0&modestbranding=1&wmode=opaque&rel=0\" frameborder=\"0\" allowfullscreen></iframe></div>";
			}
		
			#VIMEO
			$is_vimeo = substr_count($video_url, "vimeo");
			if ($is_vimeo > 0) {
				$videoid = substr(strstr($video_url, "m/"), 2);
				echo "<iframe src=\"http://player.vimeo.com/video/" . $videoid . "?autoplay=1&loop=0\" width=\"100%\" height=\"100%\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>";
			}
		}
	}
	
	?>
    <script>
		jQuery(document).ready(function() {
			jQuery('.fw_background').height(jQuery(window).height());
			jQuery('.main_header').removeClass('hided');
			jQuery('.fullscreen_block').addClass('loaded');
			if (jQuery(window).width() > 1024) {
				if (jQuery('.bg_video').size() > 0) {
					if (((jQuery(window).height()+150)/9)*16 > jQuery(window).width()) {				
						jQuery('iframe').height(jQuery(window).height()+150).width(((jQuery(window).height()+150)/9)*16);
						jQuery('iframe').css({'margin-left' : (-1*jQuery('iframe').width()/2)+'px', 'top' : "-75px", 'margin-top' : '0px'});
					} else {
						jQuery('iframe').width(jQuery(window).width()).height(((jQuery(window).width())/16)*9);
						jQuery('iframe').css({'margin-left' : (-1*jQuery('iframe').width()/2)+'px', 'margin-top' : (-1*jQuery('iframe').height()/2)+'px', 'top' : '50%'});
					}
				}
			} else if (jQuery(window).width() < 760) {
				jQuery('.bg_video').height(jQuery(window).height()-jQuery('.main_header').height());
				jQuery('iframe').height(jQuery(window).height()-jQuery('.main_header').height());
			} else {
				jQuery('.bg_video').height(jQuery(window).height());
				jQuery('iframe').height(jQuery(window).height());				
			}
			jQuery('html').addClass('without_border');
		});
		jQuery(window).resize(function() {
			jQuery('.fw_background').height(jQuery(window).height());
			if (jQuery(window).width() > 1024	) {
				if (jQuery('.bg_video').size() > 0) {
					if (((jQuery(window).height()+150)/9)*16 > jQuery(window).width()) {
						jQuery('iframe').height(jQuery(window).height()+150).width(((jQuery(window).height()+150)/9)*16);
						jQuery('iframe').css({'margin-left' : (-1*jQuery('iframe').width()/2)+'px', 'top' : "-75px", 'margin-top' : '0px'});
					} else {
						jQuery('iframe').width(jQuery(window).width()).height(((jQuery(window).width())/16)*9);
						jQuery('iframe').css({'margin-left' : (-1*jQuery('iframe').width()/2)+'px', 'margin-top' : (-1*jQuery('iframe').height()/2)+'px', 'top' : '50%'});
					}
				}
			} else if (jQuery(window).width() < 760) {
				jQuery('.bg_video').height(jQuery(window).height()-jQuery('.main_header').height());
				jQuery('iframe').height(jQuery(window).height()-jQuery('.main_header').height());
			} else {
				jQuery('.bg_video').height(jQuery(window).height());
				jQuery('iframe').height(jQuery(window).height());				
			}
		});
	</script>

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