<?php
include_once('../../../../wp-config.php');

global $wpdb;

// _yoast_wpseo_metadesc
// Edward est un fanart de Fullmetal Alchemist réalisé par Hizerielle.

// _yoast_wpseo_title
// Edward de Fullmetal Alchemist par Hizerielle - Fan art

$posts = get_posts(array('posts_per_page' => -1));

foreach ($posts as $post) {
    $user       = get_user_by('id', $post->post_author);
    $categories = get_the_category($post->ID);
    $category   = current($categories);

    $meta_key   = '_yoast_wpseo_title';
    $meta_value = "{$post->post_title} de {$category->cat_name} par " . ucfirst($user->user_nicename) . " - Fan art";

    add_post_meta($post->ID, $meta_key, $meta_value);

    $meta_key   = '_yoast_wpseo_metadesc';
    $meta_value = "{$post->post_title} est un fanart de {$category->cat_name} réalisé par " . ucfirst($user->user_nicename) . ". Venez découvrir tous les fanart et donner votre avis.";

    add_post_meta($post->ID, $meta_key, $meta_value);
}
