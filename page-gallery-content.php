<?php 
/*
Template Name: Gallery - Photo Listing
*/
if ( !post_password_required() ) {
get_header(); the_post();

$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');

wp_enqueue_script('gt3_prettyPhoto_js', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', array(), false, true);
wp_enqueue_script('gt3_masonry_js', get_template_directory_uri() . '/js/masonry.min.js', array(), false, true);
if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['gallery_style']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['gallery_style'] == "masonry") { 
	$gallery_type = 'is_masonry';
	$masonry_active = 'active';
	$column_active = '';
} else {
	$gallery_type = 'listing_gallery';
	$masonry_active = '';
	$column_active = 'active';
}
?>
<div class="content_wrapper">
	<div class="container">
        <div class="content_block row no-sidebar">
            <div class="fl-container">
                <div class="row">
                    <div class="posts-block">
					<?php if (!isset($gt3_theme_pagebuilder['settings']['show_title']) || $gt3_theme_pagebuilder['settings']['show_title'] !== "no") { ?>
                        <div class="page_title_block">
							<h1 class="title"><?php the_title(); ?></h1>
                        </div>
                    <?php } ?>
                    	<div class="gallery_toolbar">
                        	<a href="<?php echo esc_js("javascript:history.back()");?>" class="btn_back"><?php _e('Back', 'theme_localization'); ?></a>
                            <?php if (!isset($gt3_theme_pagebuilder['sliders']['fullscreen']['type_selector']) || $gt3_theme_pagebuilder['sliders']['fullscreen']['type_selector'] == "on") { ?>
                            <div class="gallery_type_selector">                            	
                                <a href="<?php echo esc_js("javascript:void(0)");?>" class="btn_gallery_masonry <?php echo $masonry_active; ?>"></a>
                                <a href="<?php echo esc_js("javascript:void(0)");?>" class="btn_gallery_column <?php echo $column_active; ?>"></a>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="contentarea">
	                        <div class="content_gallery <?php echo $gallery_type; ?>">
								<?php 
                                if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['slides']) && is_array($gt3_theme_pagebuilder['sliders']['fullscreen']['slides'])) {
                                    foreach ($gt3_theme_pagebuilder['sliders']['fullscreen']['slides'] as $imageid => $image) {
                                ?>
                                	<div class="cont_gallery_item">
                                    	<div class="cont_gallery_wrapper">
	                                        <div class="grid-gallery-item"><img src="<?php echo aq_resize(wp_get_attachment_url($image['attach_id']), "1170", "", true, true, true); ?>" alt="">
                                                <div class="gallery_fadder"></div>
                                                <span class="gallery_ico"><i class="stand_icon icon-eye"></i></span>
                                                <a href="<?php echo wp_get_attachment_url($image['attach_id']); ?>" class="prettyPhoto" rel="prettyPhoto[gallery77]"></a>                                            
                                            </div>
                                        </div>
                                    </div>
                                <?php	}
                                }		
                                ?>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php
        $GLOBALS['showOnlyOneTimeJS']['prettyPhoto'] = "
            <script>
                jQuery(document).ready(function($) {
                    jQuery('.prettyPhoto').prettyPhoto();
                });
            </script>
		";
	?>
    <script>
		jQuery(document).ready(function(){
			if (window_w < 760) {
				jQuery('.is_masonry').removeClass('is_masonry').addClass('listing_gallery');
				jQuery('.gallery_type_selector').hide();
			}
			jQuery('.is_masonry').masonry();
			jQuery('.btn_gallery_masonry').click(function(){
				jQuery('.gallery_type_selector a').removeClass('active');
				jQuery('.listing_gallery').removeClass('listing_gallery');
				jQuery(this).addClass('active');
				jQuery('.content_gallery').addClass('is_masonry');
				setTimeout("jQuery('.is_masonry').masonry()",250);
				setTimeout("jQuery('.is_masonry').masonry()",500);
				setTimeout("jQuery('.is_masonry').masonry()",1000);				
			});
			jQuery('.btn_gallery_column').click(function(){
				jQuery('.gallery_type_selector a').removeClass('active');
				jQuery(this).addClass('active');
				jQuery('.content_gallery').removeClass('is_masonry');
				jQuery('.content_gallery').addClass('listing_gallery');
			});
		});
        jQuery(window).load(function () {
			jQuery('.is_masonry').masonry();
			setTimeout("jQuery('.is_masonry').masonry()",1000);
        });
        jQuery(window).resize(function () {
			jQuery('.is_masonry').masonry();
			setTimeout("jQuery('.is_masonry').masonry()",500);
			setTimeout("jQuery('.is_masonry').masonry()",1000);
        });
		jQuery(document).ready(function(){
			setTimeout("jQuery('.is_masonry').masonry()",3000);				
		});		
    </script>
<?php get_footer(); 
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