<?php

#Upload images
add_action('wp_ajax_mix_ajax_post_action', 'mix_theme_upload_images');
function mix_theme_upload_images()
{
    if (is_admin()) {
        $save_type = $_POST['type'];

        if ($save_type == 'upload') {

            $clickedID = $_POST['data'];
            $filename = $_FILES[$clickedID];
            $filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']);

            $override['test_form'] = false;
            $override['action'] = 'wp_handle_upload';
            $uploaded_file = wp_handle_upload($filename, $override);
            $upload_tracking[] = $clickedID;
            gt3_update_theme_option($clickedID, $uploaded_file['url']);
            if (!empty($uploaded_file['error'])) {
                echo 'Upload Error: ' . $uploaded_file['error'];
            } else {
                echo esc_url($uploaded_file['url']);
            }
        }
    }

    die();
}

#Upload images
add_action('wp_ajax_gt3_get_blog_posts', 'gt3_get_blog_posts');
add_action('wp_ajax_nopriv_gt3_get_blog_posts', 'gt3_get_blog_posts');
function gt3_get_blog_posts()
{
    if ($_REQUEST['template_name'] == "fw_blog_template") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'cat' => $_REQUEST['categories'],
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );

        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
            $all_likes = gt3pb_get_option("likes");
            $gt3_theme_pagebuilder = get_post_meta(get_the_ID(), "pagebuilder", true);

            if (get_the_category()) $categories = get_the_category();
            $post_categ = '';
            $separator = ', ';
            if ($categories) {
                foreach ($categories as $category) {
                    $post_categ = $post_categ . '<a href="' . get_category_link($category->term_id) . '">' . $category->cat_name . '</a>' . $separator;
                }
            }

            ?>
            <div <?php post_class("blogpost_preview_fw newLoaded"); ?>>
                <div class="fw_preview_wrapper loading">
                    <?php echo get_pf_type_output(array("pf" => get_post_format(), "gt3_theme_pagebuilder" => $gt3_theme_pagebuilder, "width" => '570', "height" => '', "fw_post" => true)); ?>
                    <div class="blogpreview_top">
                        <div class="box_date">
                            <span class="box_month"><?php echo get_the_time("M"); ?></span>
                            <span class="box_day"><?php echo get_the_time("d"); ?></span>
                        </div>
                        <div class="listing_meta">
                            <span><?php _e('in', 'theme_localization') ?> <?php echo trim($post_categ, ', '); ?></span>
                            <span><a
                                    href="<?php echo get_comments_link(); ?>"><?php echo get_comments_number(get_the_ID()) ?> <?php _e('comments', 'theme_localization') ?></a></span>
                        </div>
                    </div>
                    <h6 class="blogpost_title"><a
                            href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></h6>

                    <div class="blogpost_fw_content">
                        <article class="contentarea">
                            <?php echo get_the_excerpt(); ?>
                        </article>
                    </div>
                    <div class="fw_preview_footer">
                        <?php if (gt3_get_theme_option("default_skin") == 'skin_dark') { ?>
                            <a href="<?php echo get_permalink(); ?>"
                               class="shortcode_button btn_small btn_type1_dark reamdore"><?php _e('Read More', 'theme_localization') ?></a>
                        <?php } else { ?>
                            <a href="<?php echo get_permalink(); ?>"
                               class="shortcode_button btn_small btn_type4 reamdore"><?php _e('Read More', 'theme_localization') ?></a>
                        <?php } ?>
                        <div class="block_likes">
                            <div class="post-views"><i class="stand_icon icon-eye"></i>
                                <span><?php echo(get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span>
                            </div>
                            <div
                                class="gallery_likes gallery_likes_add <?php echo (isset($_COOKIE['like_post' . get_the_ID()]) ? "already_liked" : "") . '" data-attachid="' . get_the_ID(); ?>"
                                data-modify="like_post">
                                <i class="stand_icon <?php echo(isset($_COOKIE['like_post' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile;
        wp_reset_query();
    }

    if ($_REQUEST['template_name'] == "port_massonry_template_isotope") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );

        $post_type_terms = array();
        if (isset($_REQUEST['categories']) && strlen($_REQUEST['categories']) > 0) {
            $post_type_terms = explode(",", $_REQUEST['categories']);
        }

        if (count($post_type_terms) > 0) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'portcat',
                    'field' => 'id',
                    'terms' => $post_type_terms
                )
            );
        }

        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
            $all_likes = gt3pb_get_option("likes");
            $gt3_theme_post = get_plugin_pagebuilder(get_the_ID());
            $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
            $pf = get_post_format();
            $target = (isset($gt3_theme_post['settings']['new_window']) && $gt3_theme_post['settings']['new_window'] == "on" ? "target='_blank'" : "");
            if (isset($gt3_theme_post['page_settings']['portfolio']['work_link']) && strlen($gt3_theme_post['page_settings']['portfolio']['work_link']) > 0) {
                $linkToTheWork = esc_url($gt3_theme_post['page_settings']['portfolio']['work_link']);
            } else {
                $linkToTheWork = get_permalink();
            }
            $echoallterm = '';
            $new_term_list = get_the_terms(get_the_id(), "portcat");
            if (is_array($new_term_list)) {
                foreach ($new_term_list as $term) {
                    $tempname = strtr($term->name, array(
                        ' ' => '-',
                    ));
                    $echoallterm .= strtolower($tempname) . " ";
                    $echoterm = $term->name;
                }
            } else {
                $tempname = 'Uncategorized';
            }
            ?>

            <div <?php post_class("blogpost_preview_fw element newLoaded " . $echoallterm); ?>
                data-category="<?php echo $echoallterm ?>">
                <div class="fw_preview_wrapper">
                    <div class="gallery_item_wrapper">
                        <a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                            <img src="<?php echo aq_resize($featured_image[0], "540", "", true, true, true); ?>" alt=""
                                 class="fw_featured_image" width="540">

                            <div class="gallery_fadder"></div>
                            <span class="gallery_ico"><i class="stand_icon icon-eye"></i></span>
                        </a>
                    </div>
                    <div class="grid-port-cont">
                        <h6><a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>><?php the_title(); ?></a>
                        </h6>

                        <div class="block_likes">
                            <div class="post-views"><i class="stand_icon icon-eye"></i>
                                <span><?php echo(get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span>
                            </div>
                            <div
                                class="gallery_likes gallery_likes_add <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile;
        wp_reset_query();
    }

    if ($_REQUEST['template_name'] == "port_grid_template_isotope") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );

        $post_type_terms = array();
        if (isset($_REQUEST['categories']) && strlen($_REQUEST['categories']) > 0) {
            $post_type_terms = explode(",", $_REQUEST['categories']);
        }

        if (count($post_type_terms) > 0) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'portcat',
                    'field' => 'id',
                    'terms' => $post_type_terms
                )
            );
        }

        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
            $all_likes = gt3pb_get_option("likes");
            $gt3_theme_post = get_plugin_pagebuilder(get_the_ID());
            $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
            $pf = get_post_format();
            $target = (isset($gt3_theme_post['settings']['new_window']) && $gt3_theme_post['settings']['new_window'] == "on" ? "target='_blank'" : "");
            if (isset($gt3_theme_post['page_settings']['portfolio']['work_link']) && strlen($gt3_theme_post['page_settings']['portfolio']['work_link']) > 0) {
                $linkToTheWork = esc_url($gt3_theme_post['page_settings']['portfolio']['work_link']);
            } else {
                $linkToTheWork = get_permalink();
            }
            $echoallterm = '';
            $new_term_list = get_the_terms(get_the_id(), "portcat");
            if (is_array($new_term_list)) {
                foreach ($new_term_list as $term) {
                    $tempname = strtr($term->name, array(
                        ' ' => '-',
                    ));
                    $echoallterm .= strtolower($tempname) . " ";
                    $echoterm = $term->name;
                }
            } else {
                $tempname = 'Uncategorized';
            }
            ?>

            <div <?php post_class("blogpost_preview_fw element newLoaded " . $echoallterm); ?>
                data-category="<?php echo $echoallterm ?>">
                <div class="fw_preview_wrapper">
                    <div class="gallery_item_wrapper">
                        <a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                            <img src="<?php echo aq_resize($featured_image[0], "540", "350", true, true, true); ?>"
                                 alt="" class="fw_featured_image" width="540" height="350">

                            <div class="gallery_fadder"></div>
                            <span class="gallery_ico"><i class="stand_icon icon-eye"></i></span>
                        </a>
                    </div>
                    <div class="grid-port-cont">
                        <h6><a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>><?php the_title(); ?></a>
                        </h6>

                        <div class="block_likes">
                            <div class="post-views"><i class="stand_icon icon-eye"></i>
                                <span><?php echo(get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span>
                            </div>
                            <div
                                class="gallery_likes gallery_likes_add <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile;
        wp_reset_query();
    }

    if ($_REQUEST['template_name'] == "port_grid2_template_isotope") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );

        $post_type_terms = array();
        if (isset($_REQUEST['categories']) && strlen($_REQUEST['categories']) > 0) {
            $post_type_terms = explode(",", $_REQUEST['categories']);
        }

        if (count($post_type_terms) > 0) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'portcat',
                    'field' => 'id',
                    'terms' => $post_type_terms
                )
            );
        }

        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
            $all_likes = gt3pb_get_option("likes");
            $gt3_theme_post = get_plugin_pagebuilder(get_the_ID());
            $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
            $pf = get_post_format();
            $target = (isset($gt3_theme_post['settings']['new_window']) && $gt3_theme_post['settings']['new_window'] == "on" ? "target='_blank'" : "");
            if (isset($gt3_theme_post['page_settings']['portfolio']['work_link']) && strlen($gt3_theme_post['page_settings']['portfolio']['work_link']) > 0) {
                $linkToTheWork = esc_url($gt3_theme_post['page_settings']['portfolio']['work_link']);
            } else {
                $linkToTheWork = get_permalink();
            }
            $echoallterm = '';
            $new_term_list = get_the_terms(get_the_id(), "portcat");
            if (is_array($new_term_list)) {
                foreach ($new_term_list as $term) {
                    $tempname = strtr($term->name, array(
                        ' ' => '-',
                    ));
                    $echoallterm .= strtolower($tempname) . " ";
                    $echoterm = $term->name;
                }
            } else {
                $tempname = 'Uncategorized';
            }
            ?>
            <div <?php post_class("grid-portfolio-item unhovered newLoaded element " . $echoallterm); ?>
                data-category="<?php echo $echoallterm ?>">
                <img src="<?php echo aq_resize($featured_image[0], "540", "376", true, true, true); ?>" alt=""
                     class="fw_featured_image grid-img" width="540" height="376">

                <div class="grid-item-trigger">
                    <a class="grid-img-link" href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>></a>

                    <div class="fs-port-cont">
                        <div class="fs-portcont-wrapper">
                            <h6><a class="grid-title-link"
                                   href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>><?php the_title(); ?></a>
                            </h6>

                            <div class="block_likes">
                                <div class="post-views"><i class="stand_icon icon-eye"></i>
                                    <span><?php echo(get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span>
                                </div>
                                <div
                                    class="gallery_likes gallery_likes_add <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                    data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                    <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                    <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile;
        wp_reset_query();
    }

    if ($_REQUEST['template_name'] == "port_grid_template") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );

        $post_type_terms = array();
        if (isset($_REQUEST['categories']) && strlen($_REQUEST['categories']) > 0) {
            $post_type_terms = explode(",", $_REQUEST['categories']);
        }

        if (count($post_type_terms) > 0) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'portcat',
                    'field' => 'id',
                    'terms' => $post_type_terms
                )
            );
        }

        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
            $all_likes = gt3pb_get_option("likes");
            $gt3_theme_post = get_plugin_pagebuilder(get_the_ID());
            $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
            $pf = get_post_format();
            $target = (isset($gt3_theme_post['settings']['new_window']) && $gt3_theme_post['settings']['new_window'] == "on" ? "target='_blank'" : "");
            if (isset($gt3_theme_post['page_settings']['portfolio']['work_link']) && strlen($gt3_theme_post['page_settings']['portfolio']['work_link']) > 0) {
                $linkToTheWork = esc_url($gt3_theme_post['page_settings']['portfolio']['work_link']);
            } else {
                $linkToTheWork = get_permalink();
            }

            ?>

            <div <?php post_class("blogpost_preview_fw newLoaded"); ?>>
                <div class="fw_preview_wrapper loading">
                    <div class="gallery_item_wrapper">
                        <a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                            <img src="<?php echo aq_resize($featured_image[0], "540", "350", true, true, true); ?>"
                                 alt="" class="fw_featured_image" width="540" height="350">

                            <div class="gallery_fadder"></div>
                            <span class="gallery_ico"><i class="stand_icon icon-eye"></i></span>
                        </a>
                    </div>
                    <div class="grid-port-cont">
                        <h6><a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>><?php the_title(); ?></a>
                        </h6>

                        <div class="block_likes">
                            <div class="post-views"><i class="stand_icon icon-eye"></i>
                                <span><?php echo(get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span>
                            </div>
                            <div
                                class="gallery_likes gallery_likes_add <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile;
        wp_reset_query();
    }

    if ($_REQUEST['template_name'] == "port_massonry_template") {

        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );

        if(isset($_REQUEST['author'])) {
            $args['author__in'] = array($_REQUEST['author']);
        }

        if(isset($_REQUEST['categories'])) {
            $args['cat'] = $_REQUEST['categories'];
        }

        // $post_type_terms = array();
        // if (isset($_REQUEST['categories']) && strlen($_REQUEST['categories']) > 0 && $_REQUEST['categories'] > 0) {
        //     $post_type_terms = explode(",", $_REQUEST['categories']);
        // }

        // if (count($post_type_terms) > 0) {
        //     $args['tax_query'] = array(
        //         array(
        //             'taxonomy' => 'portcat',
        //             'field' => 'id',
        //             'terms' => $post_type_terms
        //         )
        //     );
        // }

        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
            $all_likes = gt3pb_get_option("likes");
            $gt3_theme_post = get_plugin_pagebuilder(get_the_ID());
            $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
            $pf = get_post_format();
            $target = (isset($gt3_theme_post['settings']['new_window']) && $gt3_theme_post['settings']['new_window'] == "on" ? "target='_blank'" : "");
            if (isset($gt3_theme_post['page_settings']['portfolio']['work_link']) && strlen($gt3_theme_post['page_settings']['portfolio']['work_link']) > 0) {
                $linkToTheWork = esc_url($gt3_theme_post['page_settings']['portfolio']['work_link']);
            } else {
                $linkToTheWork = get_permalink();
            }

            ?>
            <div <?php post_class("blogpost_preview_fw newLoaded"); ?>>
                <div class="fw_preview_wrapper loading">
                    <div class="gallery_item_wrapper">
                        <a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                            <img src="<?php echo aq_resize($featured_image[0], "540", "", true, true, true); ?>" alt=""
                                 class="fw_featured_image" width="540">

                            <div class="gallery_fadder"></div>
                            <span class="gallery_ico"><i class="stand_icon icon-eye"></i></span>
                        </a>
                    </div>
                    <div class="grid-port-cont">
                        <h6><a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>><?php the_title(); ?></a>
                        </h6>

                        <div class="block_likes">
                            <div class="post-views"><i class="stand_icon icon-eye"></i>
                                <span><?php echo(get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span>
                            </div>
                            <div
                                class="gallery_likes gallery_likes_add <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile;
        wp_reset_query();
    }


    if ($_REQUEST['template_name'] == "port_grid2_template") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );

        if(isset($_REQUEST['author'])) {
            $args['author__in'] = array($_REQUEST['author']);
        }

        $post_type_terms = array();
        if (isset($_REQUEST['categories']) && strlen($_REQUEST['categories']) > 0) {
            $post_type_terms = explode(",", $_REQUEST['categories']);
        }

        if (count($post_type_terms) > 0) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'portcat',
                    'field' => 'id',
                    'terms' => $post_type_terms
                )
            );
        }

        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
            $all_likes = gt3pb_get_option("likes");
            $gt3_theme_post = get_plugin_pagebuilder(get_the_ID());
            $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
            $pf = get_post_format();
            $target = (isset($gt3_theme_post['settings']['new_window']) && $gt3_theme_post['settings']['new_window'] == "on" ? "target='_blank'" : "");
            if (isset($gt3_theme_post['page_settings']['portfolio']['work_link']) && strlen($gt3_theme_post['page_settings']['portfolio']['work_link']) > 0) {
                $linkToTheWork = esc_url($gt3_theme_post['page_settings']['portfolio']['work_link']);
            } else {
                $linkToTheWork = get_permalink();
            }

            ?>
            <div <?php post_class("grid-portfolio-item unhovered newLoaded loading anim_el"); ?>>
                <img src="<?php echo aq_resize($featured_image[0], "540", "376", true, true, true); ?>" alt=""
                     class="fw_featured_image grid-img" width="540" height="376">

                <div class="grid-item-trigger">
                    <a class="grid-img-link" href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>></a>

                    <div class="fs-port-cont">
                        <div class="fs-portcont-wrapper">
                            <h6><a class="grid-title-link"
                                   href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>><?php the_title(); ?></a>
                            </h6>

                            <div class="block_likes">
                                <div class="post-views"><i class="stand_icon icon-eye"></i>
                                    <span><?php echo(get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span>
                                </div>
                                <div
                                    class="gallery_likes gallery_likes_add <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                    data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                    <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                    <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile;
        wp_reset_query();
    }
    die();
}

#Get last slide ID
add_action('wp_ajax_get_unused_id_ajax', 'get_unused_id_ajax');
if (!function_exists('get_unused_id_ajax')) {
    function get_unused_id_ajax()
    {
        $lastid = gt3_get_theme_option("last_slide_id");
        if ($lastid < 3) {
            $lastid = 2;
        }
        $lastid++;

        $mystring = home_url();
        $findme = 'gt3themes';
        $pos = strpos($mystring, $findme);

        if ($pos === false) {
            echo $lastid;
        } else {
            echo str_replace(array("/", "-", "_"), "", substr(wp_get_theme()->get('ThemeURI'), -4, 3)) . date("d") . date("m") . $lastid;
        }

        gt3_update_theme_option("last_slide_id", $lastid);

        die();
    }
}


add_action('wp_ajax_add_like_post', 'gt3_add_like_post');
add_action('wp_ajax_nopriv_add_like_post', 'gt3_add_like_post');
function gt3_add_like_post()
{
    $post_id = absint($_POST['post_id']);
    $post_likes = (get_post_meta($post_id, "post_likes", true) > 0 ? get_post_meta($post_id, "post_likes", true) : "0");
    $new_likes = absint($post_likes) + 1;
    update_post_meta($post_id, "post_likes", $new_likes);
    echo $new_likes;
    die();
}

#Load portfolio works
add_action('wp_ajax_get_portfolio_works', 'get_portfolio_works');
add_action('wp_ajax_nopriv_get_portfolio_works', 'get_portfolio_works');
if (!function_exists('get_portfolio_works')) {
    function get_portfolio_works()
    {
        $html_template = esc_attr($_POST['html_template']);
        $now_open_works = absint($_POST['now_open_works']);
        $works_per_load = absint($_POST['works_per_load']);
        $category = (esc_attr($_POST['category']) !== "all" ? esc_attr($_POST['category']) : '');

        $wp_query = new WP_Query();
        $args = array(
            'post_type' => 'port',
            'order' => 'DESC',
            'post_status' => 'publish',
            'offset' => $now_open_works,
            'posts_per_page' => $works_per_load,
        );

        if (strlen($category) > 0) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'portcat',
                    'field' => 'slug',
                    'terms' => $category
                )
            );
        }
        $wp_query->query($args);

        $i = 1;

        while ($wp_query->have_posts()) : $wp_query->the_post();

            $pf = get_post_format();
            if (empty($pf)) $pf = "text";
            $pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());

            $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'single-post-thumbnail');
            if (strlen($featured_image[0]) < 1) {
                $featured_image[0] = IMGURL . "/core/your_image_goes_here.jpg";
            }

            if (isset($pagebuilder['settings']['external_link']) && strlen($pagebuilder['settings']['external_link']) > 0) {
                $linkToTheWork = $pagebuilder['settings']['external_link'];
                $target = "target='_blank'";
            } else {
                $linkToTheWork = get_permalink();
                $target = "";
            }

            if (isset($pagebuilder['settings']['time_spent']) && strlen($pagebuilder['settings']['time_spent']) > 0) {
                $time_spent_value = $pagebuilder['settings']['time_spent'];
                $time_spent_html = '<div class="portfolio_descr_time">' . ((get_theme_option("translator_status") == "enable") ? get_text("translator_time_spent") : __('Time spent', 'theme_localization')) . ': <span>' . $time_spent_value . '</span></div>';
            } else {
                $time_spent_value = '';
                $time_spent_html = '';
            }

            if (!isset($echoallterm)) {
                $echoallterm = '';
            }
            $new_term_list = get_the_terms(get_the_id(), "portcat");
            if (is_array($new_term_list)) {
                foreach ($new_term_list as $term) {
                    $tempname = strtr($term->name, array(
                        ' ' => '-',
                    ));
                    $echoallterm .= strtolower($tempname) . " ";
                    $echoterm = $term->name;
                }
            }

            #Portfolio grid
            if ($html_template == "fs_grid_module") {
                $pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
                if (isset($pagebuilder['settings']['elink']) && $pagebuilder['settings']['elink'] !== '') {
                    $set_link = $pagebuilder['settings']['elink'];
                    $set_target = 'target = "_blank"';
                } else {
                    $set_link = get_permalink();
                    $set_target = '';
                }
                echo '
				<div data-category="' . $echoallterm . '" class="' . $echoallterm . ' element portfolio_item loading">
					<div class="portfolio_item_wrapper">
						<div class="portfolio_item_img gallery_item_wrapper">
							<a href="' . $set_link . '" ' . $set_target . '>
								<img src="' . aq_resize($featured_image[0], "460", "460", true, true, true) . '" alt="" width="460" height="460">
								<div class="gallery_fadder"></div>
								<h4 class="portfolio_title">' . get_the_title() . '</h4>
							</a>
						</div>
					</div>
				</div>
                ';
            }
            #END

            $i++;
            unset($echoallterm, $pf);
        endwhile;

        die();
    }
}


#Ajax import xml
add_action('wp_ajax_ajax_import_dump', 'ajax_import_dump');
if (!function_exists('ajax_import_dump')) {
    function ajax_import_dump()
    {
        if (is_admin()) {
            if (!defined('WP_LOAD_IMPORTERS')) {
                define('WP_LOAD_IMPORTERS', true);
            }

            require_once(TEMPLATEPATH . '/core/xml-importer/importer.php');

            try {
                ob_start();
                $importer = new WP_Import();
                $importer->import(TEMPLATEPATH . '/core/xml-importer/import.xml');
                ob_clean();
            } catch (Exception $e) {
                die(json_encode(array(
                    'message' => $e->getMessage()
                )));
            }
            die(json_encode(array(
                'message' => 'Data was imported successfully'
            )));
        }
    }
}

?>
