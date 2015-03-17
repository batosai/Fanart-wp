<?php
if ( !post_password_required() ) {
get_header('fullscreen');
the_post();

/* LOAD PAGE BUILDER ARRAY */
$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
$pf = get_post_format();			
	wp_enqueue_script('gt3_fsGallery_js', get_template_directory_uri() . '/js/fs_gallery.js', array(), false, true);
	wp_enqueue_script('gt3_swipe_js', get_template_directory_uri() . '/js/jquery.event.swipe.js', array(), false, true);
    wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);

	$sliderCompile = "";
	if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['slides']) && is_array($gt3_theme_pagebuilder['sliders']['fullscreen']['slides'])) {

		if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['fit_style']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['fit_style'] !== 'default') {
			$fit_style = $gt3_theme_pagebuilder['sliders']['fullscreen']['fit_style'];
		} else {
			$fit_style = gt3_get_theme_option("default_fit_style");
		}
		if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['controls']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['controls'] !== 'default') {
			$controls = $gt3_theme_pagebuilder['sliders']['fullscreen']['controls'];
		} else {
			$controls = gt3_get_theme_option("default_controls");
		}
		if ($controls == 'on') {
			$controls = 'true';
		} else {
			$controls = 'false';
		}
		if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['thumbs']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['thumbs'] !== 'no') {
			$thmbs = 1;
		} else {
			$thmbs = 0;
		}
		if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['autoplay']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['autoplay'] !== 'default') {
			$autoplay = $gt3_theme_pagebuilder['sliders']['fullscreen']['autoplay'];
		} else {
			$autoplay = gt3_get_theme_option("default_autoplay");
		}
		if ($autoplay == 'on') {
			$autoplay = 'true';
		} else {
			$autoplay = 'false';
		}

		$interval = gt3_get_theme_option("gallery_interval");

		$sliderCompile .= '<script>gallery_set = [';
		foreach ($gt3_theme_pagebuilder['sliders']['fullscreen']['slides'] as $imageid => $image) {
			$uniqid = mt_rand(0, 9999);
			if (isset($image['title']['value']) && strlen($image['title']['value'])>0) {$photoTitle = $image['title']['value'];} else {$photoTitle = "";}
			if (isset($image['caption']['value']) && strlen($image['caption']['value'])>0) {$photoCaption  = $image['caption']['value'];} else {$photoCaption = "";}
			if (isset($image['title']['color']) && strlen($image['title']['color'])>0) {$titleColor = $image['title']['color'];} else {$titleColor = "ffffff";}
			if (isset($image['caption']['color']) && strlen($image['caption']['color'])>0) {$captionColor  = $image['caption']['color'];} else {$captionColor = "ffffff";}			
			$sliderCompile .= '{type: "image", image: "' . wp_get_attachment_url($image['attach_id']) . '", thmb: "'.aq_resize(wp_get_attachment_url($image['attach_id']), "130", "130", true, true, true).'", alt: "' . str_replace('"', "'",  $photoTitle) . '", title: "' . str_replace('"', "'", $photoTitle) . '", description: "' . str_replace('"', "'",  $photoCaption) . '", titleColor: "#'.$titleColor.'", descriptionColor: "#'.$captionColor.'"},';
		}
		$sliderCompile .= "]		
		jQuery(document).ready(function(){
			jQuery('html').addClass('hasPag');
			jQuery('body').fs_gallery({
				fx: 'fade', /*fade, zoom, slide_left, slide_right, slide_top, slide_bottom*/
				fit: '". $fit_style ."',
				slide_time: ". $interval .", /*This time must be < then time in css*/
				autoplay: ".$autoplay.",
				show_controls: ". $controls .",
				slides: gallery_set
			});
			jQuery('.fs_share').click(function(){
				jQuery('.fs_fadder').removeClass('hided');
				jQuery('.fs_sharing_wrapper').removeClass('hided');
				jQuery('.fs_share_close').removeClass('hided');
			});
			jQuery('.fs_share_close').click(function(){
				jQuery('.fs_fadder').addClass('hided');
				jQuery('.fs_sharing_wrapper').addClass('hided');
				jQuery('.fs_share_close').addClass('hided');
			});
			jQuery('.fs_fadder').click(function(){
				jQuery('.fs_fadder').addClass('hided');
				jQuery('.fs_sharing_wrapper').addClass('hided');
				jQuery('.fs_share_close').addClass('hided');
			});
	
			/*jQuery('.fs_controls').addClass('up_me');
			jQuery('.fs_title_wrapper').addClass('up_me');*/
		
			jQuery('.close_controls').click(function(){
				if (jQuery(this).hasClass('open_controls')) {
					jQuery('.fs_controls').removeClass('hide_me');
					jQuery('.fs_title_wrapper ').removeClass('hide_me');
					jQuery('.fs_thmb_viewport').removeClass('hide_me');
					jQuery('header.main_header').removeClass('hide_me');
					jQuery(this).removeClass('open_controls');
				} else {		
					jQuery('header.main_header').addClass('hide_me');
					jQuery('.fs_controls').addClass('hide_me');
					jQuery('.fs_title_wrapper ').addClass('hide_me');
					jQuery('.fs_thmb_viewport').addClass('hide_me');
					jQuery(this).addClass('open_controls');
				}
			});
		});
		</script>";
	
	echo "
	<div class='fs_fadder hided'></div>
	<div class='fs_sharing_wrapper hided is_gallery'>
		<div class='fs_sharing'>";?>
	
        <a target="_blank"
           href="http://www.facebook.com/share.php?u=<?php echo get_permalink(); ?>"
           class="share_facebook"><i class="icon-facebook-square"></i></a>
        <a target="_blank"
           href="http://pinterest.com/pin/create/button/?url=<?php echo get_permalink(); ?>&media=<?php echo (strlen($featured_image[0])>0) ? $featured_image[0] : gt3_get_theme_option("logo"); ?>"
           class="share_pinterest"><i class="icon-pinterest"></i></a>                                                            
        <a target="_blank"
           href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&amp;url=<?php echo get_permalink(); ?>"
           class="share_tweet"><i class="icon-twitter"></i></a>
        <a target="_blank"
           href="https://plus.google.com/share?url=<?php echo get_permalink(); ?>"
           class="share_gplus"><i class="icon-google-plus-square"></i></a>
		<a class='fs_share_close hided' href='<?php echo esc_js("javascript:void(0)");?>'></a>
	<?php echo "</div></div>";
		
	echo $sliderCompile;
	
	?>

	<?php
	if ($autoplay == 'false') {
		$playpause = "fs_play";
	} else {
		$playpause = "fs_pause";
	}
	if ($controls == 'false') {
		$show_class = 'hide_me';
		$controls_class = 'open_controls';
	} else {
		$show_class = '';
		$controls_class = '';
	}?>
    
	<div class="fs_controls <?php echo $show_class; ?>">
        <div class="fs-like <?php echo (isset($_COOKIE['like_album'.get_the_ID()]) ? "already_liked" : ""); ?>" data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_album">
            <i class="stand_icon <?php echo (isset($_COOKIE['like_album'.get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
        </div>        
    	<a href="<?php echo esc_js("javascript:void(0)");?>" class="fs_share"></a>
        <a href="<?php echo esc_js("javascript:void(0)");?>" class="fs_slider_prev"></a>
        <a href="<?php echo esc_js("javascript:void(0)");?>" id="fs_play-pause" class="<?php echo $playpause; ?>"></a>
        <a href="<?php echo esc_js("javascript:void(0)");?>" class="fs_slider_next"></a>
        <a href="<?php echo esc_js("javascript:void(0)");?>" class="close_controls <?php echo $controls_class; ?>"></a>
    </div>    
    <script>
		jQuery(document).ready(function($){
			jQuery('.main_header').removeClass('hided');
			jQuery('html').addClass('without_border');
			
            jQuery('.fs-like').click(function(){
				var gallery_likes_this = jQuery(this);
				if (!jQuery.cookie(gallery_likes_this.attr('data-modify')+gallery_likes_this.attr('data-attachid'))) {
					jQuery.post(gt3_ajaxurl, {
						action:'add_like_attachment',
						attach_id:jQuery(this).attr('data-attachid')
					}, function (response) {
						jQuery.cookie(gallery_likes_this.attr('data-modify')+gallery_likes_this.attr('data-attachid'), 'true', { expires: 7, path: '/' });
						gallery_likes_this.addClass('already_liked');
						gallery_likes_this.find('i').removeClass('icon-heart-o').addClass('icon-heart');
						gallery_likes_this.find('span').text(response);
					});
				}
            });			
		});	
	</script>

	<?php } else { ?>

    <div class="wrapper404">
        <div class="container404">
        	<h1 class="title_noimg"><?php echo __('No images found', 'theme_localization'); ?></h1>
            <div class="clear"></div>
        </div>        
    </div>
    <div class="custom_bg404 img_bg" style="background-image: url(<?php echo gt3_get_theme_option('bg_404'); ?>); ?>;"></div>
    <script>
		var wrapper404 = jQuery('.wrapper404');
		jQuery(document).ready(function(){
			centerWindow();
			html.addClass('error404');
		});
		jQuery(window).resize(function(){
			setTimeout('centerWindow()',500);
			setTimeout('centerWindow()',1000);			
		});
		function centerWindow() {
			setTop = (window_h - wrapper404.height())/2;
			wrapper404.css('top', setTop +'px');
			wrapper404.removeClass('fixed');
		}
	</script>
			
	<?php 
	}
	?>

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