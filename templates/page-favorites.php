<?php
/*
Template Name: Favorites Page
*/

get_header();
?>

<?php get_sidebar(); ?>

<main class="main <?php echo !is_user_logged_in() ? 'container-center' : ''; ?>">
  <?php if (!is_user_logged_in()) : ?>
    <div class="box favorites__box">
      <h2 class="box__title">Favorites</h2>
      <p>To view favorite posts, please log in to the system.</p>
      <a href="<?php echo esc_url(get_permalink(get_page_by_path('login'))); ?>" class="main-link">Log In</a>
      <p class="text-secondary">
        Don't have an account?
        <br>
        Registering will only take a minute -
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('register'))); ?>" class="main-link">Register</a>!
      </p>
    </div>
  <?php else : ?>
    <div class="container-grid">
      <header class="header">
        <?php get_template_part('template-parts/topbar', null, array('title' => 'Favorites')); ?>
        <?php echo get_categories_filter(); ?>
        <?php echo get_sort_selector(); ?>
      </header>

      <?php
      $favorites = get_user_favorites();

      if (!empty($favorites)) :
      ?>
        <div class="posts-container" data-filter-type="favorites" data-filter-value="all">
          <?php
          foreach ($favorites as $post) :
            setup_postdata($post);
            get_template_part('template-parts/content', 'post');
          endforeach;
          wp_reset_postdata();
          ?>
        </div>
        <button id="loadMore" class="box load-more">Load More</button>
      <?php else : ?>
        <div class="box main__message">
          <p>You have no favorite posts yet.</p>
          <a href="<?php echo esc_url(home_url('/')); ?>" class="main-link">Go to the list of posts</a>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</main>

<?php get_footer(); ?>