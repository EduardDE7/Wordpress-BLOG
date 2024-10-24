<?php
function modify_posts_query($query)
{
  if (!is_admin() && $query->is_main_query() && (is_home() || is_category())) {
    $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'date-desc';

    switch ($sort) {
      case 'date-asc':
        $query->set('orderby', 'date');
        $query->set('order', 'ASC');
        break;

      case 'date-desc':
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');
        break;

      case 'popularity':
        $query->set('meta_query', array(
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
        ));

        add_filter('posts_orderby', function ($orderby, $query) {
          global $wpdb;

          return "COALESCE((
                    SELECT meta_value FROM {$wpdb->postmeta} 
                    WHERE post_id = {$wpdb->posts}.ID AND meta_key = '_likes_count'
                  ), 0) - 
                  COALESCE((
                    SELECT meta_value FROM {$wpdb->postmeta} 
                    WHERE post_id = {$wpdb->posts}.ID AND meta_key = '_dislikes_count'
                  ), 0) DESC, " . $orderby;
        }, 10, 2);

        break;
    }
  }
}
add_action('pre_get_posts', 'modify_posts_query');


function get_sort_selector()
{
  $current_sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'date-desc';

  $html = '
    <div class="custom-select-wrapper">
      <div class="custom-select">
        <div class="custom-select__trigger box">
          <span>' . get_option_label($current_sort) . '</span>
          <svg class="custom-select__arrow" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </div>
        <div class="custom-options box">
          <span class="custom-option' . ($current_sort == 'date-desc' ? ' selected' : '') . '" data-value="date-desc">Newest First</span>
          <span class="custom-option' . ($current_sort == 'date-asc' ? ' selected' : '') . '" data-value="date-asc">Oldest First</span>
          <span class="custom-option' . ($current_sort == 'popularity' ? ' selected' : '') . '" data-value="popularity">Most Popular</span>
        </div>
      </div>
    </div>';

  return $html;
}

function get_option_label($value)
{
  $options = [
    'date-desc' => 'Newest First',
    'date-asc' => 'Oldest First',
    'popularity' => 'Most Popular'
  ];
  return isset($options[$value]) ? $options[$value] : 'Newest First';
}
