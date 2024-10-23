<?php
function handle_lost_password_request()
{
  $errors = '';
  $success = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_login'])) {
    $user_login = sanitize_text_field($_POST['user_login']);

    if (empty($user_login)) {
      $errors = 'Please enter a username or email address.';
    } else {
      if (strpos($user_login, '@')) {
        $user_data = get_user_by('email', $user_login);
      } else {
        $user_data = get_user_by('login', $user_login);
      }

      if (!$user_data) {
        $errors = 'Invalid username or email address.';
      } else {
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
        $key = get_password_reset_key($user_data);

        if (is_wp_error($key)) {
          $errors = 'Error generating password reset key.';
        } else {
          $reset_url = home_url("/reset-password/?key=$key&login=" . rawurlencode($user_login));

          $message = __('Someone has requested a password reset for the following account:') . "\r\n\r\n";
          $message .= network_home_url('/') . "\r\n\r\n";
          $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
          $message .= __('If this was a mistake, ignore this email and nothing will happen.') . "\r\n\r\n";
          $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
          $message .= $reset_url . "\r\n";

          $title = sprintf(__('[%s] Password Reset'), wp_specialchars_decode(get_option('blogname'), ENT_QUOTES));

          if (wp_mail($user_email, wp_specialchars_decode($title), $message)) {
            $success = 'Password reset link has been sent to your email address.';
          } else {
            $errors = 'Error sending email. Please try again later.';
          }
        }
      }
    }
  }

  return [
    'errors' => $errors,
    'success' => $success
  ];
}

function custom_lost_password_redirect()
{
  if (
    isset($_SERVER['REQUEST_URI']) &&
    strpos($_SERVER['REQUEST_URI'], 'wp-login.php?action=lostpassword') !== false
  ) {
    wp_redirect(home_url('/lost-password/'));
    exit;
  }
}
add_action('init', 'custom_lost_password_redirect');

function custom_lostpassword_url($url)
{
  return home_url('/lost-password/');
}
add_filter('lostpassword_url', 'custom_lostpassword_url', 10, 1);
