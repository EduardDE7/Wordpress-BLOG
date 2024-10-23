<?php

function custom_password_reset_redirect()
{
  if (isset($_SERVER['REQUEST_URI'])) {
    if (strpos($_SERVER['REQUEST_URI'], 'wp-login.php?action=rp') !== false) {
      $key = isset($_GET['key']) ? $_GET['key'] : '';
      $login = isset($_GET['login']) ? $_GET['login'] : '';

      if (!empty($key) && !empty($login)) {
        wp_redirect(home_url("/reset-password/?key=$key&login=$login"));
        exit;
      }
    }
  }
}
add_action('init', 'custom_password_reset_redirect');

function validate_password_reset_key($key, $login)
{
  $user = check_password_reset_key($key, $login);

  if (is_wp_error($user)) {
    return [
      'valid' => false,
      'error' => 'This password reset link has expired or is invalid.'
    ];
  }

  return [
    'valid' => true,
    'user' => $user
  ];
}

function handle_password_reset()
{
  if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['reset_password'])) {
    return ['errors' => '', 'success' => ''];
  }

  $key = sanitize_text_field($_POST['key']);
  $login = sanitize_text_field($_POST['login']);
  $password = $_POST['password'];
  $password_confirm = $_POST['password_confirm'];

  if (empty($password) || empty($password_confirm)) {
    return ['errors' => 'Please enter both passwords.', 'success' => ''];
  }

  if ($password !== $password_confirm) {
    return ['errors' => 'Passwords do not match.', 'success' => ''];
  }

  if (strlen($password) < 8) {
    return ['errors' => 'Password must be at least 8 characters long.', 'success' => ''];
  }

  $validation = validate_password_reset_key($key, $login);
  if (!$validation['valid']) {
    return ['errors' => $validation['error'], 'success' => ''];
  }

  $user = $validation['user'];

  reset_password($user, $password);

  wp_set_current_user($user->ID);
  wp_set_auth_cookie($user->ID);

  return [
    'errors' => '',
    'success' => 'Your password has been reset successfully.',
    'redirect' => true
  ];
}
