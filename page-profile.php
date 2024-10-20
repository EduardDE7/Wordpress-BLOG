<?php
/*
Template Name: User Profile
*/

if (!is_user_logged_in()) {
  wp_redirect(home_url('/login/'));
  exit;
}

$current_user = wp_get_current_user();

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

      <div class="profile__wrapper">
      <div class="profile__info">
        <ul class="profile__info-list">
          <li>
            <span class="profile__info-label">Username:</span>
            <span class="profile__info-value"><?php echo $current_user->user_login; ?></span>
          </li>
          <li>
            <span class="profile__info-label">Email:</span>
            <span class="profile__info-value"><?php echo $current_user->user_email; ?></span>
          </li>
          <li>
            <span class="profile__info-label">First Name:</span>
            <span class="profile__info-value"><?php echo $current_user->first_name; ?></span>
          </li>
          <li>
            <span class="profile__info-label">Last Name:</span>
            <span class="profile__info-value"><?php echo $current_user->last_name; ?></span>
          </li>
          <li>
            <span class="profile__info-label">Join Date:</span>
            <span class="profile__info-value"><?php echo date('d.m.Y', strtotime($current_user->user_registered)); ?></span>
          </li>
        </ul>
      </div>

      <div class="profile__actions">
        <a href="<?php echo wp_logout_url(home_url()); ?>" class="profile__actions-link profile__actions-link--logout">
        Log Out
        </a>
            <a href="<?php echo admin_url('profile.php'); ?>" class="profile__actions-link profile__actions-link--edit">
            Edit Profile
            </a>
      </div>
      </div>


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


<?php
get_footer();
?>