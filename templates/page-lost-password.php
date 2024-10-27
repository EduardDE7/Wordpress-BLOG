<?php
/*
Template Name: Lost Password
*/

$result = handle_lost_password_request();
$errors = $result['errors'];
$success = $result['success'];

get_header();
get_sidebar();
?>

<main class="authorize">
  <form class="authorize__form" method="post">
    <div class="authorize__form-top">
      <h2 class="authorize__title">Reset Password</h2>
      <?php if ($errors): ?>
        <p class="authorize__error-message"><?php echo $errors; ?></p>
      <?php endif; ?>
      <?php if ($success): ?>
        <p class="authorize__success-message"><?php echo $success; ?></p>
      <?php endif; ?>
      <p class="authorize__description">
        Please enter your username or email address. You will receive a link to create a new password via email.
      </p>
    </div>

    <div class="authorize__form-group">
      <label hidden for="user_login">Username or Email Address</label>
      <input type="text" name="user_login" id="user_login" required
        placeholder="Enter your username or email">
    </div>

    <button type="submit" class="authorize__form-button main-button">
      Get New Password
    </button>

    <div class="authorize__form-links">
      <a href="<?php echo home_url('/login/'); ?>">Back to Login</a>
    </div>
  </form>
</main>

<?php get_footer(); ?>