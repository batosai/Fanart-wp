<?php 
/*
Template Name: Gallery - Flow
*/
if ( !post_password_required() ) {
get_header('fullscreen');
the_post();

$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
wp_enqueue_script('gt3_reflection_js', get_template_directory_uri() . '/js/reflection.min.js', array(), false, true);
wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);
wp_enqueue_script('gt3_swipe_js', get_template_directory_uri() . '/js/jquery.event.swipe.js', array(), false, true);

$pf = get_post_format();
$all_likes = gt3pb_get_option("likes");
$post_views = (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0");
update_post_meta(get_the_ID(), "post_views", (int)$post_views + 1);
?>
<div class="whaterWheel_content">
   	<div id="whaterwheel">
    	<div id="ww_finger"></div>
		<?php 
            $img_base = '';
            $compile_slides = '';
        ?>
        <?php
        if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['slides']) && is_array($gt3_theme_pagebuilder['sliders']['fullscreen']['slides'])) {
            $count = 1;
            foreach ($gt3_theme_pagebuilder['sliders']['fullscreen']['slides'] as $imageid => $image) {
                //$img_base .= $image['attach_id'] .',';
				if (isset($image['title']['value']) && strlen($image['title']['value'])>0) {$photoTitle = ' : '.$image['title']['value'];} else {$photoTitle = " ";}
				if (isset($image['title']['value']) && strlen($image['title']['value'])>0) {$photoAlt = $image['title']['value'];} else {$photoAlt = " ";}
                echo "<div class='ww_block' id='ww_block".$count."' data-count='".$count."' data-title='". $photoTitle ."'><div class='ww_wrapper'><a href='".esc_js("javascript:void(0)")."' class='ww_link' data-count='".$count."'><img width='740' alt='". $photoAlt ."' height='550' src='" . aq_resize(wp_get_attachment_url($image['attach_id']), "740", "550", true, true, true) . "'/></a></div></div>";
				$count++;
        ?>   			
            <?php }
        }?>
    </div>
    <div class="ww_footer">
    	<div class="ww_footer_left">
            <?php if (!isset($gt3_theme_pagebuilder['settings']['show_title']) || $gt3_theme_pagebuilder['settings']['show_title'] !== "no") { ?>
                <h6 class="title"><?php the_title(); ?></h6><h6 class="img_title"></h6>
            <?php } ?>
        </div>
    	<div class="ww_footer_right">
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
        </div>
    </div>
</div>
	<script type="text/javascript">
		var whaterWheel = jQuery("#whaterwheel"),
			allSize = jQuery('.ww_block').size();
		jQuery(document).ready(function ($) {
			setupWW();
			jQuery('.ww_link').click(function(){
				setSlide(parseInt(jQuery(this).attr('data-count')))
			});

			jQuery(document.documentElement).keyup(function (event) {
				if ((event.keyCode == 37) || (event.keyCode == 40)) {
					prev_ww();
				// Right Arrow or Up Arrow
				} else if ((event.keyCode == 39) || (event.keyCode == 38)) {
					next_ww();
				}	
			});

			jQuery('#ww_finger').on("swipeleft",function(){
				next_ww();
			});
			jQuery('#ww_finger').on("swiperight",function(){
				prev_ww();
			});
			
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
		function next_ww() {
			cur_slide = parseInt(jQuery('.current').attr('data-count'));
			cur_slide++;
			if (cur_slide > allSize) cur_slide = 1;
			if (cur_slide < 1) cur_slide = allSize;
			setSlide(cur_slide);
		}
		function prev_ww() {
			cur_slide = parseInt(jQuery('.current').attr('data-count'));
			cur_slide--;
			if (cur_slide > allSize) cur_slide = 1;
			if (cur_slide < 1) cur_slide = allSize;
			setSlide(cur_slide);
		}
		
		jQuery(window).load(function (){
			setupWW();		
			setTimeout("setupWW()",500);
			setTimeout("setupWW()",1000);
		});
		jQuery(window).resize(function (){
			setupWW();
			setTimeout("setupWW()",500);
			setTimeout("setupWW()",1000);
		});
		
		var atr056 = 0,
			atr078 = 0,
			atr_main = 1;
		function setSlide(cur) {
			if (window_w > 1025) {
				whaterWheel.find('img').unreflect();
			}
			if (window_w > 960) {
				atr056 = 0.56;
				atr078 = 0.78;
				atr_main = 1;
			} else if (window_w > 760 && window_w < 960){
				atr056 = 0.37;
				atr078 = 0.56;
				atr_main = 0.75;
			} else if (window_w < 760){
				atr056 = 0.3;
				atr078 = 0.5;
				atr_main = 0.75;
			}
			jQuery('.prev2').removeClass('prev2');
			jQuery('.prev').removeClass('prev');
			jQuery('.current').removeClass('current');				
			jQuery('.next').removeClass('next');
			jQuery('.next2').removeClass('next2');
			jQuery('#ww_block'+cur).addClass('current');
			if (whaterWheel.hasClass('type5')) {
				if((cur+1) > allSize) {
					jQuery('#ww_block1').addClass('next');
					jQuery('#ww_block2').addClass('next2');
				} else if ((cur+1) == allSize){
					jQuery('#ww_block'+allSize).addClass('next');
					jQuery('#ww_block1').addClass('next2');					
				} else {
					jQuery('#ww_block'+(cur+1)).addClass('next');
					jQuery('#ww_block'+(cur+2)).addClass('next2');				
				}
				if((cur-1) < 1) {
					jQuery('#ww_block'+allSize).addClass('prev');
					jQuery('#ww_block'+(allSize-1)).addClass('prev2');
				} else if ((cur-1) == 1){					
					jQuery('#ww_block1').addClass('prev');
					jQuery('#ww_block'+allSize).addClass('prev2');
				} else {
					jQuery('#ww_block'+(cur-1)).addClass('prev');
					jQuery('#ww_block'+(cur-2)).addClass('prev2');
				}
			}

			jQuery('.prev2').css('margin-left', -1*(jQuery('.current').width()/2)-jQuery('.current').width()*0.78/1.333-jQuery('.current').width()*0.56/1.333);
			jQuery('.prev').css('margin-left', -1*(jQuery('.current').width()/2)-jQuery('.current').width()*0.78/1.333);
			jQuery('.current').css('margin-left', -1*(jQuery('.current').width()/2));
			jQuery('.next').css('margin-left' , -1*(jQuery('.current').width()/2)+jQuery('.current').width()*0.78/1.333);
			jQuery('.next2').css('margin-left' , -1*(jQuery('.current').width()/2)+jQuery('.current').width()*0.78/1.333+jQuery('.current').width()*0.56/1.333);
			jQuery('.img_title').text(jQuery('.current').attr('data-title'));
			if (window_w > 1025) {
				whaterWheel.find('img').reflect({height:0.24,opacity:0.25});
				whaterWheel.find('canvas').each(function(){
					jQuery(this).width(jQuery(this).prev('img').width());
				});
			}
		}
		function setupWW() {
			if (window_w > 1025) {
				whaterWheel.find('img').unreflect();
			}
			if (window_w > 960) {
				atr056 = 0.56;
				atr078 = 0.78;
				atr_main = 1;
			} else if (window_w > 760 && window_w < 960){
				atr056 = 0.37;
				atr078 = 0.56;
				atr_main = 0.75;
			} else if (window_w < 760){
				atr056 = 0.3;
				atr078 = 0.5;
				atr_main = 0.75;
			}
			var setHeight = (window_h-header.height()-jQuery('.ww_footer').height()-1)*atr_main;
			var setWidth = window_w - parseInt(whaterWheel.css('padding-left')) - parseInt(whaterWheel.css('padding-right'));			
			whaterWheel.height(setHeight*0.7).width(setWidth).css({'margin-top' : setHeight*0.15, 'margin-bottom' : setHeight*0.15});
			whaterWheel.width();
			whaterWheel.height((window_h-header.height()-jQuery('.ww_footer').height())*0.7);
			if (jQuery('.current').size() < 1) {
				if (whaterWheel.find('.ww_block').size() > 4) {
					whaterWheel.addClass('type5');
					jQuery('#ww_block1').addClass('prev2');
					jQuery('#ww_block2').addClass('prev');
					jQuery('#ww_block3').addClass('current');
					jQuery('#ww_block4').addClass('next');
					jQuery('#ww_block5').addClass('next2');				
				} else if (whaterWheel.find('.ww_block').size() > 2) {
					whaterWheel.addClass('type3');
					jQuery('#ww_block1').addClass('prev');
					jQuery('#ww_block2').addClass('current');
					jQuery('#ww_block3').addClass('next');				
				} else if (whaterWheel.find('.ww_block').size() == 2) {
					whaterWheel.addClass('type2');
					jQuery('#ww_block1').addClass('current');
					jQuery('#ww_block2').addClass('next');
				} else if (whaterWheel.find('.ww_block').size() == 1) {
					whaterWheel.addClass('type1');
					jQuery('#ww_block1').addClass('current');
				}
			}
			jQuery('.prev2').css('margin-left', -1*(jQuery('.current').width()/2)-jQuery('.current').width()*atr078/1.333-jQuery('.current').width()*atr056/1.333);
			jQuery('.prev').css('margin-left', -1*(jQuery('.current').width()/2)-jQuery('.current').width()*atr078/1.333);
			jQuery('.current').css('margin-left', -1*(jQuery('.current').width()/2));
			jQuery('.next').css('margin-left' , -1*(jQuery('.current').width()/2)+jQuery('.current').width()*atr078/1.333);
			jQuery('.next2').css('margin-left' , -1*(jQuery('.current').width()/2)+jQuery('.current').width()*atr078/1.333+jQuery('.current').width()*atr056/1.333);
			jQuery('.img_title').text(jQuery('.current').attr('data-title'));
			if (window_w > 1025) {
				whaterWheel.find('img').reflect({height:0.24,opacity:0.25});
				whaterWheel.find('canvas').each(function(){
					jQuery(this).width(jQuery(this).prev('img').width());
				});
			}
		}
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