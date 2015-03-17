<?php
/*
Template Name: Gallery - Albums
*/
if ( !post_password_required() ) {
    get_header('fullscreen');
    the_post();

    $gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
    $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
    $pf = get_post_format();
    wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);

    ?>
    <div class="fullscreen_block">
        <?php
        global $wp_query_in_shortcodes, $paged;

        if(empty($paged)){
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        }
        if (isset($gt3_theme_pagebuilder['settings']['cat_ids']) && (is_array($gt3_theme_pagebuilder['settings']['cat_ids']))) {
            $compile_cats = array();
            foreach ($gt3_theme_pagebuilder['settings']['cat_ids'] as $catkey => $catvalue) {
                array_push($compile_cats, $catkey);
            }
            $selected_categories = implode(",", $compile_cats);
        } else {
            $selected_categories = "";
        }
        $post_type_terms = array();
        if (isset($selected_categories) && strlen($selected_categories) > 0) {
            $post_type_terms = explode(",", $selected_categories);
            if (count($post_type_terms) > 0) {
                $args = array(
                    'post_type' => 'gallery',
                    'order' => 'DESC',
                    'paged' => $paged,
                    'posts_per_page' => -1
                );
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'gallerycat',
                        'field' => 'id',
                        'terms' => $post_type_terms
                    )
                );
            }
        } else {
            $args = array(
                'post_type' => 'gallery',
                'order' => 'DESC',
                'paged' => $paged,
                'posts_per_page' => -1
            );
        }
        $wp_query_in_shortcodes = new WP_Query();

        if (isset($_GET['slug']) && strlen($_GET['slug']) > 0) {
            $post_type_terms = $_GET['slug'];
            if (count($post_type_terms) > 0) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'gallerycat',
                        'field' => 'slug',
                        'terms' => $post_type_terms
                    )
                );
            }
        }
		
		#Filter
		if (!isset($gt3_theme_pagebuilder['fs_portfolio']['filter']) || $gt3_theme_pagebuilder['fs_portfolio']['filter'] == 'on') {
			$compile = '';
			$compile .= showGalleryCats($post_type_terms);		
			echo $compile;		
		} else {
			echo "<script>
					jQuery(document).ready(function(){
						jQuery('html').addClass('without_border');
					});
				 </script>";			
		}
        ?>
        <div class="fs_blog_module fw_port_module">
            <?php
            $wp_query_in_shortcodes->query($args);
            while ($wp_query_in_shortcodes->have_posts()) : $wp_query_in_shortcodes->the_post();
                $all_likes = gt3pb_get_option("likes");
                $gt3_theme_post = get_plugin_pagebuilder(get_the_ID());
                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
                $pf = get_post_format();
                $echoallterm = '';
                $new_term_list = get_the_terms(get_the_id(), "gallerycat");
                if (is_array($new_term_list)) {
                    foreach ($new_term_list as $term) {
                        $tempname = strtr($term->name, array(
                            ' ' => ', ',
                        ));
                        $echoallterm .= strtolower($tempname) . " ";
                        $echoterm = $term->name;
                    }
                } else {
                    $tempname = 'Uncategorized';
                }
				?>                
                <div <?php post_class("blogpost_preview_fw fw-portPreview album_post"); ?>>
                    <div class="fw-portPreview-wrapper mas_style1 album_item" style="margin:0 20px 0 0">
                    <?php 
						if (isset($featured_image[0])) { ?>
                    	<div class="album_cover grid-gallery-item">
                            <img src="<?php echo aq_resize($featured_image[0], "540", "350", true, true, true); ?>" alt="" class="fw_featured_image" width="540">                            
                            <span class="gallery_fadder"></span>
                            <span class="gallery_ico"><i class="stand_icon icon-eye"></i></span>
                            <a href="<?php echo get_permalink(); ?>"></a>
                        </div>
						<?php } ?>
                        <div class="album_item-content ">
                            <h6 class="album_item-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h6>
                            <div class="block_likes">
                                <div class="fw-portPreview-views">
                                    <i class="stand_icon icon-camera-retro"></i>
                                    <span><?php echo count($gt3_theme_post['sliders']['fullscreen']['slides']); ?></span>
                                </div>
                                <div class="fw-portPreview-likes <?php echo (isset($_COOKIE['like_album'.get_the_ID()]) ? "already_liked" : ""); ?>" data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_album">
                                    <i class="stand_icon <?php echo (isset($_COOKIE['like_album'.get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                    <span><?php echo ((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="album-pseudo1"></div>
                    <div class="album-pseudo2"></div>
                </div>
            <?php endwhile; wp_reset_query();?>
            <div class="clear"></div>
        </div>
    </div>
    <div class="preloader"></div>
    <div class="peloader_logo">
        <div class="preloader_chart" data-percent="100"></div>
    </div>
    <script>

        jQuery(document).ready(function($){
            jQuery('.custom_bg').remove();
            jQuery('.fw-portPreview-likes').click(function(){
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
        jQuery(window).load(function($){

        });
    </script>
    <?php
    ?>

    <?php get_footer('fullwidth');
} else {
    get_header('fullscreen');
    echo "<div class='fixed_bg' style='background-image:url(".gt3_get_theme_option('bg_img').")'></div>";
    ?>
    <div class="pp_block">
        <h1 class="pp_title"><?php  _e('THIS CONTENT IS PASSWORD PROTECTED', 'theme_localization') ?></h1>
        <div class="pp_wrapper">
            <?php the_content(); ?>
        </div>
    </div>
    <div class="global_center_trigger"></div>
    <script>
        jQuery(document).ready(function(){
            jQuery('.post-password-form').find('label').find('input').attr('placeholder', 'Enter The Password...');
        });
    </script>
    <?php
    get_footer('fullscreen');
} ?>