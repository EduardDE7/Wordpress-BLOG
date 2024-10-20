<?php
/*
Template Name: Favorites Page
*/

get_header();
?>
<?php get_sidebar(); ?>
<?php if (!is_user_logged_in()) : ?>
  <main class="main container-center">
    <div class="box favorites__box">
      <p>You must be logged in to view your favorites.</p>
      <a href="<?php echo get_permalink(get_page_by_path('login')); ?>" class="main-link">Login</a>
      <p class="text-secondary">
        Don't have an account?
        <br>
        It's free, and only takes a minute to
        <a href="<?php echo get_permalink(get_page_by_path('register')); ?>" class="main-link">register</a>!
      </p>
    </div>


  <?php else : ?>
    <main class="main">
      <div class="container-grid">
        <header class="header">
          <?php get_template_part('template-parts/topbar', args: ['title' => 'Favorites']); ?>
          <?php get_template_part('template-parts/categories', 'list'); ?>
        </header>

        <?php
        $favorites = get_user_favorites();

        if ($favorites) :
          foreach ($favorites as $post_id) :
            $post = get_post($post_id);
            setup_postdata($post);
            get_template_part('template-parts/content', 'post');
          endforeach;
          wp_reset_postdata();
        else :
          echo '<p>You have no favorite posts yet.</p>';
        endif;
        ?>
      <?php endif; ?>

      </div>
    </main>

    <?php get_footer(); ?>