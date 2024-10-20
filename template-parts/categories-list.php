<div class="categories">
  <div id="categories-list" class="categories__list">
    <?php
    $categories = get_categories();
    $current_category = get_queried_object();
    $total_categories = count($categories);
    $visible_categories = 7;

    $is_home = is_home() || is_front_page();

    echo '<a href="' . get_post_type_archive_link('post') . '" class="categories__link ' . ($is_home ? 'categories__link--active' : '') . '">All</a>';

    foreach ($categories as $index => $category) {
      $is_current = is_category() && $current_category->term_id == $category->term_id;
      $hidden_class = $index >= $visible_categories ? 'categories__link--hidden' : '';

      echo '<a href="' . get_category_link($category->term_id) . '" class="categories__link ' . ($is_current ? 'categories__link--active' : '') . $hidden_class . '">'
        . $category->name . '</a>';
    }
    ?>
    <?php if ($total_categories > $visible_categories): ?>
      <button id="categories-toggle" class="categories__link">
        <svg width="17" height="10" viewBox="0 0 17 10" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M0.293031 0.793081C0.480558 0.60561 0.734866 0.500295 1.00003 0.500295C1.26519 0.500295 1.5195 0.60561 1.70703 0.793081L8.00003 7.08608L14.293 0.793081C14.3853 0.697571 14.4956 0.621389 14.6176 0.56898C14.7396 0.516571 14.8709 0.488985 15.0036 0.487831C15.1364 0.486677 15.2681 0.511978 15.391 0.562259C15.5139 0.61254 15.6255 0.686793 15.7194 0.780686C15.8133 0.874579 15.8876 0.986231 15.9379 1.10913C15.9881 1.23202 16.0134 1.3637 16.0123 1.49648C16.0111 1.62926 15.9835 1.76048 15.9311 1.88249C15.8787 2.00449 15.8025 2.11483 15.707 2.20708L9.41403 8.50008C9.03897 8.87502 8.53036 9.08565 8.00003 9.08565C7.4697 9.08565 6.96109 8.87502 6.58603 8.50008L0.293031 2.20708C0.10556 2.01955 0.000244141 1.76525 0.000244141 1.50008C0.000244141 1.23492 0.10556 0.980609 0.293031 0.793081Z" fill="currentColor" />
        </svg>
      </button>
    <?php endif; ?>
  </div>
</div>