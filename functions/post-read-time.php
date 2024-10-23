<?php
function add_read_time_meta_box()
{
  add_meta_box(
    'read_time_meta_box',
    'Read Time',
    'show_read_time_meta_box',
    'post',
    'side',
    'high'
  );
}
add_action('add_meta_boxes', 'add_read_time_meta_box');

function show_read_time_meta_box($post)
{
  $read_time = get_post_meta($post->ID, 'read_time', true);
?>
  <label for="read_time">Read Time (in minutes):</label>
  <input type="number" id="read_time" name="read_time" value="<?php echo esc_attr($read_time); ?>" min="1">
<?php
}

function save_read_time_meta($post_id)
{
  if (array_key_exists('read_time', $_POST)) {
    update_post_meta(
      $post_id,
      'read_time',
      sanitize_text_field($_POST['read_time'])
    );
  }
}
add_action('save_post', 'save_read_time_meta');
