<?php
/*
Template Name: Profile
*/
?>
<?php

if ( !is_user_logged_in() ) {
    wp_redirect( get_bloginfo('url') . '/connexion' );
    exit;
}

if ( !post_password_required() ) {
get_header(); the_post();
$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
if ($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "right-sidebar" || $gt3_theme_pagebuilder['settings']['layout-sidebars'] == "left-sidebar") {
	echo '<div class="bg_sidebar is_'. $gt3_theme_pagebuilder['settings']['layout-sidebars'] .'"></div>';
}
?>
<div class="content_wrapper">
	<div class="container">
        <div class="content_block row <?php echo esc_attr($gt3_theme_pagebuilder['settings']['layout-sidebars']) ?>">
            <div class="fl-container <?php echo(($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "right-sidebar") ? "hasRS" : ""); ?>">
                <div class="row">
                    <div class="posts-block <?php echo($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "left-sidebar" ? "hasLS" : ""); ?>">
					<?php if (!isset($gt3_theme_pagebuilder['settings']['show_title']) || $gt3_theme_pagebuilder['settings']['show_title'] !== "no") { ?>
                        <div class="page_title_block">
							<h1 class="title"><?php the_title(); ?></h1>
                        </div>
                    <?php } ?>
                        <div class="contentarea">
                            <?php
                                the_content(__('Read more!', 'theme_localization'));
                            ?>
                            <div id="wpmem_login">
                                <fieldset>
                                    <legend>Associer son compte facebook</legend>
                                    <a href="<?php echo get_bloginfo('url') ?>/wp-login.php?loginFacebook=1&redirect=<?php echo get_bloginfo('url') ?>" onclick="window.location = '<?php echo get_bloginfo('url') ?>/wp-login.php?loginFacebook=1&redirect='+window.location.href; return false;">
                                        <div class="new-fb-btn new-fb-7 new-fb-default-anim">
                                            <div class="new-fb-7-1">
                                                <div class="new-fb-7-1-1">Lier / d??lier</div>
                                            </div>
                                        </div>
                                    </a>
                                </fieldset>
                            </div>

                        </div>
                    </div>
                    <?php get_sidebar('left'); ?>
                </div>
            </div>
            <?php get_sidebar('right'); ?>
        </div>
    </div>
</div>

<?php
	get_footer();
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
