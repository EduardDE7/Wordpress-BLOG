<?php
get_header();
get_sidebar();
?>

<main class="main">
  <div class="container-grid">
    <header class="header">
      <?php get_template_part('template-parts/topbar', args: ['title' => 'Search']); ?>
    </header>
    <?php
    if (have_posts()) :
      while (have_posts()) : the_post();
        get_template_part('template-parts/content', 'post');
      endwhile;
    else: ?>
      <div class="box">
        <p class="main__message">Sorry, no posts matched your criteria.</p>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>