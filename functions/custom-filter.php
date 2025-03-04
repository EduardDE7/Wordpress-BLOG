<?php

function custom_filter_scripts()
{
  $version = time();
  wp_enqueue_script('filter', get_template_directory_uri() . '/assets/js/filter.js', array('jquery'), $version, true);
  wp_localize_script('filter', 'filterAjax', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('filter_posts_nonce')
  ));
}
add_action('wp_enqueue_scripts', 'custom_filter_scripts');

function filter_posts_ajax()
{
  check_ajax_referer('filter_posts_nonce', 'nonce');

  $filter_type = isset($_POST['filter_type']) ? sanitize_text_field($_POST['filter_type']) : 'category';
  $filter_value = isset($_POST['filter_value']) ? sanitize_text_field($_POST['filter_value']) : 'all';
  $sort = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'date-desc';
  $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
  $posts_per_page = get_option('posts_per_page');

  $args = array(
    'post_type' => 'post',
    'posts_per_page' => $posts_per_page,
    'paged' => $page,
  );

  if ($filter_value !== 'all') {
    switch ($filter_type) {
      case 'category':
        $args['tax_query'] = array(
          array(
            'taxonomy' => 'category',
            'field' => 'slug',
            'terms' => $filter_value
          )
        );
        break;
    }
  }

  switch ($sort) {
    case 'date-asc':
      $args['orderby'] = 'date';
      $args['order'] = 'ASC';
      break;

    case 'date-desc':
      $args['orderby'] = 'date';
      $args['order'] = 'DESC';
      break;

    case 'popularity':
      $args['meta_query'] = array(
        'relation' => 'OR',
        array(
          'key' => '_likes_count',
          'compare' => 'EXISTS',
        ),
        array(
          'key' => '_dislikes_count',
          'compare' => 'EXISTS',
        ),
        array(
          'key' => '_likes_count',
          'compare' => 'NOT EXISTS',
        ),
        array(
          'key' => '_dislikes_count',
          'compare' => 'NOT EXISTS',
        ),
      );

      add_filter('posts_orderby', 'sort_by_popularity', 10, 2);
      break;
  }

  $query = new WP_Query($args);

  if ($sort === 'popularity') {
    remove_filter('posts_orderby', 'sort_by_popularity');
  }

  $response = array(
    'posts' => '',
    'max_pages' => $query->max_num_pages,
    'found_posts' => $query->found_posts
  );

  if ($query->have_posts()) {
    ob_start();
    while ($query->have_posts()) {
      $query->the_post();
      get_template_part('template-parts/content', 'post');
    }
    $response['posts'] = ob_get_clean();
  }

  wp_reset_postdata();
  wp_send_json_success($response);
}
add_action('wp_ajax_filter_posts', 'filter_posts_ajax');
add_action('wp_ajax_nopriv_filter_posts', 'filter_posts_ajax');

function sort_by_popularity($orderby, $query)
{
  global $wpdb;
  return "COALESCE((
    SELECT meta_value FROM {$wpdb->postmeta} 
    WHERE post_id = {$wpdb->posts}.ID AND meta_key = '_likes_count'
  ), 0) - 
  COALESCE((
    SELECT meta_value FROM {$wpdb->postmeta} 
    WHERE post_id = {$wpdb->posts}.ID AND meta_key = '_dislikes_count'
  ), 0) DESC, " . $orderby;
}

function get_categories_filter()
{
  $args = array(
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => true
  );

  if (is_page_template('templates/page-favorites.php')) {
    $favorites = get_user_favorites();
    if (!empty($favorites)) {
      $favorite_categories = array();
      foreach ($favorites as $post_id) {
        $post_categories = wp_get_post_categories($post_id);
        $favorite_categories = array_merge($favorite_categories, $post_categories);
      }
      $favorite_categories = array_unique($favorite_categories);

      if (!empty($favorite_categories)) {
        $args['include'] = $favorite_categories;
      }
    } else {
      return;
    }
  }

  $categories = get_categories($args);
  if (empty($categories)) return;
?>
  <div class="categories-filter slider" data-slider>
    <ul class="categories-filter__list slider__container">
      <li class="categories-filter__item">
        <a href="#"
          class="categories-filter__link active"
          data-filter-type="category"
          data-filter-value="all">
          All
        </a>
      </li>
      <?php foreach ($categories as $category) : ?>
        <li class="categories-filter__item">
          <a href="#"
            class="categories-filter__link"
            data-filter-type="category"
            data-filter-value="<?php echo esc_attr($category->slug); ?>">
            <?php echo esc_html($category->name); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
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
}
