<?php get_header('centered');
#Emulate default settings for page without personal ID
$gt3_theme_pagebuilder = gt3_get_default_pb_settings();
$gt3_current_page_sidebar = $gt3_theme_pagebuilder['settings']['layout-sidebars'];
if ($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "right-sidebar" || $gt3_theme_pagebuilder['settings']['layout-sidebars'] == "left-sidebar") {
    echo '<div class="bg_sidebar"></div>';
}
?>

    <div class="content_wrapper">
        <div class="container">
            <div class="content_block <?php echo esc_attr($gt3_theme_pagebuilder['settings']['layout-sidebars']) ?> row">
            <div class="fl-container <?php echo(($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "right-sidebar") ? "hasRS" : ""); ?>">
                <div class="row">
                    <div class="posts-block <?php echo($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "left-sidebar" ? "hasLS" : ""); ?>">
                            <div class="contentarea">
                                <?php
                                echo '<div class="row-fluid"><div class="span12 module_cont module_blog">';

                                global $paged;
                                $foundSomething = false;

                                while ($wp_query->have_posts()) : $wp_query->the_post();

                                    if (isset($gt3_theme_post['page_settings']['portfolio']['work_link']) && strlen($gt3_theme_post['page_settings']['portfolio']['work_link']) > 0) {
                                        $linkToTheWork = esc_url($gt3_theme_post['page_settings']['portfolio']['work_link']);
                                    } else {
                                        $linkToTheWork = get_permalink();
                                    }

                                    $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
                                    ?>
                                        <div class="fw_preview_wrapper" style="margin-bottom:20px;">
                                            <div class="gallery_item_wrapper">
                                                <a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                                                    <img src="<?php echo aq_resize($featured_image[0], "540", "", true, true, true); ?>" alt="" class="fw_featured_image" width="540">
                                                    <div class="gallery_fadder"></div>
                                                    <span class="gallery_ico"><i class="stand_icon icon-eye"></i></span>
                                                </a>
                                            </div>
                                            <div class="grid-port-cont">
                                                <h2><a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>><?php the_title(); ?></a></h2>
                                                <div class="block_likes">
                                                    <div class="post-views"><i class="stand_icon icon-eye"></i> <span><?php echo (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span></div>
                                                    <div class="gallery_likes gallery_likes_add <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "already_liked" : ""); ?>" data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                                        <i class="stand_icon <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                                        <span><?php echo ((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    $foundSomething = true;
                                endwhile;
                                wp_reset_query();

                                echo gt3_get_theme_pagination();

                                if ($foundSomething == false) {
                                    ?>
                                    <div class="block404" style="width:100%; text-align: center;">
                                        <h1 class="search_oops"><?php echo __('Oops!', 'theme_localization'); ?> <?php echo __('Not Found!', 'theme_localization'); ?></h1>

                                        <div class="search_form_wrap">
                                            <form name="search_field" method="get" action="<?php echo home_url(); ?>"
                                                  class="search_form" style="margin-top: 14px; margin-bottom: 40px;">
                                                <input type="text" name="s"
                                                       value=""
                                                       placeholder="<?php _e('Search the site...', 'theme_localization'); ?>"
                                                       class="field_search">
                                            </form>
                                        </div>
                                    </div>
                                <?php
                                }

                                echo '</div><div class="clear"></div></div>';
                                ?>
                            </div>
                        </div>
                        <?php get_sidebar('left'); ?>
                    </div>
                </div>
                <?php get_sidebar('right'); ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>
