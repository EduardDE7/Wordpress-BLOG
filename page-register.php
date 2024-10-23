<?php
/*
Template Name: Register
*/


if (is_user_logged_in()) {
  wp_redirect(home_url());
  exit;
}

$errors = handle_user_registration();

get_header();
get_sidebar();
?>

<div class="authorize">
  <form id="wp_signup_form" class="authorize__form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" style="max-width: 500px">

    <div class="authorize__form-top">
      <h2 class="authorize__title">
        Welcome! Please Sign Up
      </h2>
      <p class="authorize__error-message">
        <?php
        if (!empty($error_message)) {
          echo $error_message;
        } ?>
      </p>
      <p class="authorize__error-message">The website is still under development! <br> Please do not enter any real data at this time.</p>
    </div>

    <div class="authorize__form-group">
      <label hidden for="username">Username</label>
      <input type="text" name="username" id="username" placeholder="Enter your username" value="<?= isset($_REQUEST['username']) ? $_REQUEST['username']  : '' ?>">
      <span class="error"><?= isset($errors['username']) ? $errors['username']  : '' ?></span>
    </div>

    <div class="authorize__form-group">
      <label hidden for="email">Email</label>
      <input type="text" name="email" id="email" placeholder="Enter your email" value="<?= isset($_REQUEST['email']) ? $_REQUEST['email']  : '' ?>">
      <span class="error"><?= isset($errors['email']) ? $errors['email']  : '' ?></span>
    </div>

    <div class="authorize__form-group">
      <label hidden for="password">Password</label>
      <input type="password" name="password" id="password" placeholder="Enter your password" value="<?= isset($_REQUEST['password']) ? $_REQUEST['password']  : '' ?>">
      <span class="error"><?= isset($errors['password']) ? $errors['password']  : '' ?></span>
    </div>

    <div class="authorize__form-group">
      <label hidden for="password_confirmation">Confirm Password</label>
      <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat your password" value="<?= isset($_REQUEST['password_confirmation']) ? $_REQUEST['password_confirmation']  : '' ?>">
      <span class="error"><?= isset($errors['password_confirmation']) ? $errors['password_confirmation']  : '' ?></span>
    </div>

    <div class="authorize__form-group">
      <label class="checkbox-label" for="terms">
        I agree to the Terms of Service
        <input name="terms" id="terms" type="checkbox" value="Yes">
        <span class="checkmark"></span>
      </label><br>
      <span class="error"><?= isset($errors['terms']) ? $errors['terms']  : '' ?></span>
    </div>

    <button class="authorize__form-button main-button" type="submit">Sign Up</button>

    <div class="authorize__form-links">
      <a href="<?= home_url(); ?>/login/">Already have an account? Sign in!</a>
    </div>

  </form>
</div>


<?php
get_footer();
?>