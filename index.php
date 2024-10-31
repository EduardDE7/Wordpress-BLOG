<?php get_header(); ?>
<?php get_sidebar(); ?>
<main class="main" id="main">
  <div class="container-grid">
    <header class="header">
      <?php get_template_part('template-parts/topbar', args: ['title' => 'Home']); ?>
      <?php echo get_categories_filter(); ?>
      <?php echo get_sort_selector(); ?>
    </header>
    <?php get_template_part('template-parts/posts'); ?>
  </div>
</main>

<?php get_footer(); ?>