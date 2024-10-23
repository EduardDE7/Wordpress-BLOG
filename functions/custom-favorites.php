<?php
function get_user_favorites()
{
  if (!is_user_logged_in()) {
    return array();
  }

  $user_id = get_current_user_id();
  $favorites = get_user_meta($user_id, 'favorite_posts', true);

  return is_array($favorites) ? array_filter($favorites) : array();
}

function filter_favorites_search($query)
{
  if (
    !is_admin() &&
    $query->is_main_query() &&
    ($query->is_search() || isset($_GET['favorites'])) &&
    isset($_GET['favorites']) &&
    $_GET['favorites'] == '1'
  ) {

    $favorites = get_user_favorites();

    if (!empty($favorites)) {
      $query->set('post__in', $favorites);
      $query->set('orderby', 'post__in');

      if (get_search_query()) {
        $query->set('s', get_search_query());
      }

      if (!$query->get('posts_per_page')) {
        $query->set('posts_per_page', get_option('posts_per_page'));
      }
    } else {
      $query->set('post__in', array(0));
    }

    $query->set('post_status', 'publish');
  }

  return $query;
}
add_action('pre_get_posts', 'filter_favorites_search');

function add_favorites_meta_box()
{
  add_meta_box(
    'favorites_meta_box',
    'Favorites',
    'display_favorites_meta_box',
    'post',
    'side',
    'high'
  );
}
add_action('add_meta_boxes', 'add_favorites_meta_box');

function display_favorites_meta_box($post)
{
  $favorites_count = get_post_meta($post->ID, 'favorites_count', true);
  echo '<p>Added to favorites: ' . ($favorites_count ? $favorites_count : '0') . ' times</p>';
}

function is_post_favorited($post_id, $user_id = null)
{
  if (!$user_id) {
    $user_id = get_current_user_id();
  }

  if (!$user_id) {
    return false;
  }

  $favorites = get_user_meta($user_id, 'favorite_posts', true);
  if (!is_array($favorites)) {
    return false;
  }

  return in_array($post_id, $favorites);
}


function handle_favorite_action()
{
  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'favorites_nonce')) {
    wp_send_json_error('Security error', 403);
    exit;
  }

  if (!is_user_logged_in()) {
    wp_send_json_error('Authorization required', 401);
    exit;
  }

  if (!isset($_POST['post_id'])) {
    wp_send_json_error('Post ID is missing', 400);
    exit;
  }

  $post_id = intval($_POST['post_id']);
  $user_id = get_current_user_id();

  if (!get_post($post_id)) {
    wp_send_json_error('Post not found', 404);
    exit;
  }

  $favorites = get_user_meta($user_id, 'favorite_posts', true);
  if (!is_array($favorites)) {
    $favorites = array();
  }
  $is_favorited = in_array($post_id, $favorites);

  if ($is_favorited) {
    $favorites = array_diff($favorites, array($post_id));
    $favorites_count = get_post_meta($post_id, 'favorites_count', true);
    update_post_meta($post_id, 'favorites_count', max(0, intval($favorites_count) - 1));
    $message = 'Removed from favorites';
  } else {
    $favorites[] = $post_id;
    $favorites_count = get_post_meta($post_id, 'favorites_count', true);
    update_post_meta($post_id, 'favorites_count', intval($favorites_count) + 1);
    $message = 'Added to favorites';
  }

  update_user_meta($user_id, 'favorite_posts', array_unique($favorites));

  wp_send_json_success(array(
    'message' => $message,
  ));
}
add_action('wp_ajax_handle_favorite', 'handle_favorite_action');

function the_favorite_button($post_id = null, $classes = '')
{
  if (!is_user_logged_in()) {
    return;
  }

  if (!$post_id) {
    $post_id = get_the_ID();
  }

  $user_id = get_current_user_id();
  $is_favorited = is_post_favorited($post_id, $user_id);

  $button_class = $is_favorited ? 'active' : '';

  $html = sprintf(
    '<button class="favorite-button %s" data-post-id="%d">
      <svg width="11" height="14" viewBox="0 0 11 14" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0.812795 13.5804L0.81169 13.5797C0.748584 13.5439 0.695531 13.4914 0.658361 13.4273C0.621217 13.3632 0.601399 13.29 0.601227 13.2152V1.81762H0.601316L0.601138 1.80963C0.592825 1.43676 0.730219 1.07631 0.982363 0.806277C1.23301 0.537843 1.57683 0.38028 1.93969 0.365918H9.3025C9.66536 0.38028 10.0092 0.537843 10.2598 0.806277C10.512 1.07631 10.6494 1.43676 10.6411 1.80963L10.641 1.80963V1.81762V13.2143C10.6404 13.2878 10.6208 13.3596 10.5846 13.4226C10.5482 13.4859 10.4964 13.538 10.4346 13.574L10.4346 13.574C10.3725 13.6103 10.3024 13.6292 10.2312 13.6292C10.1601 13.6292 10.0899 13.6103 10.0278 13.574L10.0252 13.5725L5.66863 11.0785L5.48513 10.9734L5.30438 11.0832L1.20907 13.5694L1.20905 13.5694L1.20452 13.5722C1.14459 13.6098 1.0764 13.6309 1.00657 13.6338C0.938828 13.6327 0.872215 13.6144 0.812795 13.5804Z" stroke="currentColor" stroke-width="0.717188"/>
      </svg>
    </button>',
    esc_attr($button_class),
    esc_attr($post_id),
  );

  echo $html;
}

function add_favorites_scripts()
{
  if (is_user_logged_in()) {
    wp_enqueue_script('favorites-script', get_template_directory_uri() . '/js/favorites.js', array(), filemtime(get_template_directory() . '/js/favorites.js'), true);
    wp_localize_script('favorites-script', 'favoritesAjax', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('favorites_nonce'),
    ));
  }
}

add_action('wp_enqueue_scripts', 'add_favorites_scripts');
