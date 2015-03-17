<?php
if ( !post_password_required() ) {
/* LOAD PAGE BUILDER ARRAY */
$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
$pf = get_post_format();
$gt3_current_page_sidebar = $gt3_theme_pagebuilder['settings']['layout-sidebars'];

$post_views = (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0");
update_post_meta(get_the_ID(), "post_views", (int)$post_views + 1);

get_header();
the_post();
wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);
$all_likes = gt3pb_get_option("likes");

	if ($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "right-sidebar" || $gt3_theme_pagebuilder['settings']['layout-sidebars'] == "left-sidebar") {
		echo '<div class="bg_sidebar is_'. $gt3_theme_pagebuilder['settings']['layout-sidebars'] .'"></div>';
	}
?>

<?php

if(isset($_GET['delete_'.$post->ID])) {
    if(get_current_user_id() == $post->post_author) {
        wp_delete_post($post->ID);
        wp_redirect( home_url() );
        exit;
    }
}

?>

    <div class="content_wrapper">
        <div class="container simple-post-container">
            <div class="content_block <?php echo esc_attr($gt3_theme_pagebuilder['settings']['layout-sidebars']) ?> row">
                <div
                    class="fl-container <?php echo(($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "right-sidebar") ? "hasRS" : ""); ?>">
                    <div class="row">
                        <div class="posts-block simple-post <?php echo($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "left-sidebar" ? "hasLS" : ""); ?>">
                            <div class="contentarea">
                                <div class="row">
                                    <div class="span12 module_cont module_blog module_none_padding module_blog_page">
                                        <div class="prev_next_links">
                                            <?php next_post_link('<div class="fleft">%link</div>', __('Previous Post', 'theme_localization')) ?>
                                            <?php previous_post_link('<div class="fright">%link</div>', __('Next Post', 'theme_localization')) ?>
                                        </div>
                                        <div class="blog_post_page">
				                        	<?php echo get_pf_type_output(array("pf" => get_post_format(), "gt3_theme_pagebuilder" => $gt3_theme_pagebuilder)); ?>
                                            <div class="blogpreview_top">
                                                <div class="box_date">
                                                    <span class="box_month"><?php echo get_the_time("M"); ?></span>
                                                    <span class="box_day"><?php echo get_the_time("d"); ?></span>
                                                </div>
                                                <div class="listing_meta">
                                                    <span><?php _e('in', 'theme_localization'); ?> <?php the_category(', '); ?></span>
                                                    <span><a href="<?php echo get_comments_link(); ?>"><?php echo get_comments_number(get_the_ID())?> <?php _e('comments', 'theme_localization'); ?></a></span>
                                                    <?php if(is_user_logged_in() && get_current_user_id() == $post->post_author): ?>
                                                    <span><a href="?delete_<?php echo $post->ID ?>" onClick="return confirm('Souhaitez-vous vraiment supprimer ce dessin ?')" style="color:red;">Supprimer</a></span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="author_ava"><?php echo get_avatar(get_the_author_meta('ID'), 72) ?><?php the_author_posts_link(); ?></div>
                                            </div>
											<?php if (!isset($gt3_theme_pagebuilder['settings']['show_title']) || $gt3_theme_pagebuilder['settings']['show_title'] !== "no") { ?>
                                                <h1 class="blogpost_title"><?php the_title(); ?></h1>
                                            <?php } ?>

                                            <div class="blog_post_content">
                                                <article class="contentarea">
                                                    <?php
                                                    global $contentAlreadyPrinted;
                                                    if ($contentAlreadyPrinted !== true) {
                                                        the_content(__('Read more!', 'theme_localization'));
                                                    }
                                                    wp_link_pages(array('before' => '<div class="page-link"><span>' . __('Pages', 'theme_localization') . ': </span>', 'after' => '</div>'));
                                                    ?>
                                                </article>
                                            </div>

                                            <div class="blog_post-footer">
                                                <div class="blogpost_share">
	                                                <span><?php  _e('Share this:', 'theme_localization'); ?></span>
                                                    <a target="_blank"
                                                       href="http://www.facebook.com/share.php?u=<?php echo get_permalink(); ?>"
                                                       class="share_facebook"><i
                                                            class="stand_icon icon-facebook-square"></i></a>
                                                    <a target="_blank"
                                                       href="http://pinterest.com/pin/create/button/?url=<?php echo get_permalink(); ?>&media=<?php echo (strlen($featured_image[0])>0) ? $featured_image[0] : gt3_get_theme_option("logo"); ?>"
                                                       class="share_pinterest"><i class="stand_icon icon-pinterest"></i></a>
                                                    <a target="_blank"
                                                       href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&amp;url=<?php echo get_permalink(); ?>"
                                                       class="share_tweet"><i class="stand_icon icon-twitter"></i></a>
                                                    <a target="_blank"
                                                       href="https://plus.google.com/share?url=<?php echo get_permalink(); ?>"
                                                       class="share_gplus"><i class="icon-google-plus-square"></i></a>
                                                    <div class="clear"></div>
                                                </div>
                                                <?php echo '
                                                <div class="block_likes">
                                                    <div class="post-views"><i class="stand_icon icon-eye"></i> <span>'. (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0") .'</span></div>
                                                    <div class="gallery_likes gallery_likes_add '.(isset($_COOKIE['like_post'.get_the_ID()]) ? "already_liked" : "").'" data-attachid="'.get_the_ID().'" data-modify="like_post">
                                                        <i class="stand_icon '.(isset($_COOKIE['like_post'.get_the_ID()]) ? "icon-heart" : "icon-heart-o").'"></i>
                                                        <span>'.((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0).'</span>
                                                    </div>
                                                </div>'; ?>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <!--.blog_post_page -->
                                    </div>
                                </div>
								<hr class="single_hr">
                                <div class="dn">
                                    <?php posts_nav_link(); ?>
                                </div>

                                <div class="row">
                                    <div class="span12">
                                        <?php comments_template(); ?>
                                    </div>
                                </div>

                                <hr class="single_hr">

                                <?php
									if (defined("GT3PBVERSION") && gt3_get_theme_option("related_posts") == "on") {
										$posts_per_line = 3;

										echo '<div class="row"><div class="span12 module_cont module_small_padding featured_items single_feature featured_posts">';
										echo do_shortcode("[feature_posts
									heading_color=''
									heading_size='h2'
									heading_text=''
									number_of_posts=" . $posts_per_line . "
									posts_per_line=" . $posts_per_line . "
									sorting_type='random'
									related='yes'
									post_type='post'][/feature_posts]");
										echo '</div></div>';
									}
                                ?>
                            </div>
                            <!-- .contentarea -->
                        </div>
                        <?php get_sidebar('left'); ?>
                    </div>
                    <div class="clear"><!-- ClearFix --></div>
                </div>
                <!-- .fl-container -->
                <?php get_sidebar('right'); ?>
                <div class="clear"><!-- ClearFix --></div>
            </div>
        </div>
        <!-- .container -->
    </div><!-- .content_wrapper -->
    <script>
		jQuery(document).ready(function(){
			jQuery('.pf_output_container').each(function(){
				if (jQuery(this).html() == '') {
					jQuery(this).parents('.blog_post_page').addClass('no_pf');
				} else {
					jQuery(this).parents('.blog_post_page').addClass('has_pf');
				}
			});
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
