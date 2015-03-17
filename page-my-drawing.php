<?php
/*
Template Name: Drawing - My
*/

if( !is_user_logged_in() ){
    wp_redirect(home_url()); exit;
}

global $current_user;

wp_redirect(get_author_posts_url($current_user->ID));
