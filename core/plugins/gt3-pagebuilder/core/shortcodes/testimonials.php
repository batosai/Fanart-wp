<?php

class testimonials_shortcode
{
    public function register_shortcode($shortcodeName)
    {
        function shortcode_testimonials($atts, $content = null)
        {
            if (!isset($compile)) {$compile='';}

            extract(shortcode_atts(array(
                'heading_alignment' => 'left',
                'heading_size' => $GLOBALS["pbconfig"]['default_heading_in_module'],
                'heading_color' => '',
                'heading_text' => '',
                'cpt_ids' => '0',
                'testimonials_in_line' => 1,
                'sorting_type' => "new",
            ), $atts));

            if ($testimonials_in_line < 1) {$testimonials_in_line = 1;}
			$testimonial_width = (100/$testimonials_in_line);
			
			if ( $testimonials_in_line == 1) {
				$testimonial_class = "single";
			} else if ( $testimonials_in_line == 2) {
				$testimonial_class = "double";
			} else {
				$testimonial_class = "default";
			}
            #heading
            if (strlen($heading_color) > 0) {
                $custom_color = "color:#{$heading_color};";
            }
            if (strlen($heading_text) > 0) {
                $compile .= "<div class='bg_title'><a href='".esc_js("javascript:void(0)")."' class='btn_carousel_left'></a><".$heading_size." style='".(isset($custom_color) ? $custom_color : '') . ((strlen($heading_alignment) > 0 && $heading_alignment !== 'left') ? 'text-align:'.$heading_alignment.';' : '')."' class='headInModule'>{$heading_text}</".$heading_size."><a href='".esc_js("javascript:void(0)")."' class='btn_carousel_right'></a></div>";
            }

            #sort converter
            switch ($sorting_type) {
                case "new":
                    $sort_type = "post_date";
                    break;
                case "random":
                    $sort_type = "rand";
                    break;
            }

            $compile .= "
			<div class='module_content testimonials_list'>
			    <ul class='".$testimonial_class."'>";
            $args = array(
                'post_type' => "testimonials",
                'orderby' => $sort_type,
                'include' => (string)$cpt_ids,
                'post_status' => 'publish');

            $posts = get_posts($args);

            if (is_array($posts)) {
                foreach ($posts as $post) {
                    $gt3_theme_pagebuilder = get_post_meta($post->ID, "pagebuilder", true);

                    $testimonials_author = $gt3_theme_pagebuilder['page_settings']['testimonials']['testimonials_author'];
                    $testimonials_company = $gt3_theme_pagebuilder['page_settings']['testimonials']['company'];
                    $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );

                    if(strlen($testimonials_company) > 0) {
                        $coma=", ";
                    } else {
                        $coma="";
                    }

                    $compile .= "
                            <li style='width:". $testimonial_width ."%'>
                                <div class='item'>
									<div class='testimonial_item_wrapper'>
										<div class='testimonials_photo'>
												".(strlen($featured_image[0])>0 ? "<img alt='' src='".aq_resize($featured_image[0], "128", "128", true, true, true)."' class='testimonials_ava'>" : "")."
										</div>
										<div class='testimonials_text'>
											<h6 class='testimonials_title'>{$testimonials_author}, {$testimonials_company}</h6>
											<p>" . $post->post_content . "</p>
										</div>
									</div>
								</div>
                            </li>
                        ";
                }
            }

            $compile .= "    </ul>
			</div>";

            return $compile;

        }

        add_shortcode($shortcodeName, 'shortcode_testimonials');
    }
}

#Shortcode name
$shortcodeName = "testimonials";
$testimonials = new testimonials_shortcode();
$testimonials->register_shortcode($shortcodeName);

?>