<?php
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
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $attachment_id = media_handle_upload('custom_avatar', 0);

    if (is_wp_error($attachment_id)) {
      return false;
    }

    update_user_meta($user_id, 'custom_avatar', $attachment_id);
  }
}

add_action('personal_options_update', 'save_custom_user_avatar');
add_action('edit_user_profile_update', 'save_custom_user_avatar');

function get_custom_avatar($avatar, $id_or_email, $size, $default, $alt)
{
  $user = false;

  if (is_numeric($id_or_email)) {
    $id = (int) $id_or_email;
    $user = get_user_by('id', $id);
  } elseif (is_object($id_or_email)) {
    if (!empty($id_or_email->user_id)) {
      $id = (int) $id_or_email->user_id;
      $user = get_user_by('id', $id);
    }
  } else {
    $user = get_user_by('email', $id_or_email);
  }

  if ($user && is_object($user)) {
    $avatar_id = get_user_meta($user->ID, 'custom_avatar', true);
    if ($avatar_id) {
      $avatar_url = wp_get_attachment_image_src($avatar_id, array($size, $size));
      if ($avatar_url) {
        $avatar = "<img alt='{$alt}' src='{$avatar_url[0]}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
      }
    }
  }

  return $avatar;
}

add_filter('get_avatar', 'get_custom_avatar', 1, 5);

function add_form_enctype()
{
  echo ' enctype="multipart/form-data"';
}

add_action('user_edit_form_tag', 'add_form_enctype');
