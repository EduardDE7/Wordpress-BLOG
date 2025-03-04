<?php
/*
Template Name: Categories Page
*/
get_header();
get_sidebar();

$categories = get_categories(array(
  'orderby' => 'name',
  'order'   => 'ASC',
  'hide_empty' => true
));
?>
<main class="main">
  <header class="header">
    <?php get_template_part('template-parts/topbar', args: ['title' => 'Categories']); ?>
  </header>
  <div class="container">
    <?php foreach ($categories as $category) :
      $posts = get_posts(array(
        'category' => $category->term_id,
        'posts_per_page' => 100,
        'orderby' => 'date',
        'order' => 'DESC'
      ));

      if (!empty($posts)) :
    ?>
        <div class="categories__section" data-category="<?php echo $category->slug; ?>">
          <div class="categories__header box">
            <h2 class="categories__title">
              <?php echo $category->name; ?>
              <span class="categories__count">(<?php echo $category->count; ?>)</span>
            </h2>
            <svg class="categories__arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </div>

          <div class="slider categories__slider" data-slider>
            <div class="slider__container categories__slider-container">
              <?php foreach ($posts as $post) :
                setup_postdata($post);
                get_template_part('template-parts/content', 'post');

              endforeach;
              wp_reset_postdata();
              ?>
            </div>
            <button class="slider__button slider__button--prev" data-slider-prev aria-label="Previous slides">
              <svg class="slider__button-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
              </svg>
            </button>
            <button class="slider__button slider__button--next" data-slider-next aria-label="Next slides">
              <svg class="slider__button-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </button>
          </div>
        </div>
    <?php
      endif;
    endforeach;
    ?>
  </div>
</main>

<?php get_footer(); ?>