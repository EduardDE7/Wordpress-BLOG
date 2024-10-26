<?php
get_header();
get_sidebar();
?>

<main id="main" class="main">
  <div class="container">

    <header class="header">
      <?php get_template_part('template-parts/topbar', args: ['title' => get_the_title()]); ?>
    </header>

    <?php
    while (have_posts()) :
      the_post();

      get_template_part('template-parts/content', get_post_type(), ['is_full' => true]);



      if (comments_open() || get_comments_number()) :
        comments_template();
      endif;

    endwhile;
    ?>

    <?php get_template_part('template-parts/recommended-posts'); ?>

  </div>
</main>

<?php
get_footer();
