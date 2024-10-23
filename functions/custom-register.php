<?php
function custom_authenticate_username_password($user, $username, $password)
{
  if (is_wp_error($user)) {
    return $user;
  }

  if (empty($username) || empty($password)) {
    return new WP_Error('empty_credentials', 'Empty credentials');
  }

  $user = get_user_by('login', $username);

  if (!$user) {
    return new WP_Error('invalid_username', 'Invalid username');
  }

  if (!wp_check_password($password, $user->user_pass, $user->ID)) {
    return new WP_Error('incorrect_password', 'Incorrect password');
  }

  return $user;
}


function register_link_url($url)
{
  if (! is_user_logged_in()) {
    if (get_option('users_can_register'))
      $url = '<li><a href="' . get_bloginfo('url') . " /register" . '">' . __('Register', 'yourtheme') . '</a></li>';
    else $url = '';
  } else {
    $url = '<li><a href="' . admin_url() . '">' . __('Site Admin', 'yourtheme') . '</a></li>';
  }
  return $url;
}
add_filter('register', 'register_link_url', 10, 2);

function handle_user_registration()
{
  global $wpdb, $user_ID;
  if ($_POST) {
    $errors = array();

    $username = sanitize_text_field($_POST['username']);
    if (strpos($username, ' ') !== false) {
      $errors['username'] = "Sorry, spaces are not allowed in usernames";
    }
    if (empty($username)) {
      $errors['username'] = "Please enter a username";
    } elseif (username_exists($username)) {
      $errors['username'] = "Username already exists, try another one";
    }

    $email = sanitize_email($_POST['email']);
    if (!is_email($email)) {
      $errors['email'] = "Please enter a valid email";
    } elseif (email_exists($email)) {
      $errors['email'] = "Email is already registered";
    }

    if (0 === preg_match("/.{6,}/", $_POST['password'])) {
      $errors['password'] = "Password must be at least 6 characters long";
    }

    if (0 !== strcmp($_POST['password'], $_POST['password_confirmation'])) {
      $errors['password_confirmation'] = "Passwords do not match";
    }

    if ($_POST['terms'] != "Yes") {
      $errors['terms'] = "You must agree to the Terms of Service";
    }

    if (0 === count($errors)) {
      $password = $_POST['password'];
      $new_user_id = wp_create_user($username, $password, $email);
      wp_redirect(home_url('/login/?success=1&u=' . $username));
      exit;
    }

    return $errors;
  }

  return array();
}
