<?php 
if ( !post_password_required() ) {
/* LOAD PAGE BUILDER ARRAY */
$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' );
$pf = get_post_format();
$gt3_current_page_sidebar = $gt3_theme_pagebuilder['settings']['layout-sidebars'];

/* ADD 1 view for this post */
$post_views = (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0");
update_post_meta(get_the_ID(), "post_views", (int)$post_views + 1);
wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);
wp_enqueue_script('gt3_swipe_js', get_template_directory_uri() . '/js/jquery.event.swipe.js', array(), false, true);

$portfolioType = gt3_get_theme_option('default_portfolio_style');
if (isset($gt3_theme_pagebuilder['settings']['portfolio_style'])) {	
	if ($gt3_theme_pagebuilder['settings']['portfolio_style'] == 'simple-portfolio-post') { 
		$portfolioType = 'simple-portfolio-post';
	}
	if ($gt3_theme_pagebuilder['settings']['portfolio_style'] == 'fw-portfolio-post') { 
		$portfolioType = 'fw-portfolio-post';
	}
	if ($gt3_theme_pagebuilder['settings']['portfolio_style'] == 'ribbon-portfolio-post') { 
		$portfolioType = 'ribbon-portfolio-post';
	}
}
if ($portfolioType == 'fw-portfolio-post' || $portfolioType == 'ribbon-portfolio-post') {
	get_header('fullscreen');
} else {
	get_header();
}
$all_likes = gt3pb_get_option("likes");
the_post();
if ($portfolioType == 'simple-portfolio-post') { 
	if ($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "right-sidebar" || $gt3_theme_pagebuilder['settings']['layout-sidebars'] == "left-sidebar") {
		echo '<div class="bg_sidebar is_'. $gt3_theme_pagebuilder['settings']['layout-sidebars'] .'"></div>';
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
                                        <div class="blog_post_page sp_post">
				                        	<?php echo get_pf_type_output(array("pf" => get_post_format(), "gt3_theme_pagebuilder" => $gt3_theme_pagebuilder)); ?>
                                            <div class="blogpreview_top">
                                                <div class="box_date">
                                                    <span class="box_month"><?php echo get_the_time("M"); ?></span>
                                                    <span class="box_day"><?php echo get_the_time("d"); ?></span>
                                                </div>                                            
                                                <div class="listing_meta">
                                                    <span><?php _e('in', 'theme_localization'); ?> <?php 
                                                        $terms = get_the_terms( get_the_ID(), 'portcat' );
                                                        if ( $terms && ! is_wp_error( $terms ) ) {
                                                            $draught_links = array();
                                                            foreach ( $terms as $term ) {
                                                                $draught_links[] = '<a href="'.get_term_link($term->slug, "portcat").'">'.$term->name.'</a>';
                                                            }
                                                            $on_draught = join( ", ", $draught_links );
                                                            $show_cat = true;
                                                        }
            
                                                        if ($terms !== false) {
                                                            echo $on_draught;
                                                        } else {
                                                            echo "Uncategorized";
                                                        }
                                                    ?></span>
                                                    <span><a href="<?php echo get_comments_link(); ?>"><?php echo get_comments_number(get_the_ID())?> <?php _e('comments', 'theme_localization'); ?></a></span>
													<?php 
                                                        if (isset($gt3_theme_pagebuilder['page_settings']['portfolio']['skills']) && is_array($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'])) {
                                                            foreach ($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'] as $skillkey => $skillvalue) {
                                                                echo "<span class='preview_skills'>".esc_attr($skillvalue['name']).": ".esc_attr($skillvalue['value'])."</span>";
                                                            }
                                                        }
                                                    ?>                                                    
                                                </div>                                        
                                                <div class="author_ava"><?php echo get_avatar(get_the_author_meta('ID'), 72) ?></div>
                                            </div>
											<?php if (!isset($gt3_theme_pagebuilder['settings']['show_title']) || $gt3_theme_pagebuilder['settings']['show_title'] !== "no") { ?>
                                                <h3 class="blogpost_title"><?php the_title(); ?></h3>
                                            <?php } ?>
										</div>
										<!--.blog_post_page -->

                                        <div class="blog_post_content">
                                            <article class="contentarea sp_contentarea">
                                                <?php
                                                global $contentAlreadyPrinted;
                                                if ($contentAlreadyPrinted !== true) {
                                                    the_content(__('Read more!', 'theme_localization'));
                                                }
                                                wp_link_pages(array('before' => '<div class="page-link"><span>' . __('Pages', 'theme_localization') . ': </span>', 'after' => '</div>'));
                                                ?>
                                            </article>
                                        </div>

                                        <div class="blog_post-footer sp-blog_post-footer ">
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

                                        <div class="blogpost_user_meta">
                                            <div class="author-ava">
                                                <?php echo get_avatar(get_the_author_meta('ID'), 96); ?>
                                            </div>
                                            <div class="author-name"><h6><?php _e('About the Author', 'theme_localization'); ?>: <?php the_author_posts_link(); ?></h6></div>
                                            <div
                                                class="author-description"><?php the_author_meta('description'); ?></div>
                                            <div class="clear"></div>
                                        </div>                                        
                                    </div>
                                </div>
								<hr class="single_hr">
                                <div class="dn">
                                    <?php posts_nav_link(); ?>
                                </div>

                                <?php
									if (defined("GT3PBVERSION") && gt3_get_theme_option("related_posts") == "on") {
										$posts_per_line = 3;
		
										echo '<div class="row"><div class="span12 module_cont module_small_padding module_feature_posts single_feature">';
		
										$new_term_list = get_the_terms(get_the_id(), "portcat");
										$echoallterm = '';
										$echoterm = array();
										if (is_array($new_term_list)) {
											foreach ($new_term_list as $term) {
												$echoterm[] = $term->term_id;
											}
										}
										if (is_array($echoterm) && count($echoterm)>0) {
											$post_type_terms = implode(",", $echoterm);
										} else {
											$post_type_terms = "";
										}
		
										echo do_shortcode("[feature_portfolio
										heading_color=''
										heading_size='h3'
										heading_text='Related Works'
										number_of_posts='".$posts_per_line."'
										posts_per_line=".$posts_per_line."
										sorting_type='random'
										related='yes'
										now_open_pageid='".get_the_id()."'
										post_type_terms='".$post_type_terms."'
										post_type='port'][/feature_portfolio]");
										echo '</div></div>';
									}
									echo '<hr class="single_hr">';
									if ( comments_open() && gt3_get_theme_option("portfolio_comments") == "enabled" ) {
									?>
									<div class="row">
										<div class="span12">
											<?php comments_template(); ?>
										</div>
									</div>
									<?php } ?>                                
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
<?php 	
get_footer();
	} else if ($portfolioType == 'fw-portfolio-post'){
		//Fullscreen Type

	$compile_slides = "";
	$imgi = 1;?>
    <div class="fullscreen-gallery">
	    <div class="fs_grid_gallery">    
	<?php 
	if ($pf == "image" && isset($gt3_theme_pagebuilder['post-formats']['images'])) {		
		if (isset($gt3_theme_pagebuilder['post-formats']['images']) && is_array($gt3_theme_pagebuilder['post-formats']['images'])) {
			foreach ($gt3_theme_pagebuilder['post-formats']['images'] as $imageid => $image) {
				$compile_slides .= "<li data-count='".$imgi."' class='slide".$imgi."'><img src='" . aq_resize(wp_get_attachment_url($image['attach_id']), null, "910", true, true, true) . "' alt='image" . $imgi ."'/></li>";
				$imgi++;				
			}
		} ?>

        <div class="ribbon_wrapper">
	        <div id="ribbon_swipe"></div>
            <div class="ribbon_list_wrapper">
                <ul class="fw_gallery_list">
                    <?php echo $compile_slides; ?>
                </ul>
            </div>
        </div>
        <div class="slider_info fw_slider_info">
            <div class="slider_data">
                <a href="<?php echo esc_js("javascript:void(0)");?>" class="ltl_prev"><i class="icon-angle-left"></i></a><span class="num_current">1</span> <?php _e('of', 'theme_localization'); ?> <span class="num_all"></span><a href="<?php echo esc_js("javascript:void(0)");?>" class="ltl_next"><i class="icon-angle-right"></i></a>
                <h6 class="post_title"><?php the_title(); ?></h6>
            </div>
            <div class="slider_share">
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
            </div>
            <div class="block_likes">
                <div class="post-views"><i class="stand_icon icon-eye"></i> <span><?php echo (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span></div>                            
                <div class="gallery_likes gallery_likes_add <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "already_liked" : ""); ?>" data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                    <i class="stand_icon <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                    <span><?php echo ((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0); ?></span>
                </div>											
            </div>
            <div class="clear"></div>
            <div class="post_meta_data">
	            <div class="listing_meta">
                    <span><?php _e('by', 'theme_localization'); ?> <?php the_author(); ?></span>
                    <span><?php _e('in', 'theme_localization'); ?> <?php 
                        $terms = get_the_terms( get_the_ID(), 'portcat' );
                        if ( $terms && ! is_wp_error( $terms ) ) {
                            $draught_links = array();
                            foreach ( $terms as $term ) {
                                $draught_links[] = '<a href="'.get_term_link($term->slug, "portcat").'">'.$term->name.'</a>';
                            }
                            $on_draught = join( ", ", $draught_links );
                            $show_cat = true;
                        }

                        if ($terms !== false) {
                            echo $on_draught;
                        } else {
                            echo "Uncategorized";
                        }
                    ?></span>
                    <?php if (get_the_content() !== '' || (isset($gt3_theme_pagebuilder['modules']) && is_array($gt3_theme_pagebuilder['modules']) && count($gt3_theme_pagebuilder['modules'])>0)) { ?>
                    <span><a href="<?php echo get_comments_link(); ?>"><?php echo get_comments_number(get_the_ID())?> <?php _e('comments', 'theme_localization'); ?></a></span>
                    <?php } ?>
                    <?php 
                        if (isset($gt3_theme_pagebuilder['page_settings']['portfolio']['skills']) && is_array($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'])) {
                            foreach ($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'] as $skillkey => $skillvalue) {
                                echo "<span class='preview_skills'>".esc_attr($skillvalue['name']).": ".esc_attr($skillvalue['value'])."</span>";
                            }
                        }
                    ?>                                                    
                </div>
                <div class="post_controls">
                <?php if (get_the_content() !== '' || (isset($gt3_theme_pagebuilder['modules']) && is_array($gt3_theme_pagebuilder['modules']) && count($gt3_theme_pagebuilder['modules'])>0)) { ?>
                	<a href="<?php echo esc_js("javascript:void(0)");?>" class="fw_post_info"><i class="icon-info-circle"></i></a>
                <?php } ?>
					<?php next_post_link('<div class="fleft">%link</div>', ''); ?>
                    <?php previous_post_link('<div class="fright">%link</div>', ''); ?>
                    <a href="<?php echo esc_js("javascript:history.back()");?>" class="fw_post_close"></a>
                </div>
            </div>                           
        </div>
            <!-- .fullscreen_content_wrapper -->            
    <script>
		jQuery(document).ready(function($){
			jQuery('#ribbon_swipe').on("swipeleft",function(){
				next_slide();
			});
			jQuery('#ribbon_swipe').on("swiperight",function(){
				prev_slide();
			});			
			jQuery('.ltl_prev').click(function(){
				prev_slide();
			});
			jQuery('.ltl_next').click(function(){
				next_slide();
			});
			jQuery('.slide1').addClass('currentStep');			
			ribbon_setup();
		});	
		jQuery(window).resize(function($){
			ribbon_setup();
		});	
		jQuery(window).load(function($){
			ribbon_setup();
		});	
		
		function ribbon_setup() {
			setHeight = window_h - header.height() - 20;
			setHeight2 = window_h - header.height() - jQuery('.slider_info').height() - 20;
			jQuery('.fs_grid_gallery').height(window_h - header.height()-1);
			jQuery('.currentStep').removeClass('currentStep');
			jQuery('.slide1').addClass('currentStep');
			jQuery('.num_current').text('1');
			
			jQuery('.num_all').text(jQuery('.fw_gallery_list li').size());
			jQuery('.ribbon_wrapper').height(setHeight2);
			jQuery('.fw_gallery_list').height(setHeight2);
			max_step = -1*(jQuery('.ribbon_list').width()-window_w);
		}

		function prev_slide() {
			current_slide = parseInt(jQuery('.currentStep').attr('data-count'));
			current_slide--;
			if (current_slide < 1) {
				current_slide = jQuery('.fw_gallery_list').find('li').size();
			}
			jQuery('.currentStep').removeClass('currentStep');
			jQuery('.num_current').text(current_slide);
			jQuery('.slide'+current_slide).addClass('currentStep');
		}
		function next_slide() {
			current_slide = parseInt(jQuery('.currentStep').attr('data-count'));
			current_slide++;
			if (current_slide > jQuery('.fw_gallery_list').find('li').size()) {
				current_slide = 1
			}
			jQuery('.currentStep').removeClass('currentStep');
			jQuery('.num_current').text(current_slide);
			jQuery('.slide'+current_slide).addClass('currentStep');
		}
    </script>
	<?php 		
	} else if ($pf == "video") { ?>
        <div class="ribbon_wrapper">
            <a href="<?php echo esc_js("javascript:void(0)");?>" class="btn_prev"></a><a href="<?php echo esc_js("javascript:void(0)");?>" class="btn_next"></a>
            <div class="ribbon_list_wrapper">
			<?php
                //Video BG
        
                $video_url = $gt3_theme_pagebuilder['post-formats']['videourl'];
                echo "<div class='fw_video_block'>";
        
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
                }?>
			</div>
        </div>
        <div class="slider_info fw_slider_info">
            <div class="slider_data">
                <h6 class="video_post_title"><?php the_title(); ?></h6>
            </div>
            <div class="slider_share">
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
            </div>
            <div class="block_likes">
                <div class="post-views"><i class="stand_icon icon-eye"></i> <span><?php echo (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span></div>                            
                <div class="gallery_likes gallery_likes_add <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "already_liked" : ""); ?>" data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                    <i class="stand_icon <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                    <span><?php echo ((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0); ?></span>
                </div>											
            </div>
            <div class="clear"></div>
            <div class="post_meta_data">
	            <div class="listing_meta">
                    <span><?php _e('by', 'theme_localization'); ?> <?php the_author(); ?></span>
                    <span><?php _e('in', 'theme_localization'); ?> <?php 
                        $terms = get_the_terms( get_the_ID(), 'portcat' );
                        if ( $terms && ! is_wp_error( $terms ) ) {
                            $draught_links = array();
                            foreach ( $terms as $term ) {
                                $draught_links[] = '<a href="'.get_term_link($term->slug, "portcat").'">'.$term->name.'</a>';
                            }
                            $on_draught = join( ", ", $draught_links );
                            $show_cat = true;
                        }

                        if ($terms !== false) {
                            echo $on_draught;
                        } else {
                            echo "Uncategorized";
                        }
                    ?></span>
                    <?php if (get_the_content() !== '' || (isset($gt3_theme_pagebuilder['modules']) && is_array($gt3_theme_pagebuilder['modules']) && count($gt3_theme_pagebuilder['modules'])>0)) { ?>
                    <span><a href="<?php echo get_comments_link(); ?>"><?php echo get_comments_number(get_the_ID())?> <?php _e('comments', 'theme_localization'); ?></a></span>
                    <?php } ?>
                    <?php 
                        if (isset($gt3_theme_pagebuilder['page_settings']['portfolio']['skills']) && is_array($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'])) {
                            foreach ($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'] as $skillkey => $skillvalue) {
                                echo "<span class='preview_skills'>".esc_attr($skillvalue['name']).": ".esc_attr($skillvalue['value'])."</span>";
                            }
                        }
                    ?>                                                    
                </div>
                <div class="post_controls">
                <?php if (get_the_content() !== '' || (isset($gt3_theme_pagebuilder['modules']) && is_array($gt3_theme_pagebuilder['modules']) && count($gt3_theme_pagebuilder['modules'])>0)) { ?>
                	<a href="<?php echo esc_js("javascript:void(0)");?>" class="fw_post_info"><i class="icon-info-circle"></i></a>
                <?php } ?>
					<?php next_post_link('<div class="fleft">%link</div>', ''); ?>
                    <?php previous_post_link('<div class="fright">%link</div>', ''); ?>
                    <a href="<?php echo esc_js("javascript:history.back()");?>" class="fw_post_close"></a>
                </div>
            </div>                           
        </div>    

		<script>
            jQuery(document).ready(function($){
                video_setup();
            });	
            jQuery(window).resize(function($){
                video_setup();
            });	
            jQuery(window).load(function($){
                video_setup();
            });	
            
            function video_setup() {
				setHeight2 = window_h - header.height() - jQuery('.slider_info').height();
				jQuery('.fs_grid_gallery').height(window_h - header.height()-1);
				jQuery('.ribbon_wrapper').height(setHeight2);
				jQuery('.fw_video_block').height(setHeight2-20);
				jQuery('.fw_video_block').width(((setHeight2-20)/9)*16);
			}
        </script>
	<?php } ?>
        
    	</div>
    </div>
	<script>
        jQuery(document).ready(function($){
			if (jQuery('.fl-container').size() > 0) {
				jQuery('.fw_post_info').click(function(){
					jQuery('html, body').stop().animate({
						scrollTop: jQuery(jQuery('.content_wrapper')).offset().top-10
					}, 500);					
				});
			} else {
				jQuery('.fw_post_info').hide();
			}
			
            jQuery('.gallery_likes_add').click(function(){
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
   	<div class="preloader"></div>    
<?php if (get_the_content() !== '' || (isset($gt3_theme_pagebuilder['modules']) && is_array($gt3_theme_pagebuilder['modules']) && count($gt3_theme_pagebuilder['modules'])>0)) { ?>
    <div class="content_wrapper">
        <div class="container simple-post-container fw-post-container">
            <div class="content_block <?php echo esc_attr($gt3_theme_pagebuilder['settings']['layout-sidebars']) ?> row">
                <div
                    class="fl-container <?php echo(($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "right-sidebar") ? "hasRS" : ""); ?>">
                    <div class="row">						
                        <div class="posts-block simple-post <?php echo($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "left-sidebar" ? "hasLS" : ""); ?>">
                            <div class="contentarea fw_contentarea">
                                <div class="row">
                                    <div class="span12 module_cont module_blog module_none_padding module_blog_page">
                                        <div class="blog_post_page fw_post_page">
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

                                            <div class="blog_post-footer fw-blog_post-footer">
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

                                <?php
									if (defined("GT3PBVERSION") && gt3_get_theme_option("related_posts") == "on") {
										$posts_per_line = 3;
		
										echo '<div class="row"><div class="span12 module_cont module_small_padding module_feature_posts single_feature">';
		
										$new_term_list = get_the_terms(get_the_id(), "portcat");
										$echoallterm = '';
										$echoterm = array();
										if (is_array($new_term_list)) {
											foreach ($new_term_list as $term) {
												$echoterm[] = $term->term_id;
											}
										}
										if (is_array($echoterm) && count($echoterm)>0) {
											$post_type_terms = implode(",", $echoterm);
										} else {
											$post_type_terms = "";
										}
		
										echo do_shortcode("[feature_portfolio
										heading_color=''
										heading_size='h3'
										heading_text=''
										number_of_posts='".$posts_per_line."'
										posts_per_line=".$posts_per_line."
										sorting_type='random'
										related='yes'
										now_open_pageid='".get_the_id()."'
										post_type_terms='".$post_type_terms."'
										post_type='port'][/feature_portfolio]");
										echo '</div></div>';
									}
									echo '<hr class="single_hr">';?>
                                    <!-- <div class="prev_next_links fw_prev_next_links">
                                        <?php next_post_link('<div class="fleft">%link</div>', 'Previous Post') ?>
                                        <?php previous_post_link('<div class="fright">%link</div>', 'Next Post') ?>                                                    
                                    </div>-->
									<?php									
									if ( comments_open() && gt3_get_theme_option("portfolio_comments") == "enabled" ) {
									?>
									<div class="row">
										<div class="span12">
											<?php comments_template(); ?>
										</div>
									</div>
									<?php } ?>                                
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
	<?php 
		get_footer();
		} else { 
			get_footer('fullscreen'); 
		}

	} else {
	//Ribbon
	$compile_slides = "";
	$imgi = 1;?>
    <div class="fullscreen-gallery">
	    <div class="fs_grid_gallery">    
	<?php 
	if ($pf == "image" && isset($gt3_theme_pagebuilder['post-formats']['images'])) {		
		if (isset($gt3_theme_pagebuilder['post-formats']['images']) && is_array($gt3_theme_pagebuilder['post-formats']['images'])) {
			foreach ($gt3_theme_pagebuilder['post-formats']['images'] as $imageid => $image) {
				$compile_slides .= "<li data-count='".$imgi."' class='slide".$imgi."'><div class='slide_wrapper'><img src='" . aq_resize(wp_get_attachment_url($image['attach_id']), null, "910", true, true, true) . "' alt='image" . $imgi ."'/></div></li>";
				$imgi++;				
			}
		} ?>

        <div class="ribbon_wrapper">
            <a href="<?php echo esc_js("javascript:void(0)");?>" class="btn_prev"></a><a href="javascript:void(0)" class="btn_next"></a>
            <div id="ribbon_swipe"></div>
            <div class="ribbon_list_wrapper">
                <ul class="ribbon_list">
                    <?php echo $compile_slides; ?>
                </ul>
            </div>
        </div>
        <div class="slider_info">
            <div class="slider_data">
                <a href="<?php echo esc_js("javascript:void(0)");?>" class="ltl_prev"><i class="icon-angle-left"></i></a><span class="num_current">1</span> <?php _e('of', 'theme_localization'); ?> <span class="num_all"></span><a href="<?php echo esc_js("javascript:void(0)");?>" class="ltl_next"><i class="icon-angle-right"></i></a>
                <h6 class="post_title"><?php the_title(); ?></h6>
            </div>
            <div class="slider_share">
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
            </div>
            <div class="block_likes">
                <div class="post-views"><i class="stand_icon icon-eye"></i> <span><?php echo (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span></div>                            
                <div class="gallery_likes gallery_likes_add <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "already_liked" : ""); ?>" data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                    <i class="stand_icon <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                    <span><?php echo ((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0); ?></span>
                </div>											
            </div>
            <div class="clear"></div>
            <div class="post_meta_data">
	            <div class="listing_meta">
                    <span><?php _e('by', 'theme_localization'); ?> <?php the_author(); ?></span>
                    <span><?php _e('in', 'theme_localization'); ?> <?php 
                        $terms = get_the_terms( get_the_ID(), 'portcat' );
                        if ( $terms && ! is_wp_error( $terms ) ) {
                            $draught_links = array();
                            foreach ( $terms as $term ) {
                                $draught_links[] = '<a href="'.get_term_link($term->slug, "portcat").'">'.$term->name.'</a>';
                            }
                            $on_draught = join( ", ", $draught_links );
                            $show_cat = true;
                        }

                        if ($terms !== false) {
                            echo $on_draught;
                        } else {
                            echo "Uncategorized";
                        }
                    ?></span>
                    <?php if (get_the_content() !== '' || (isset($gt3_theme_pagebuilder['modules']) && is_array($gt3_theme_pagebuilder['modules']) && count($gt3_theme_pagebuilder['modules'])>0)) { ?>
                    <span><a href="<?php echo get_comments_link(); ?>"><?php echo get_comments_number(get_the_ID())?> <?php _e('comments', 'theme_localization'); ?></a></span>
                    <?php } ?>
                    <?php 
                        if (isset($gt3_theme_pagebuilder['page_settings']['portfolio']['skills']) && is_array($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'])) {
                            foreach ($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'] as $skillkey => $skillvalue) {
                                echo "<span class='preview_skills'>".esc_attr($skillvalue['name']).": ".esc_attr($skillvalue['value'])."</span>";
                            }
                        }
                    ?>                                                    
                </div>
                <div class="post_controls">
                <?php if (get_the_content() !== '' || (isset($gt3_theme_pagebuilder['modules']) && is_array($gt3_theme_pagebuilder['modules']) && count($gt3_theme_pagebuilder['modules'])>0)) { ?>
                	<a href="<?php echo esc_js("javascript:void(0)");?>" class="fw_post_info"><i class="icon-info-circle"></i></a>
                <?php } ?>
					<?php next_post_link('<div class="fleft">%link</div>', ''); ?>
                    <?php previous_post_link('<div class="fright">%link</div>', ''); ?>
                    <a href="<?php echo esc_js("javascript:history.back()");?>" class="fw_post_close"></a>
                </div>
            </div>                           
        </div>
            <!-- .fullscreen_content_wrapper -->            
    <script>
		jQuery(document).ready(function($){
			jQuery('#ribbon_swipe').on("swipeleft",function(){
				next_slide();
			});
			jQuery('#ribbon_swipe').on("swiperight",function(){
				prev_slide();
			});			
			jQuery('.ltl_prev').click(function(){
				prev_slide();
			});
			jQuery('.ltl_next').click(function(){
				next_slide();
			});
			jQuery('.btn_prev').click(function(){
				prev_slide();
			});
			jQuery('.btn_next').click(function(){
				next_slide();
			});
			jQuery('.slide1').addClass('currentStep');			
			ribbon_setup();
			
			setTimeout("ribbon_setup()",700);			
		});	
		jQuery(window).resize(function($){
			ribbon_setup();
			setTimeout("ribbon_setup()",500);
			setTimeout("ribbon_setup()",1000);			
		});	
		jQuery(window).load(function($){
			ribbon_setup();
			setTimeout("ribbon_setup()",350);
			setTimeout("ribbon_setup()",700);
		});	
		
		function ribbon_setup() {
			setHeight = window_h - header.height() - 20;
			setHeight2 = window_h - header.height() - jQuery('.slider_info').height() - 20;
			jQuery('.fs_grid_gallery').height(window_h - header.height()-1);
			jQuery('.currentStep').removeClass('currentStep');
			jQuery('.slide1').addClass('currentStep');
			jQuery('.num_current').text('1');
			
			jQuery('.num_all').text(jQuery('.ribbon_list li').size());
			jQuery('.ribbon_wrapper').height(setHeight2+20);
			jQuery('.ribbon_list .slide_wrapper').height(setHeight2);
			jQuery('.ribbon_list').height(setHeight2).width(20).css('left', 0);
			jQuery('.ribbon_list').find('li').each(function(){
				jQuery('.ribbon_list').width(jQuery('.ribbon_list').width()+jQuery(this).width());
				jQuery(this).attr('data-offset',jQuery(this).offset().left);
				jQuery(this).width(jQuery(this).find('img').width()+parseInt(jQuery(this).find('.slide_wrapper').css('margin-left')));
			});
			max_step = -1*(jQuery('.ribbon_list').width()-window_w);
		}
		function prev_slide() {
			current_slide = parseInt(jQuery('.currentStep').attr('data-count'));
			current_slide--;
			if (current_slide < 1) {
				current_slide = jQuery('.ribbon_list').find('li').size();
			}
			jQuery('.currentStep').removeClass('currentStep');
			jQuery('.num_current').text(current_slide);
			jQuery('.slide'+current_slide).addClass('currentStep');
			if (-1*jQuery('.slide'+current_slide).attr('data-offset') > max_step) {
				jQuery('.ribbon_list').css('left', -1*jQuery('.slide'+current_slide).attr('data-offset'));
			} else {
				jQuery('.ribbon_list').css('left', max_step);
			}
		}
		function next_slide() {
			current_slide = parseInt(jQuery('.currentStep').attr('data-count'));
			current_slide++;
			if (current_slide > jQuery('.ribbon_list').find('li').size()) {
				current_slide = 1
			}
			jQuery('.currentStep').removeClass('currentStep');
			jQuery('.num_current').text(current_slide);
			jQuery('.slide'+current_slide).addClass('currentStep');
			if (-1*jQuery('.slide'+current_slide).attr('data-offset') > max_step) {
				jQuery('.ribbon_list').css('left', -1*jQuery('.slide'+current_slide).attr('data-offset'));
			} else {
				jQuery('.ribbon_list').css('left', max_step);
			}
		}
    </script>
	<?php 		
	} else if ($pf == "video") { ?>
        <div class="ribbon_wrapper">
            <div class="ribbon_list_wrapper">
			<?php
                //Video BG
        
                $video_url = $gt3_theme_pagebuilder['post-formats']['videourl'];
                echo "<div class='fw_video_block'>";
        
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
                }?>
			</div>
        </div>
        <div class="slider_info">
            <div class="slider_data">
                <h6 class="video_post_title"><?php the_title(); ?></h6>
            </div>
            <div class="slider_share">
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
            </div>
            <div class="block_likes">
                <div class="post-views"><i class="stand_icon icon-eye"></i> <span><?php echo (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span></div>                            
                <div class="gallery_likes gallery_likes_add <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "already_liked" : ""); ?>" data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                    <i class="stand_icon <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                    <span><?php echo ((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0); ?></span>
                </div>											
            </div>
            <div class="clear"></div>
            <div class="post_meta_data">
	            <div class="listing_meta">
                    <span><?php _e('by', 'theme_localization'); ?> <?php the_author(); ?></span>
                    <span><?php _e('in', 'theme_localization'); ?> <?php 
                        $terms = get_the_terms( get_the_ID(), 'portcat' );
                        if ( $terms && ! is_wp_error( $terms ) ) {
                            $draught_links = array();
                            foreach ( $terms as $term ) {
                                $draught_links[] = '<a href="'.get_term_link($term->slug, "portcat").'">'.$term->name.'</a>';
                            }
                            $on_draught = join( ", ", $draught_links );
                            $show_cat = true;
                        }

                        if ($terms !== false) {
                            echo $on_draught;
                        } else {
                            echo "Uncategorized";
                        }
                    ?></span>
                    <?php if (get_the_content() !== '' || (isset($gt3_theme_pagebuilder['modules']) && is_array($gt3_theme_pagebuilder['modules']) && count($gt3_theme_pagebuilder['modules'])>0)) { ?>
                    <span><a href="<?php echo get_comments_link(); ?>"><?php echo get_comments_number(get_the_ID())?> <?php _e('comments', 'theme_localization'); ?></a></span>
                    <?php } ?>
                    <?php 
                        if (isset($gt3_theme_pagebuilder['page_settings']['portfolio']['skills']) && is_array($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'])) {
                            foreach ($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'] as $skillkey => $skillvalue) {
                                echo "<span class='preview_skills'>".esc_attr($skillvalue['name']).": ".esc_attr($skillvalue['value'])."</span>";
                            }
                        }
                    ?>                                                    
                </div>
                <div class="post_controls">
                <?php if (get_the_content() !== '' || (isset($gt3_theme_pagebuilder['modules']) && is_array($gt3_theme_pagebuilder['modules']) && count($gt3_theme_pagebuilder['modules'])>0)) { ?>
                	<a href="<?php echo esc_js("javascript:void(0)");?>" class="fw_post_info"><i class="icon-info-circle"></i></a>
                <?php } ?>
					<?php next_post_link('<div class="fleft">%link</div>', ''); ?>
                    <?php previous_post_link('<div class="fright">%link</div>', ''); ?>
                    <a href="<?php echo esc_js("javascript:history.back()");?>" class="fw_post_close"></a>
                </div>
            </div>                           
        </div>    

		<script>
            jQuery(document).ready(function($){
                video_setup();
            });	
            jQuery(window).resize(function($){
                video_setup();
            });	
            jQuery(window).load(function($){
                video_setup();
            });	
            
            function video_setup() {
				setHeight2 = window_h - header.height() - jQuery('.slider_info').height();
				jQuery('.fs_grid_gallery').height(window_h - header.height()-1);
				jQuery('.ribbon_wrapper').height(setHeight2);
				jQuery('.fw_video_block').height(setHeight2-20);
				jQuery('.fw_video_block').width(((setHeight2-20)/9)*16);
			}
        </script>
	<?php } ?>
        
    	</div>
    </div>
	<script>
        jQuery(document).ready(function($){
			if (jQuery('.fl-container').size() > 0) {
				jQuery('.fw_post_info').click(function(){
					jQuery('html, body').stop().animate({
						scrollTop: jQuery(jQuery('.content_wrapper')).offset().top-10
					}, 500);					
				});
			} else {
				jQuery('.fw_post_info').hide();
			}
			
            jQuery('.gallery_likes_add').click(function(){
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
   	<div class="preloader"></div>
<?php if (get_the_content() !== '' || (isset($gt3_theme_pagebuilder['modules']) && is_array($gt3_theme_pagebuilder['modules']) && count($gt3_theme_pagebuilder['modules'])>0)) { ?>
    <div class="content_wrapper">
        <div class="container simple-post-container fw-post-container">
            <div class="content_block <?php echo esc_attr($gt3_theme_pagebuilder['settings']['layout-sidebars']) ?> row">
                <div
                    class="fl-container <?php echo(($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "right-sidebar") ? "hasRS" : ""); ?>">
                    <div class="row">						
                        <div class="posts-block simple-post <?php echo($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "left-sidebar" ? "hasLS" : ""); ?>">
                            <div class="contentarea fw_contentarea">
                                <div class="row">
                                    <div class="span12 module_cont module_blog module_none_padding module_blog_page">
                                        <div class="blog_post_page fw_post_page">
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

                                            <div class="blog_post-footer fw-blog_post-footer">
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

                                <?php
									if (defined("GT3PBVERSION") && gt3_get_theme_option("related_posts") == "on") {
										$posts_per_line = 3;
		
										echo '<div class="row"><div class="span12 module_cont module_small_padding module_feature_posts single_feature">';
		
										$new_term_list = get_the_terms(get_the_id(), "portcat");
										$echoallterm = '';
										$echoterm = array();
										if (is_array($new_term_list)) {
											foreach ($new_term_list as $term) {
												$echoterm[] = $term->term_id;
											}
										}
										if (is_array($echoterm) && count($echoterm)>0) {
											$post_type_terms = implode(",", $echoterm);
										} else {
											$post_type_terms = "";
										}
		
										echo do_shortcode("[feature_portfolio
										heading_color=''
										heading_size='h3'
										heading_text=''
										number_of_posts='".$posts_per_line."'
										posts_per_line=".$posts_per_line."
										sorting_type='random'
										related='yes'
										now_open_pageid='".get_the_id()."'
										post_type_terms='".$post_type_terms."'
										post_type='port'][/feature_portfolio]");
										echo '</div></div>';
									}
									echo '<hr class="single_hr">';
									if ( comments_open() && gt3_get_theme_option("portfolio_comments") == "enabled" ) {
									?>
									<div class="row">
										<div class="span12">
											<?php comments_template(); ?>
										</div>
									</div>
									<?php } ?>                                
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
<?php 
	get_footer();
} else { get_footer('fullscreen'); } ?>
    <?php 
	}
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