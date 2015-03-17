<?php
/*
Template Name: Page categories
*/
if ( !post_password_required() ) {
    get_header('fullscreen');
?>
<?php
    $args = array(
      'child_of'   => 0,
      'parent'     => isset($_GET['slug']) ? $_GET['slug'] : '',
      'orderby'    => 'name',
      'hide_empty' => true,
      'order'      => 'ASC'
    );

    $categories = get_categories($args);

    $filters = get_categories();
?>


<div class="fullscreen_block grid_style" style="min-height: 616px; opacity: 1;">
        <div class="short_filter">
            <ul class="optionset" data-option-key="filter">
                <li class="selected"><a href="<?php echo site_url(); ?>/categories/" data-option-value="*">Tous</a></li>

                <?php foreach ($filters as $filter) : ?>
                    <?php if($filter->parent != '0') continue; ?>
                    <li><a data-option-value=".<?php echo $filter->slug ?>" href="<?php echo site_url(); ?>/categories/?slug=<?php echo $filter->term_id ?>" title="View all post filed under ">
                        <?php echo $filter->term_id == $_GET['slug'] ? '<strong>' : '' ?>
                        <?php echo $filter->name ?>
                        <?php echo $filter->term_id == $_GET['slug'] ? '</strong>' : '' ?>
                    </a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="fs_blog_module">

            <?php foreach($categories as $category) : ?>
                <?php if($category->parent == '0') continue; ?>
            <div class="post-<?php echo $category->term_id ?> port type-port status-publish format-image has-post-thumbnail hentry blogpost_preview_fw ">
                <div class="fw_preview_wrapper">
                    <div class="gallery_item_wrapper">
                        <a href="<?php echo get_category_link( $category->term_id ) ?>">
                            <img src="<?php echo z_taxonomy_image_url($category->term_id) ?>" alt="" class="fw_featured_image" width="540" height="350">
                            <div class="gallery_fadder"></div>
                            <span class="gallery_ico"><i class="stand_icon icon-eye"></i></span>
                        </a>
                    </div>
                    <div class="grid-port-cont">
                        <h2><a href="<?php echo get_category_link( $category->term_id ) ?>"><?php echo $category->name ?></a></h2>
                        <div class="block_likes">
                            <div class="gallery_likes">
                                <span><?php echo $category->count ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
</div>

<script>
    var posts_already_showed = <?php gt3_the_theme_option('fw_port_per_page'); ?>;

    <?php if (isset($gt3_theme_pagebuilder['portfolio']['port_type']) && $gt3_theme_pagebuilder['portfolio']['port_type'] == 'port_isotope') {?>
        function get_works() {
            gt3_get_isotope_posts("port", <?php gt3_the_theme_option('fw_port_per_page'); ?>, posts_already_showed, "port_grid2_template_isotope", ".fs_grid_portfolio" <?php echo (isset($selected_categories) && strlen($selected_categories)>0 ? ', "' . $selected_categories.'"' : "") ?>);
            posts_already_showed = posts_already_showed + <?php gt3_the_theme_option('fw_port_per_page'); ?>;
        }
        jQuery(document).ready(function () {
            jQuery('.load_more_works').click(function(){
                get_works();
            });
        });
    <?php } else {?>
        function get_works() {
            gt3_get_blog_posts("port", <?php gt3_the_theme_option('fw_port_per_page'); ?>, posts_already_showed, "port_grid2_template", ".fs_grid_portfolio" <?php echo (isset($selected_categories) && strlen($selected_categories)>0 ? ', "' . $selected_categories.'"' : "") ?>);
            posts_already_showed = posts_already_showed + <?php gt3_the_theme_option('fw_port_per_page'); ?>;
        }
        jQuery(document).ready(function () {
            jQuery(window).on('scroll', scrolling);
        });
    <?php } ?>

        jQuery(document).ready(function($){
            //setTimeout("workCheck()",300);
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
            setupGrid();
        });
        jQuery(window).resize(function(){
            setupGrid();
        });
        jQuery(window).load(function(){
            setupGrid();
        });

        function setupGrid() {
            jQuery('.fs-port-cont').each(function(){
                jQuery(this).css('margin-top', jQuery(this).parents('.grid-portfolio-item').find('img').height());
                jQuery(this).parents('.grid-item-trigger').find('a.grid-img-link').height(jQuery(this).parents('.grid-portfolio-item').find('img').height());
                jQuery(this).parents('.grid-item-trigger').css('height', jQuery(this).parents('.grid-portfolio-item').find('img').height());
            });
            jQuery('.grid-portfolio-item').bind({
                mouseover: function() {
                    jQuery(this).removeClass('unhovered');
                    jQuery(this).find('.grid-item-trigger').css('height', jQuery(this).find('img').height()+jQuery(this).find('.fs-port-cont').height());
                },
                mouseout: function() {
                    jQuery(this).addClass('unhovered');
                    jQuery(this).find('.grid-item-trigger').css('height', jQuery(this).find('img').height());
                }
            });
        }
</script>
<div class="preloader"></div>

<?php
    get_footer('fullwidth');
}
?>
