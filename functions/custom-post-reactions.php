<?php
function register_reaction_assets()
{
  if (is_single()) {
    $style_path = get_template_directory() . '/css/post-reactions.css';
    $script_path = get_template_directory() . '/js/post-reactions.js';
    wp_enqueue_style(
      'post-reactions-style',
      get_template_directory_uri() . '/css/post-reactions.css',
      array(),
      file_exists($style_path) ? filemtime($style_path) : '1.0.0'
    );
    wp_enqueue_script(
      'post-reactions',
      get_template_directory_uri() . '/js/post-reactions.js',
      array(),
      file_exists($script_path) ? filemtime($script_path) : '1.0.0',
      true
    );
    wp_localize_script('post-reactions', 'reactionsAjax', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('post_reaction_nonce')
    ));
  }
}
add_action('wp_enqueue_scripts', 'register_reaction_assets');

function add_reaction_buttons($content)
{
  if (!is_single()) {
    return $content;
  }

  $post_id = get_the_ID();
  $likes = intval(get_post_meta($post_id, '_likes_count', true));
  $dislikes = intval(get_post_meta($post_id, '_dislikes_count', true));

  $buttons = '
    <div class="post-reactions" data-post-id="' . esc_attr($post_id) . '">
        <button type="button" class="like-button" data-type="like">
            <svg class="thumbs-up-icon" viewBox="0 0 24 24">
                <path d="M9 21h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1l-6.59 6.59c-.36.36-.58.86-.58 1.41v10c0 1.1.9 2 2 2zm-4 0V9h-4v12h4z"/>
            </svg>
            <span class="likes-count">' . $likes . '</span>
        </button>
        <button type="button" class="dislike-button" data-type="dislike">
            <svg class="thumbs-down-icon" viewBox="0 0 24 24">
                <path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"/>
            </svg>
            <span class="dislikes-count">' . $dislikes . '</span>
        </button>
    </div>';

  return $content . $buttons;
}
add_filter('the_content', 'add_reaction_buttons');

function handle_post_reaction()
{
  if (!check_ajax_referer('post_reaction_nonce', 'nonce', false)) {
    wp_send_json_error('Invalid nonce');
  }

  $post_id = intval($_POST['post_id']);
  $type = sanitize_text_field($_POST['type']);
  $previous_type = sanitize_text_field($_POST['previousType']);

  if (!get_post($post_id)) {
    wp_send_json_error('Invalid post ID');
  }

  if (!in_array($type, ['like', 'dislike', 'none'])) {
    wp_send_json_error('Invalid reaction type');
  }

  if ($previous_type && $previous_type !== 'none') {
    $prev_meta_key = "_${previous_type}s_count";
    $prev_count = max(0, intval(get_post_meta($post_id, $prev_meta_key, true)) - 1);
    update_post_meta($post_id, $prev_meta_key, $prev_count);
  }

  if ($type !== 'none') {
    $meta_key = "_${type}s_count";
    $current_count = intval(get_post_meta($post_id, $meta_key, true)) + 1;
    update_post_meta($post_id, $meta_key, $current_count);
  }

  $updated_likes = intval(get_post_meta($post_id, '_likes_count', true));
  $updated_dislikes = intval(get_post_meta($post_id, '_dislikes_count', true));

  wp_send_json_success([
    'likes' => $updated_likes,
    'dislikes' => $updated_dislikes
  ]);
}
add_action('wp_ajax_post_reaction', 'handle_post_reaction');
add_action('wp_ajax_nopriv_post_reaction', 'handle_post_reaction');
