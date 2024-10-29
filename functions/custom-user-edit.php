<?php

if (!defined('ABSPATH')) {
  exit;
}

function process_profile_update($user_id)
{
  $messages = array();

  if (!isset($_POST['profile_nonce']) || !wp_verify_nonce($_POST['profile_nonce'], 'update_profile_nonce')) {
    return array(array('type' => 'error', 'text' => 'Security check failed'));
  }

  if (empty($_POST['user_email'])) {
    $messages[] = array('type' => 'error', 'text' => 'Email is required');
  } elseif (!is_email($_POST['user_email'])) {
    $messages[] = array('type' => 'error', 'text' => 'Please enter a valid email address');
  }

  if (!empty($messages)) {
    return $messages;
  }

  $email = sanitize_email($_POST['user_email']);
  $first_name = sanitize_text_field($_POST['first_name']);
  $last_name = sanitize_text_field($_POST['last_name']);

  $display_name = generate_display_name($first_name, $last_name, get_userdata($user_id)->user_login);

  $has_avatar_upload = isset($_FILES['custom_avatar']) && $_FILES['custom_avatar']['size'] > 0;
  if ($has_avatar_upload) {
    $avatar_result = process_avatar_upload($user_id);
    if (is_array($avatar_result) && isset($avatar_result[0]['type']) && $avatar_result[0]['type'] === 'error') {
      $messages = array_merge($messages, $avatar_result);
    }
  }

  if (!array_filter($messages, function ($msg) {
    return $msg['type'] === 'error';
  })) {
    $current_user = get_userdata($user_id);

    wp_update_user(array(
      'ID' => $user_id,
      'display_name' => $display_name,
      'first_name' => $first_name,
      'last_name' => $last_name
    ));

    if ($email !== $current_user->user_email && !email_exists($email)) {
      wp_update_user(array('ID' => $user_id, 'user_email' => $email));
    }

    $messages[] = array('type' => 'success', 'text' => 'Profile updated successfully');
  }

  return $messages;
}

function generate_display_name($first_name, $last_name, $username)
{
  $display_name = trim($first_name . ' ' . $last_name);

  if (empty(trim($display_name))) {
    return $username;
  } else if (empty($last_name)) {
    return $first_name;
  } else if (empty($first_name)) {
    return $last_name;
  }

  return $display_name;
}

function process_avatar_upload($user_id)
{
  if ($_FILES['custom_avatar']['size'] > 2 * 1024 * 1024) {
    return array(array('type' => 'error', 'text' => 'Image size should not exceed 2MB'));
  }

  require_once(ABSPATH . 'wp-admin/includes/image.php');
  require_once(ABSPATH . 'wp-admin/includes/file.php');
  require_once(ABSPATH . 'wp-admin/includes/media.php');

  $attachment_id = media_handle_upload('custom_avatar', 0);

  if (is_wp_error($attachment_id)) {
    return array(array('type' => 'error', 'text' => 'Error uploading avatar: ' . $attachment_id->get_error_message()));
  }

  update_user_meta($user_id, 'custom_avatar', $attachment_id);
  return true;
}

function add_custom_user_avatar_field($user)
{
?>
  <h3>Change Avatar</h3>
  <table class="form-table">
    <tr>
      <th><label for="custom_avatar">Upload Avatar</label></th>
      <td>
        <?php
        $avatar_id = get_user_meta($user->ID, 'custom_avatar', true);
        if ($avatar_id) {
          echo wp_get_attachment_image($avatar_id, 'thumbnail');
        }
        ?>
        <input type="file" name="custom_avatar" id="custom_avatar">
        <input type="hidden" name="custom_avatar_id" value="<?php echo esc_attr($avatar_id); ?>">
      </td>
    </tr>
  </table>
<?php
}
add_action('show_user_profile', 'add_custom_user_avatar_field');
add_action('edit_user_profile', 'add_custom_user_avatar_field');

function save_custom_user_avatar($user_id)
{
  if (!current_user_can('edit_user', $user_id)) {
    return false;
  }

  if (isset($_FILES['custom_avatar']) && $_FILES['custom_avatar']['size'] > 0) {
    $result = process_avatar_upload($user_id);
    return $result === true || !array_filter($result, function ($msg) {
      return $msg['type'] === 'error';
    });
  }

  return true;
}
add_action('personal_options_update', 'save_custom_user_avatar');
add_action('edit_user_profile_update', 'save_custom_user_avatar');

function get_custom_avatar($avatar, $id_or_email, $size, $default, $alt)
{
  $user = false;

  if (is_numeric($id_or_email)) {
    $user = get_user_by('id', (int) $id_or_email);
  } elseif (is_object($id_or_email)) {
    if (!empty($id_or_email->user_id)) {
      $user = get_user_by('id', (int) $id_or_email->user_id);
    }
  } else {
    $user = get_user_by('email', $id_or_email);
  }

  if ($user && is_object($user)) {
    $avatar_id = get_user_meta($user->ID, 'custom_avatar', true);
    if ($avatar_id) {
      $avatar_url = wp_get_attachment_image_src($avatar_id, array($size, $size));
      if ($avatar_url) {
        $avatar = sprintf(
          '<img alt="%s" src="%s" class="avatar avatar-%d photo" height="%d" width="%d" />',
          esc_attr($alt),
          esc_url($avatar_url[0]),
          (int) $size,
          (int) $size,
          (int) $size
        );
      }
    }
  }

  return $avatar;
}

function add_form_enctype()
{
  echo ' enctype="multipart/form-data"';
}
add_action('user_edit_form_tag', 'add_form_enctype');
add_filter('get_avatar', 'get_custom_avatar', 1, 5);
