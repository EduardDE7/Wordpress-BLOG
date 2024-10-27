<?php
/*
Template Name: Login
*/

if (is_user_logged_in()) {
  wp_redirect(home_url());
  exit;
}

$errors = handle_user_login();

get_header();
get_sidebar();
?>
<main class="authorize">

  <form id="login" class="authorize__form" name="form" action="<?php echo home_url(); ?>/login/" method="post">

    <div class="authorize__form-top">
      <h2 class="authorize__title">
        Welcome! Please Log In
      </h2>
      <p class="authorize__error-message">
        <?php if (!empty($errors)) {
          echo $errors;
        } ?>
      </p>
    </div>

    <div class="authorize__form-group">
      <label hidden for="username">Username</label>
      <input required id="username" type="text" placeholder="Enter your username" name="username">
    </div>

    <div class="authorize__form-group">
      <label hidden for="password">Password</label>
      <input required id="password" type="password" placeholder="Enter your password" name="password">
    </div>

    <div class="authorize__form-group">
      <label class="checkbox-label" for="rememberme">
        Remember Me
        <input type="checkbox" id="rememberme" name="rememberme" value="forever">
        <span class="checkmark"></span>
      </label>
    </div>

    <button class="authorize__form-button main-button" type="submit" name="submit">Sign In</button>

    <div class="authorize__form-links">
      <a href="<?= home_url(); ?>/lost-password/">Forgot your password?</a>
      <a href="<?= home_url(); ?>/register/">Don't have an account? Sign up!</a>
    </div>
  </form>

</main>
<?php
get_footer();
?>