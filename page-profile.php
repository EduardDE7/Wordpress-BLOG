<?php
/*
Template Name: User Profile
*/

if (!is_user_logged_in()) {
  wp_redirect(home_url('/login/'));
  exit;
}

$current_user = wp_get_current_user();

if (isset($_POST['update_profile'])) {
  if (!wp_verify_nonce($_POST['profile_nonce'], 'update_profile_nonce')) {
    die('Security check failed');
  }

  $user_id = $current_user->ID;
  $email = sanitize_email($_POST['user_email']);
  $first_name = sanitize_text_field($_POST['first_name']);
  $last_name = sanitize_text_field($_POST['last_name']);

  // Handle avatar upload if file is present
  if (isset($_FILES['custom_avatar']) && $_FILES['custom_avatar']['size'] > 0) {
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $attachment_id = media_handle_upload('custom_avatar', 0);

    if (is_wp_error($attachment_id)) {
      $upload_error = $attachment_id->get_error_message();
    } else {
      update_user_meta($user_id, 'custom_avatar', $attachment_id);
      $upload_success = true;
    }
  }

  // Update user data
  if ($email !== $current_user->user_email && !email_exists($email)) {
    wp_update_user(array('ID' => $user_id, 'user_email' => $email));
  }

  update_user_meta($user_id, 'first_name', $first_name);
  update_user_meta($user_id, 'last_name', $last_name);

  $profile_updated = true;
}

get_header();
get_sidebar();
?>

<main class="main">
  <header class="header">
    <?php get_template_part('template-parts/topbar', args: ['title' => 'Profile']); ?>
  </header>
  <div class="container-center">
    <div class="profile box">
      <h2 class="profile__title">Your Profile Hub</h2>
      <form method="post" enctype="multipart/form-data" class="profile__form">
        <div class="profile__avatar">
          <div class="profile__avatar-wrapper">
            <?php echo get_avatar($current_user->ID, 150); ?>
          </div>
          <input type="file" name="custom_avatar" accept="image/*">
        </div>

        <?php if (isset($upload_success)): ?>
          <div class="upload-success">Avatar updated successfully!</div>
        <?php endif; ?>

        <?php if (isset($upload_error)): ?>
          <div class="upload-error">Error uploading avatar: <?php echo $upload_error; ?></div>
        <?php endif; ?>

        <div class="profile__wrapper">
          <div class="profile__info">
            <ul class="profile__info-list">
              <li>
                <span class="profile__info-label">Username:</span>
                <span class="profile__info-value"><?php echo $current_user->user_login; ?></span>
              </li>
              <li>
                <label for="user_email" class="profile__info-label">Email:</label>
                <input type="email" id="user_email" name="user_email" value="<?php echo esc_attr($current_user->user_email); ?>" required>
              </li>
              <li>
                <label for="first_name" class="profile__info-label">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($current_user->first_name); ?>">
              </li>
              <li>
                <label for="last_name" class="profile__info-label">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($current_user->last_name); ?>">
              </li>
              <li>
                <span class="profile__info-label">Join Date:</span>
                <span class="profile__info-value"><?php echo date('d.m.Y', strtotime($current_user->user_registered)); ?></span>
              </li>
            </ul>
            <div class="profile__form-submit">
              <input type="submit" name="update_profile" value="Update Profile">
              <?php wp_nonce_field('update_profile_nonce', 'profile_nonce'); ?>
            </div>
          </div>

          <?php if (isset($profile_updated)): ?>
            <div class="update-success">Profile updated successfully!</div>
          <?php endif; ?>

          <div class="profile__actions">
            <?php if (current_user_can('manage_options')): ?>
              <a href="<?php echo esc_url(admin_url()); ?>" class="profile__actions-link profile__actions-link--admin">
                Admin Panel
              </a>
            <?php endif; ?>
            <a href="<?php echo wp_logout_url(home_url()); ?>" class="profile__actions-link profile__actions-link--logout">
              Log Out
            </a>
          </div>
        </div>
      </form>

      <?php
      $args = array(
        'author' => $current_user->ID,
        'post_type' => 'post',
        'posts_per_page' => 5
      );
      $user_posts = new WP_Query($args);

      if ($user_posts->have_posts()) :
      ?>
        <div class="profile__posts">
          <h3 class="profile__posts-title">Your Latest Posts</h3>
          <ul class="profile__posts-list">
            <?php while ($user_posts->have_posts()) : $user_posts->the_post(); ?>
              <li>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <span class="profile__post-date">(<?php echo get_the_date('d.m.Y'); ?>)</span>
              </li>
            <?php endwhile; ?>
          </ul>
        </div>
      <?php
      endif;
      wp_reset_postdata();
      ?>
    </div>
  </div>
</main>

<?php get_footer(); ?>