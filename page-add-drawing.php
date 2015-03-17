<?php
/*
Template Name: Drawing - Add
*/

global $current_user;

if( !is_user_logged_in() ){
    wp_redirect(home_url()); exit;
}

wp_enqueue_script('typeahead_js', get_template_directory_uri() . '/js/typeahead.bundle.js', array(), false, true);

$categories = get_categories();
$categoriesJS = '';

foreach ($categories as $k =>$category) {
    if($category->parent == '0') continue;
    $categoriesJS .= '"' . $category->name . '"';
    if(count($categories)+1 != $k) $categoriesJS .= ',';
}

if(isset($_POST['title'])) {
    $error = '';
    if(empty($_POST['title']) || strlen($_POST['title']) < 3 ){
        $error .= 'Le titre est obligatoire et doit contenir trois caractères minimum.<br />';
    }

    if(empty($_POST['description']) || strlen($_POST['description']) < 5){
        $error .= 'La description est obligatoire et doit contenir cinq caractères minimum. <br />';
    }

    if(empty($_POST['category'])){
        $error .= 'La catégorie est obligatoire. <br />';
    }

    if(!is_uploaded_file($_FILES["image"]["tmp_name"])) {
        $error .= 'L\'image est obligatoire.';
    }

    if($error == '') {

        $post = array(
            'post_title'   => $_POST['title'],
            'post_content' => $_POST['description'],
            'post_status'  => 'draft',
            'post_type'    => 'post',
            'post_date'    => date('Y-m-d H:i:s'),
            'post_author'  => $current_user->ID,
        );

        if (!($cat = get_term_by('name', $_POST['category'], 'category'))) {
            // $term = wp_insert_term($_POST['category'], "category");
            // $cat = get_category($term['term_id']);

            $post['tags_input'] = $_POST['category'] . ', ' . $current_user->display_name;
        }
        else {
            $post['post_category'] = array($cat->term_id);
            $post['tags_input'] = $current_user->display_name;
        }



        $post_id = wp_insert_post($post);

        $file          = wp_handle_upload($_FILES['image'], array('test_form' => false));
        $filename      = $_FILES['image']['name'];
        $filetype      = wp_check_filetype( basename( $filename ), null );
        $wp_upload_dir = wp_upload_dir();

        $attachment = array(
            'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
            'post_mime_type' => $filetype['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        $attach_id = wp_insert_attachment($attachment, $wp_upload_dir['subdir'] . '/' . $filename, $post_id);

        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $attach_data = wp_generate_attachment_metadata($attach_id, $file['file']);
        wp_update_attachment_metadata($attach_id, $attach_data);

        set_post_thumbnail($post_id, $attach_id);
    }
}

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

                        <?php if($error): ?>
                        <div style="padding-bottom:55px;margin-left:0;" class="span12  module_number_32 module_cont  module_messageboxes">
                            <div class="module_content  shortcode_messagebox box_type5">
                                <span class="box_icon"><i class="icon-times-circle"></i></span>
                                <div class="box_content"><p><?php echo $error; ?></p></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <?php endif;?>

                        <div id="wpmem_reg">
                            <form name="form" method="post" action="" id="" enctype="multipart/form-data" class="form">
                                <label for="title" class="text">Titre<font class="req">*</font></label>
                                <div class="div_text">
                                    <input name="title" type="text" id="title" value="" class="textbox" />
                                </div>
                                <label for="description" class="text">Description<font class="req">*</font></label>
                                <div class="div_text">
                                    <textarea name="description" id="description" class="textbox"></textarea>
                                </div>
                                <label for="category" class="text">Catégorie<font class="req">*</font></label>
                                <div class="div_text">
                                    <input name="category" type="text" id="category" value="" class="textbox" />
                                </div>
                                <label for="image" class="text">Image<font class="req">*</font></label>
                                <div class="div_text">
                                    <input name="image" type="file" id="image" value="" class="textbox" />
                                </div>
                                <div>
                                    <input type="submit" value="Envoyer" />
                                </div>
                            </form>
                        </div>

                    <?php } ?>
                    </div>
                    <?php get_sidebar('left'); ?>
                </div>
            </div>
            <?php get_sidebar('right'); ?>
        </div>
    </div>
</div>

<script type="text/javascript">

jQuery(document).ready(function ($) {

    var substringMatcher = function(strs) {
      return function findMatches(q, cb) {
        var matches, substrRegex;

        matches = [];
        substrRegex = new RegExp(q, 'i');

        $.each(strs, function(i, str) {
          if (substrRegex.test(str)) {
            matches.push({ value: str });
          }
        });

        cb(matches);
      };
    };

    var states = [<?php echo $categoriesJS; ?>
    ];

    $('#category').typeahead({
      hint: true,
      highlight: true,
      minLength: 1
    },
    {
      name: 'states',
      displayKey: 'value',
      source: substringMatcher(states)
    });



});

</script>

<?php
    get_footer();
    get_footer('fullscreen');
?>
