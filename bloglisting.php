<?php
$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());

if (get_post_type() == "port") {
    $post_categ = '';
    $separator = ', ';
    $new_term_list = get_the_terms(get_the_id(), "portcat");
    if (is_array($new_term_list)) {
        foreach ($new_term_list as $term) {
            $post_categ = $post_categ . '<a href="'.get_term_link($term->slug, "portcat").'">'.$term->name.'</a>'.$separator;
        }
    }
} else {
    if(get_the_category()) $categories = get_the_category();
    $post_categ = '';
    $separator = ', ';
    if ($categories) {
        foreach($categories as $category) {
            $post_categ = $post_categ .'<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a>'.$separator;
        }
    }
}


if(get_the_tags() !== '') {
    $posttags = get_the_tags();

}
if ($posttags) {
    $post_tags = '';
    $post_tags_compile = '<span class="preview_meta_tags">tags:';
    foreach($posttags as $tag) {
        $post_tags = $post_tags . '<a href="?tag='.$tag->slug.'">'.$tag->name .'</a>'. ', ';
    }
    $post_tags_compile .= ' '.trim($post_tags, ', ').'</span>';
} else {
    $post_tags_compile = '';
}
	if (!isset($pf)) {
		$compile = '';
	}

	$compile .= '
	<div class="blog_post_preview"><div class="post_preview_wrapper">';
	$compile .= get_pf_type_output(array("pf" => get_post_format(), "gt3_theme_pagebuilder" => $gt3_theme_pagebuilder));
	// Top Elements
	$compile .= '<div class="blog_content">
					<div class="blogpreview_top">
						<div class="box_date">
							<span class="box_month">'. get_the_time("M") .'</span>
							<span class="box_day">'. get_the_time("d") .'</span>
						</div>
						<div class="listing_meta">
							<span>'. __('by', 'theme_localization') .' <a href="'.get_author_posts_url( get_the_author_meta('ID')).'">'.get_the_author_meta('display_name').'</a></span>									
							<span>'. __('in', 'theme_localization') .' '.trim($post_categ, ', ').'</span>
							<span><a href="' . get_comments_link() . '">'. get_comments_number(get_the_ID()) .' '. __('comments', 'theme_localization') .'</a></span>										
							'.$post_tags_compile.'
						</div >
					</div>
					<h3 class="blogpost_title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
		//Featured Image
		$compile .= '<article class="contentarea">
						' . ((strlen(get_the_excerpt()) > 0) ? get_the_excerpt() : do_shortcode(get_the_content()))   . '
					</article>
					<div class="preview_footer">
						<a href="' . get_permalink() . '" class="shortcode_button btn_small btn_type5 reamdore">'. __('Read More', 'theme_localization') .'</a>
					</div>
				</div>
	</div></div><!--.blog_post_preview -->';
	
	echo $compile;

	$GLOBALS['showOnlyOneTimeJS']['BlogListing'] = "
		<script>
			jQuery(document).ready(function($) {
			jQuery('.pf_output_container').each(function(){
				if (jQuery(this).html() == '') {
					jQuery(this).parents('.post_preview_wrapper').addClass('no_pf');
				} else {
					jQuery(this).parents('.post_preview_wrapper').addClass('has_pf');
				}
			});
			});
		</script>
	";
?>
