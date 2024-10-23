<?php
/*
Template Name: Reset Password
*/

$key = isset($_GET['key']) ? $_GET['key'] : '';
$login = isset($_GET['login']) ? $_GET['login'] : '';

if (empty($key) || empty($login)) {
  wp_redirect(home_url('/lost-password/'));
  exit;
}

$validation = validate_password_reset_key($key, $login);
$key_valid = $validation['valid'];
$key_error = !$key_valid ? $validation['error'] : '';

$result = handle_password_reset();
$errors = $result['errors'];
$success = $result['success'];

if (isset($result['redirect']) && $result['redirect']) {
  wp_redirect(home_url());
  exit;
}

get_header();
get_sidebar();
?>

<main class="authorize">
  <?php if (!$key_valid): ?>
    <div class="authorize__form">
      <div class="authorize__form-top">
        <h2 class="authorize__title">Invalid Reset Link</h2>
        <p class="authorize__error-message"><?php echo $key_error; ?></p>
      </div>
      <div class="authorize__form-links">
        <a href="<?php echo home_url('/lost-password/'); ?>">Request a new password reset link</a>
      </div>
    </div>
  <?php else: ?>
    <form class="authorize__form" method="post">
      <div class="authorize__form-top">
        <h2 class="authorize__title">Reset Your Password</h2>
        <?php if ($errors): ?>
          <p class="authorize__error-message"><?php echo $errors; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
          <p class="authorize__success-message"><?php echo $success; ?></p>
        <?php endif; ?>
      </div>

      <div class="authorize__form-group">
        <label hidden for="password">New Password</label>
        <input type="password" name="password" id="password" required
          placeholder="Enter new password">
      </div>

      <div class="authorize__form-group">
        <label hidden for="password_confirm">Confirm New Password</label>
        <input type="password" name="password_confirm" id="password_confirm" required
          placeholder="Confirm new password">
      </div>

      <input type="hidden" name="key" value="<?php echo esc_attr($key); ?>">
      <input type="hidden" name="login" value="<?php echo esc_attr($login); ?>">
      <input type="hidden" name="reset_password" value="1">

      <button type="submit" class="authorize__form-button main-button">
        Reset Password
      </button>

      <div class="authorize__form-links">
        <a href="<?php echo home_url('/login/'); ?>">Back to Login</a>
      </div>
    </form>
  <?php endif; ?>
</main>

<?php get_footer(); ?>