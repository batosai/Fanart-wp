<?php
/*
Template Name: Striped Page
*/
if ( !post_password_required() ) {
get_header('fullscreen');
?>
    <script>
		jQuery(document).ready(function(){
				jQuery('html').addClass('without_border');
		});
	</script>
<div class="strip_template">
	<?php $gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
    
    if (isset($gt3_theme_pagebuilder['strips']) && is_array($gt3_theme_pagebuilder['strips'])) {
        $el_count = count($gt3_theme_pagebuilder["strips"]);
        $strip_width = 100/$el_count;
		if (!isset($gt3_theme_pagebuilder['settings']['striptype']) || $gt3_theme_pagebuilder['settings']['striptype'] == 'horizontal') {
			$str_type = "horizontal";
		} else {
			$str_type = "vertical";
		}
		if ($str_type == "vertical") {
        ?>
            <figure class="strip-menu <?php echo $str_type; ?>" data-width="<?php echo $strip_width; ?>" data-count="<?php echo count($gt3_theme_pagebuilder["strips"]); ?>">
                <?php foreach ($gt3_theme_pagebuilder['strips'] as $stripid => $stripdata) {
                    ?>
                <div class="strip-item" data-href="<?php echo $stripdata['link']; ?>" style="background-image:url(<?php echo $stripdata['image']; ?>); width:<?php echo $strip_width; ?>%;">
                	<div class="mobile-hover"></div>
                    <div class="strip-fadder"></div>
                    <div class="strip-text">
                        <h1 class="strip-title"><?php echo $stripdata['striptitle1']; ?></h1>
                    </div>
                    <a href="<?php echo $stripdata['link']; ?>"></a>
                </div>
                <?php }?>
            </figure>
        <?php } else {?>
            <figure class="strip-menu <?php echo $str_type; ?>" data-height="<?php echo $strip_width; ?>" data-count="<?php echo count($gt3_theme_pagebuilder["strips"]); ?>">
                <?php foreach ($gt3_theme_pagebuilder['strips'] as $stripid => $stripdata) {
                    ?>
                <div class="strip-item" data-href="<?php echo $stripdata['link']; ?>" style="background-image:url(<?php echo $stripdata['image']; ?>); height:<?php echo $strip_width; ?>%;">
                	<div class="mobile-hover"></div>
                    <div class="strip-fadder"></div>
                    <div class="strip-text">
                        <h1 class="strip-title"><?php echo $stripdata['striptitle1']; ?></h1>
                        <h3 class="strip-caption"><?php echo $stripdata['striptitle2']; ?></h3>
                    </div>
                    <a href="<?php echo $stripdata['link']; ?>"></a>
                </div>
                <?php }?>
            </figure>        
        <?php } ?>
        <script>
            jQuery(document).ready(function($) {				
				if (window_w < 1025 && window_w > 760) {
					jQuery('.mobile-hover').click(function(){
						jQuery('.hovered').removeClass('hovered');
						jQuery(this).parent('.strip-item').addClass('hovered');
					});
				}
				if (window_w < 760 && jQuery('.strip-menu').hasClass('vertical')) {
					jQuery('.strip-menu').removeClass('vertical').addClass('was_vert');
				}
				strip_setup();
            });	
			jQuery(window).resize(function(){
				strip_setup();
				setTimeout("strip_setup()",500);
				setTimeout("strip_setup()",1000);
			});
			function strip_setup() {				
				if (jQuery('.strip-menu').hasClass('vertical')) {
					jQuery('.strip-menu').height(window_h - header.height());
					jQuery('.strip-menu').find('h1').each(function(){
						jQuery(this).width(jQuery('.strip-item').height());
						jQuery(this).css({'margin-top' : (jQuery('.strip-item').height() - jQuery(this).height())/2, 'margin-left' : -1*(jQuery(this).width() - jQuery('.strip-item').width())/2});
					});
				} else {
					jQuery('.strip-menu').height(window_h - header.height());
					jQuery('.strip-menu').find('.strip-text').each(function(){						
						jQuery(this).css('margin-top' , (jQuery('.strip-item').height() - jQuery(this).height()-13)/2);
					});					
				}
			}
        </script>
    <?php } ?> 
</div>
<div class="preloader"></div>
<?php
get_footer('fullscreen'); 
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