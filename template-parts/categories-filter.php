<div class="categories-filter slider" data-slider>
  <div class="categories-filter__list slider__container">
    <?php
    $categories = get_categories();
    $current_category = get_queried_object();
    $is_home = is_home() || is_front_page();

    $current_sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'date-desc';


    $all_link_class = 'categories-filter__link' . ($is_home ? ' categories-filter__link--active' : '');
    $all_link_url = add_query_arg('sort', $current_sort, get_post_type_archive_link('post'));
    ?>

    <a href="<?php echo esc_url($all_link_url); ?>" class="<?php echo esc_attr($all_link_class); ?>">
      All
    </a>

    <?php
    foreach ($categories as $category) :
      $is_current = is_category() && $current_category->term_id == $category->term_id;
      $category_link_url = add_query_arg('sort', $current_sort, get_category_link($category->term_id));
      $category_link_class = 'categories-filter__link' . ($is_current ? ' categories-filter__link--active' : '');
    ?>
      <a href="<?php echo esc_url($category_link_url); ?>" class="<?php echo esc_attr($category_link_class); ?>">
        <?php echo esc_html($category->name); ?>
      </a>
    <?php endforeach; ?>
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