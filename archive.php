<?php
switch (true) {
  case is_category():
    $title = single_cat_title('', false);
    $filter_type = 'category';
    $filter_value = get_queried_object()->slug;
    break;
  case is_tag():
    $title = single_tag_title('', false);
    $filter_type = 'tag';
    $filter_value = get_queried_object()->slug;
    break;
  case is_author():
    $title = 'By ' . get_the_author();
    $filter_type = 'author';
    $filter_value = get_queried_object()->user_nicename;
    break;
  default:
    $title = 'Archive';
    $filter_type = '';
    $filter_value = '';
}
?>

<?php get_header(); ?>
<?php get_sidebar(); ?>
<main class="main" id="main">
  <div class="container-grid">
    <header class="header">
      <?php
      get_template_part('template-parts/topbar', null, array('title' => $title));
      ?>
    </header>
    <?php
    get_template_part('template-parts/posts', null, array('filter_type' => $filter_type, 'filter_value' => $filter_value));
    ?>
  </div>
</main>

<?php get_footer(); ?>