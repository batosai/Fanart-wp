<?php 
/*
Template Name: Gallery - Masonry
*/
if ( !post_password_required() ) {
get_header('fullscreen');
the_post();

$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
$pf = get_post_format();
if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['interval'])) {
	$setPad = $gt3_theme_pagebuilder['sliders']['fullscreen']['interval'];
} else {
	$setPad = 0;
}
wp_enqueue_script('gt3_prettyPhoto_js', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', array(), false, true);
wp_enqueue_script('gt3_masonry_js', get_template_directory_uri() . '/js/masonry.min.js', array(), false, true);
?>
    <div class="fullscreen-gallery hided" style="padding-bottom:<?php echo $setPad; ?>; padding-left:<?php echo $setPad; ?>;">
	    <div class="fs_grid_gallery is_masonry">
		<?php 
            $galleryCompile = "";
        ?>
        <?php
        if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['slides']) && is_array($gt3_theme_pagebuilder['sliders']['fullscreen']['slides'])) {           
            foreach ($gt3_theme_pagebuilder['sliders']['fullscreen']['slides'] as $imageid => $image) {
				/*if ($image['slide_type'] == "video") { 
					$img_source = $image['thumbnail']['value'];
				} else {
					$img_source = $image['src'];
				}*/				
			?>   
                <div <?php post_class("grid-gallery-item"); ?> style="padding-right:<?php echo $setPad; ?>; padding-top:<?php echo $setPad; ?>">
                    <a href="<?php echo wp_get_attachment_url($image['attach_id']).($image['slide_type'] == "video" ? "&width=100%&height=100%" : ""); ?>" class="prettyPhoto" rel="prettyPhoto[gallery99]" title="<?php echo $image['caption']['value']; ?>">
                    	<img src="<?php echo aq_resize(wp_get_attachment_url($image['attach_id']), "540", "", true, true, true); ?>" alt="<?php echo $image['title']['value']; ?>" class="fw_featured_image" width="540" height="">
                        <span class="gallery_fadder"></span>
                        <span class="gallery_ico"><i class="stand_icon icon-eye"></i></span>
                    </a>
                </div>
				<?php }
	        }?>
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
		echo $galleryCompile;
	?>
    <script>
		jQuery(document).ready(function(){
			jQuery('.is_masonry').masonry();
			jQuery('html').addClass('without_border');
			jQuery('.is_masonry').masonry();
			setTimeout("jQuery('.is_masonry').masonry()",500);
			setTimeout("jQuery('.is_masonry').masonry()",1000);
		});
        jQuery(window).load(function () {
			setTimeout("jQuery('.is_masonry').masonry()",500);
			setTimeout("jQuery('.is_masonry').masonry()",1000);
        });
        jQuery(window).resize(function () {
			setTimeout("jQuery('.is_masonry').masonry()",500);
			setTimeout("jQuery('.is_masonry').masonry()",1000);
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