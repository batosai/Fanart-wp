<?php

define("GT3_THEMENAME", "Oyster");
define("GT3_THEMESHORT", "oyster_");
define("IMGURL", get_template_directory_uri()."/img");
define("THEMEROOTURL", get_template_directory_uri());

if (!defined("GT3THEME_INSTALLED")) {
    define("GT3THEME_INSTALLED", true);
}

global $menu_i;
$menu_i = 0;

?>