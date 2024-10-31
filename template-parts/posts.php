<?php
$filter_type = isset($args['filter_type']) ? $args['filter_type'] : 'category';
$filter_value = isset($args['filter_value']) ? $args['filter_value'] : 'all';
?>
<?php if (have_posts()) : ?>
  <div class="posts-container"
    data-filter-type="<?php echo esc_attr($filter_type); ?>"
    data-filter-value="<?php echo esc_attr($filter_value); ?>">
    <?php
    while (have_posts()) : the_post();
      get_template_part('template-parts/content', 'post');
    endwhile; ?>
  </div>
  <button id="loadMore" class="box load-more">Load More</button>
<?php else : ?>
  <div class="box">
    <p class="main__message">Sorry, no posts matched your criteria.</p>
  </div>
<?php endif; ?>