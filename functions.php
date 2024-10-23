<?php

if (! function_exists('wpdev_setup')) {
  function wpdev_setup()
  {
    add_theme_support('title-tag');
    // add_theme_support( 'automatic-feed-links' );
    add_theme_support('post-thumbnails');
    // add_image_size( 'wpdev-featured-image', 2000, 1200, true );
    // add_image_size( 'wpdev-thumbnail-avatar', 100, 100, true );
    // register_nav_menus( array(
    //   'primary' => esc_html__( 'Primary', 'wpdev' ),
    // ) );
  }
}

add_action('after_setup_theme', 'wpdev_setup');


function wpdev_scripts()
{
  wp_enqueue_style('main', get_stylesheet_uri(), array(), filemtime(get_stylesheet_directory() . '/style.css'));

  wp_enqueue_style('wpdev-reset', get_template_directory_uri() . '/css/reset.css', array(), filemtime(get_template_directory() . '/css/reset.css'));
  wp_enqueue_style('wpdev-variables', get_template_directory_uri() . '/css/variables.css', array('wpdev-reset'), filemtime(get_template_directory() . '/css/variables.css'));
  wp_enqueue_style('wpdev-main-css', get_template_directory_uri() . '/css/style.css', array('wpdev-variables'), filemtime(get_template_directory() . '/css/style.css'));

  wp_enqueue_script('main', get_template_directory_uri() . '/js/main.js', array(), filemtime(get_template_directory() . '/js/main.js'), true);
}
add_action('wp_enqueue_scripts', 'wpdev_scripts');

$functions_dir = get_template_directory() . '/functions/';
$functions_files = glob($functions_dir . '*.php');

foreach ($functions_files as $file) {
  if (is_file($file)) {
    require_once $file;
  }
}


// REMOVE ADMIN BAR
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar()
{
  if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
  }
}
