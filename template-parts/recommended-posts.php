<?php
$current_post_id = get_the_ID();
$current_post_tags = wp_get_post_tags($current_post_id);
$current_post_categories = get_the_category($current_post_id);

$tag_ids = !empty($current_post_tags) ? wp_list_pluck($current_post_tags, 'term_id') : [];
$category_ids = !empty($current_post_categories) ? wp_list_pluck($current_post_categories, 'term_id') : [];

$args = array(
  'post_type' => 'post',
  'posts_per_page' => 5,
  'post__not_in' => array($current_post_id),
  'tax_query' => array(
    'relation' => 'OR',
    array(
      'taxonomy' => 'post_tag',
      'field'    => 'term_id',
      'terms'    => $tag_ids,
    ),
    array(
      'taxonomy' => 'category',
      'field'    => 'term_id',
      'terms'    => $category_ids,
    ),
  ),
);

$recommended_posts = new WP_Query($args);

if ($recommended_posts->have_posts()) : ?>
  <div class="slider recommendations__slider" data-slider>
    <div class="slider__container recommendations__slider-container">
      <?php while ($recommended_posts->have_posts()) :                       $recommended_posts->the_post();
        get_template_part('template-parts/content', 'post');
      endwhile; ?>
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
<?php
endif;
wp_reset_postdata();
?>