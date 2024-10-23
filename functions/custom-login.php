<?php
// function custom_login()
// {
//   if (strpos($_SERVER['REQUEST_URI'], '/wp-admin') !== false && !is_user_logged_in()) {
//     $redirect_to = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/wp-admin/';
//     wp_redirect(get_bloginfo('url') . "/login?redirect_to=" . urlencode($redirect_to));
//     exit;
//   }
// }
// add_action('init', 'custom_login');

function login_link_url($url)
{
  $url = get_bloginfo('url') . "/login";
  return $url;
}
add_filter('login_url', 'login_link_url', 10, 2);

function handle_user_login()
{
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error_message = '';
    $username = sanitize_text_field($_POST['username']);
    $password = sanitize_text_field($_POST['password']);
    $remember = !empty($_POST['rememberme']) ? true : false;

    $user = wp_authenticate($username, $password);

    if (is_wp_error($user)) {
      $error_message = 'Incorrect credentials.<br>Please try again or go to the <a href="' . esc_url(home_url('/register')) . '">registration page</a> to sign up.';
    } else {
      wp_set_auth_cookie($user->ID, $remember);
      wp_set_current_user($user->ID);

      if (user_can($user, 'manage_options')) {
        $secure = is_ssl();
        $secure_logged_in_cookie = $secure && 'https' === parse_url(get_option('home'), PHP_URL_SCHEME);
        setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN, $secure_logged_in_cookie, true);
      }

      $redirect_to = home_url();

      if (isset($_REQUEST['redirect_to']) && !empty($_REQUEST['redirect_to'])) {
        $redirect_to = urldecode($_REQUEST['redirect_to']);

        if (strpos($redirect_to, '/wp-admin') !== false && !user_can($user, 'manage_options')) {
          $redirect_to = home_url();
        }
      } else {
        if (isset($_SERVER['HTTP_REFERER'])) {
          $referer = wp_get_referer();
          if ($referer && strpos($referer, 'wp-login.php') === false && strpos($referer, 'login') === false) {
            $redirect_to = $referer;
          }
        }
      }

      wp_redirect($redirect_to);
      exit;
    }
    return $error_message;
  }
  return '';
}
