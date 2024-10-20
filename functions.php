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
  wp_enqueue_style('main', get_stylesheet_uri());

  wp_enqueue_style('wpdev-reset', get_template_directory_uri() . '/css/reset.css');
  wp_enqueue_style('wpdev-variables', get_template_directory_uri() . '/css/variables.css', array('wpdev-reset'));
  wp_enqueue_style('wpdev-main-css', get_template_directory_uri() . '/css/style.css', array('wpdev-variables'));

  wp_enqueue_script('main', get_template_directory_uri() . '/js/main.js');
}
add_action('wp_enqueue_scripts', 'wpdev_scripts');


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

 
function custom_login() {
  echo header("Location: " . get_bloginfo( 'url' ) . "/login");
}

add_action('login_head', 'custom_login');

function login_link_url( $url ) {
  $url = get_bloginfo( 'url' ) . "/login";
  return $url;
  }
add_filter( 'login_url', 'login_link_url', 10, 2 );

function handle_user_login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $error_message = '';

        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);
        $remember = !empty($_POST['rememberme']) ? true : false;

        $login_data = array(
            'user_login' => $username,
            'user_password' => $password,
            'remember' => $remember,
        );

        $user_verify = wp_signon($login_data, false);

        if (is_wp_error($user_verify)) {
            $error_message = 'Incorrect credentials.<br>Please try again or go to the <a href="' . esc_url(home_url('/register')) . '">registration page</a> to sign up.';
        } else {
            wp_redirect(home_url());
            exit;
        }

        return $error_message;
    }
    return '';
}


function register_link_url( $url ) {
  if ( ! is_user_logged_in() ) {
     if ( get_option('users_can_register') )
 $url = '<li><a href="' . get_bloginfo( 'url' ) . "/register" . '">' . __('Register', 'yourtheme') . '</a></li>';
      else  $url = '';
  } else { 
        $url = '<li><a href="' . admin_url() . '">' . __('Site Admin', 'yourtheme') . '</a></li>';
  }
  return $url;
}
add_filter( 'register', 'register_link_url', 10, 2 );

function handle_user_registration() {
  global $wpdb, $user_ID;
  
  if ($_POST) {
      $errors = array();
      
      $username = sanitize_text_field($_POST['username']);
      if (strpos($username, ' ') !== false) {
          $errors['username'] = "Извините, в именах пользователей нельзя использовать пробелы";
      }
      if (empty($username)) {
          $errors['username'] = "Пожалуйста введите имя пользователя";
      } elseif (username_exists($username)) {
          $errors['username'] = "Имя пользователя уже существует, попробуйте другое";
      }

      $email = sanitize_email($_POST['email']);
      if (!is_email($email)) {
          $errors['email'] = "Пожалуйста, введите действительный email";
      } elseif (email_exists($email)) {
          $errors['email'] = "Такой email уже зарегистрирован";
      }

      if (0 === preg_match("/.{6,}/", $_POST['password'])) {
          $errors['password'] = "Пароль должен состоять не менее, чем из шести символов.";
      }

      if (0 !== strcmp($_POST['password'], $_POST['password_confirmation'])) {
          $errors['password_confirmation'] = "Пароли не совпадают";
      }

      if ($_POST['terms'] != "Yes") {
          $errors['terms'] = "Вы должны согласиться с Условиями использования";
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



add_action('pre_get_posts', 'filter_favorites_search');

function filter_favorites_search($query) {
    if (!is_admin() && $query->is_search && isset($_GET['favorites']) && $_GET['favorites'] == '1') {
        $favorites = get_user_favorites();
        
        if ($favorites) {
            $query->set('post__in', $favorites);
            $query->set('orderby', 'post__in'); 
        } else {
            $query->set('post__in', array(0));
        }
    }
}


